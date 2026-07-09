<?php
declare(strict_types=1);

namespace KeySoftItalia\Api;

use DateTimeImmutable;
use DateTimeZone;
use PDO;
use RuntimeException;
use Throwable;

final class ApiException extends RuntimeException
{
    public function __construct(
        public readonly int $status,
        public readonly string $apiError,
        string $message = '',
        public readonly array $details = []
    ) {
        parent::__construct($message);
    }
}

final class JsonResponse
{
    public static function send(int $status, array $data): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

final class FileStore
{
    public function __construct(private readonly string $directory)
    {
        if (!is_dir($directory) && !mkdir($directory, 0750, true) && !is_dir($directory)) {
            throw new RuntimeException('Impossibile creare la directory runtime API');
        }
    }

    public function update(string $name, callable $callback): mixed
    {
        $path = $this->directory . DIRECTORY_SEPARATOR . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $name);
        $handle = fopen($path, 'c+');
        if ($handle === false || !flock($handle, LOCK_EX)) {
            throw new RuntimeException('Impossibile acquisire il lock API');
        }
        try {
            rewind($handle);
            $raw = stream_get_contents($handle);
            $state = $raw ? json_decode($raw, true) : [];
            if (!is_array($state)) {
                $state = [];
            }
            $result = $callback($state);
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($state, JSON_UNESCAPED_SLASHES));
            fflush($handle);
            return $result;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}

final class HmacAuthenticator
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $secret,
        private readonly FileStore $store,
        private readonly int $windowSeconds = 300
    ) {}

    public function authenticate(array $headers, string $rawBody, ?int $now = null): void
    {
        if ($this->apiKey === '' || $this->secret === '') {
            throw new ApiException(500, 'internal', 'Credenziali API non configurate');
        }

        $headers = array_change_key_case($headers, CASE_LOWER);
        $key = trim((string)($headers['x-ksi-key'] ?? ''));
        $signature = strtolower(trim((string)($headers['x-ksi-signature'] ?? '')));
        $timestampRaw = trim((string)($headers['x-ksi-timestamp'] ?? ''));

        if ($key === '' || $signature === '' || !preg_match('/^\d+$/', $timestampRaw)) {
            throw new ApiException(401, 'authentication', 'Credenziali mancanti');
        }
        if (!hash_equals($this->apiKey, $key)) {
            throw new ApiException(401, 'authentication', 'Credenziali non valide');
        }

        $now ??= time();
        $timestamp = (int)$timestampRaw;
        if (abs($now - $timestamp) > $this->windowSeconds) {
            throw new ApiException(401, 'authentication', 'Timestamp scaduto');
        }

        $expected = hash_hmac('sha256', $rawBody, $this->secret);
        if (!preg_match('/^[a-f0-9]{64}$/', $signature) || !hash_equals($expected, $signature)) {
            throw new ApiException(401, 'authentication', 'Firma non valida');
        }

        $replayId = hash('sha256', $key . "\n" . $timestampRaw . "\n" . $signature);
        $this->store->update('replay.json', function (array &$state) use ($replayId, $now): void {
            $state = array_filter($state, static fn($expires): bool => (int)$expires >= $now);
            if (isset($state[$replayId])) {
                throw new ApiException(401, 'authentication', 'Richiesta già ricevuta');
            }
            $state[$replayId] = $now + $this->windowSeconds;
        });
    }
}

final class RateLimiter
{
    public function __construct(
        private readonly FileStore $store,
        private readonly int $limit = 60,
        private readonly int $period = 60
    ) {}

    public function check(string $client): void
    {
        $now = time();
        $bucket = hash('sha256', $client);
        $this->store->update('rate-limit.json', function (array &$state) use ($now, $bucket): void {
            $state = array_filter(
                $state,
                fn(array $entry): bool => (($entry['started'] ?? 0) + $this->period) > $now
            );
            $entry = $state[$bucket] ?? ['started' => $now, 'count' => 0];
            if (($entry['started'] + $this->period) <= $now) {
                $entry = ['started' => $now, 'count' => 0];
            }
            $entry['count']++;
            $state[$bucket] = $entry;
            if ($entry['count'] > $this->limit) {
                throw new ApiException(429, 'rate_limit', 'Limite richieste superato');
            }
        });
    }
}

final class ApiLogger
{
    public function __construct(private readonly string $path) {}

    public function write(?string $externalRef, string $outcome, string $ip): void
    {
        $directory = dirname($this->path);
        if (!is_dir($directory)) {
            @mkdir($directory, 0750, true);
        }
        $line = json_encode([
            'timestamp' => (new DateTimeImmutable('now'))->format(DATE_ATOM),
            'external_ref' => $externalRef,
            'outcome' => $outcome,
            'ip' => $ip,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        @file_put_contents($this->path, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

final class RefurbishedValidator
{
    public static function validateUpsert(array $data): array
    {
        foreach (['identity', 'device', 'condition', 'commercial', 'source'] as $section) {
            if (!isset($data[$section]) || !is_array($data[$section])) {
                $data[$section] = [];
            }
        }
        if (!isset($data['device']['specs']) || !is_array($data['device']['specs'])) {
            $data['device']['specs'] = [];
        }
        if (!isset($data['condition']['accessories']) || !is_array($data['condition']['accessories'])) {
            $data['condition']['accessories'] = [];
        }
        if (!isset($data['photos']) || !is_array($data['photos'])) {
            $data['photos'] = [];
        }
        $errors = [];
        $ref = trim((string)($data['external_ref'] ?? ''));
        if ($ref === '') {
            $errors['external_ref'] = 'Riferimento mancante';
        } elseif (!preg_match('/^RIC\d+$/', $ref)) {
            $errors['external_ref'] = 'Formato non valido (atteso RIC + cifre)';
        }

        $price = $data['commercial']['price_eur'] ?? null;
        if ($price === null || $price === '') {
            $errors['commercial.price_eur'] = 'Prezzo mancante';
        } elseif (!is_numeric($price) || (float)$price <= 0) {
            $errors['commercial.price_eur'] = 'Il prezzo deve essere maggiore di zero';
        }

        if (trim((string)($data['identity']['imei'] ?? '')) === ''
            && trim((string)($data['identity']['serial'] ?? '')) === '') {
            $errors['identity'] = 'Inserire almeno IMEI o seriale';
        }
        foreach (['brand' => 'Marca mancante', 'model' => 'Modello mancante', 'title' => 'Titolo mancante'] as $field => $message) {
            if (trim((string)($data['device'][$field] ?? '')) === '') {
                $errors["device.$field"] = $message;
            }
        }
        if (!in_array($data['status'] ?? null, ['publish', 'draft'], true)) {
            $errors['status'] = 'Stato non valido';
        }
        if ($errors) {
            throw new ApiException(422, 'validation', 'Payload non valido', $errors);
        }
        return $data;
    }

    public static function validateStatus(array $data): string
    {
        $status = $data['status'] ?? null;
        if (!in_array($status, ['sold', 'hidden', 'publish'], true)) {
            throw new ApiException(422, 'validation', 'Payload non valido', ['status' => 'Stato non valido']);
        }
        return $status;
    }
}

final class RefurbishedRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function upsert(array $data): array
    {
        $this->pdo->beginTransaction();
        try {
            $modelId = $this->resolveModel(
                trim((string)($data['device']['category'] ?? 'Altro')) ?: 'Altro',
                trim((string)$data['device']['brand']),
                trim((string)$data['device']['model'])
            );
            $ref = trim((string)$data['external_ref']);
            $select = $this->pdo->prepare('SELECT id FROM products WHERE sku = ? FOR UPDATE');
            $select->execute([$ref]);
            $productId = $select->fetchColumn();
            $created = $productId === false;

            $specs = is_array($data['device']['specs'] ?? null) ? $data['device']['specs'] : [];
            $accessories = is_array($data['condition']['accessories'] ?? null) ? array_values($data['condition']['accessories']) : [];
            $grade = $this->normalizeGrade((string)($data['condition']['grade'] ?? 'A'));
            $storage = $this->storageFromSpecs($specs);
            $color = isset($specs['Colore']) ? mb_substr((string)$specs['Colore'], 0, 40) : null;
            $apiStatus = (string)$data['status'];
            $available = $apiStatus === 'publish' ? 1 : 0;
            $sourceDate = $this->mysqlDate($data['source']['pushed_at'] ?? null);
            $values = [
                $modelId, mb_substr((string)$data['device']['title'], 0, 255), $color, $storage, $grade,
                (float)$data['commercial']['price_eur'], mb_substr((string)$data['device']['title'], 0, 255),
                (string)($data['device']['description'] ?? ''), $apiStatus,
                $this->nullable($data['identity']['imei'] ?? null), $this->nullable($data['identity']['serial'] ?? null),
                json_encode($specs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $this->boundedInt($data['condition']['battery_pct'] ?? null, 0, 100),
                $this->nullable($data['condition']['notes'] ?? null),
                json_encode($accessories, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $this->boundedInt($data['commercial']['warranty_months'] ?? null, 0, 120),
                $this->nullable($data['source']['system'] ?? null),
                $this->boundedInt($data['source']['device_id'] ?? null, 0, PHP_INT_MAX),
                $sourceDate, $available,
            ];

            if ($created) {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO products
                    (model_id, sku, title, color, storage_gb, grade, price_eur, short_desc, full_desc, api_status,
                     imei, serial_number, specs_json, battery_pct, condition_notes, accessories_json,
                     warranty_months, source_system, source_device_id, source_pushed_at, is_available, is_featured)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)'
                );
                $insert = $values;
                array_splice($insert, 1, 0, [$ref]);
                $stmt->execute($insert);
                $productId = (int)$this->pdo->lastInsertId();
            } else {
                $stmt = $this->pdo->prepare(
                    'UPDATE products SET model_id=?, title=?, color=?, storage_gb=?, grade=?, price_eur=?,
                     short_desc=?, full_desc=?, api_status=?, imei=?, serial_number=?, specs_json=?,
                     battery_pct=?, condition_notes=?, accessories_json=?, warranty_months=?, source_system=?,
                     source_device_id=?, source_pushed_at=?, is_available=? WHERE id=?'
                );
                $stmt->execute([...$values, (int)$productId]);
                $productId = (int)$productId;
            }

            $this->syncImages($productId, $data['photos'] ?? [], (string)$data['device']['title']);
            $this->pdo->commit();
            return ['action' => $created ? 'created' : 'updated', 'product_id' => $productId];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    public function setStatus(string $externalRef, string $status): ?int
    {
        $stmt = $this->pdo->prepare(
            'UPDATE products SET api_status = ?, is_available = ? WHERE sku = ?'
        );
        $stmt->execute([$status, $status === 'publish' ? 1 : 0, $externalRef]);
        if ($stmt->rowCount() > 0) {
            $find = $this->pdo->prepare('SELECT id FROM products WHERE sku = ?');
            $find->execute([$externalRef]);
            return (int)$find->fetchColumn();
        }
        $find = $this->pdo->prepare('SELECT id FROM products WHERE sku = ?');
        $find->execute([$externalRef]);
        $id = $find->fetchColumn();
        return $id === false ? null : (int)$id;
    }

    private function resolveModel(string $category, string $brand, string $model): int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM devices WHERE name = ? LIMIT 1');
        $stmt->execute([$category]);
        $deviceId = $stmt->fetchColumn();
        if ($deviceId === false) {
            $slug = strtolower(trim((string)preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $category) ?: $category), '-'));
            $slug = $slug ?: 'device-' . substr(hash('sha256', $category), 0, 8);
            $insert = $this->pdo->prepare('INSERT INTO devices (slug, name) VALUES (?, ?)');
            $insert->execute([$slug, $category]);
            $deviceId = (int)$this->pdo->lastInsertId();
        }
        $stmt = $this->pdo->prepare('SELECT id FROM brands WHERE device_id = ? AND name = ? LIMIT 1');
        $stmt->execute([(int)$deviceId, $brand]);
        $brandId = $stmt->fetchColumn();
        if ($brandId === false) {
            $insert = $this->pdo->prepare('INSERT INTO brands (device_id, name, is_active) VALUES (?, ?, 1)');
            $insert->execute([(int)$deviceId, $brand]);
            $brandId = (int)$this->pdo->lastInsertId();
        }
        $stmt = $this->pdo->prepare('SELECT id FROM models WHERE brand_id = ? AND name = ? LIMIT 1');
        $stmt->execute([(int)$brandId, $model]);
        $modelId = $stmt->fetchColumn();
        if ($modelId === false) {
            $insert = $this->pdo->prepare('INSERT INTO models (brand_id, name, is_active) VALUES (?, ?, 1)');
            $insert->execute([(int)$brandId, $model]);
            $modelId = (int)$this->pdo->lastInsertId();
        }
        return (int)$modelId;
    }

    private function syncImages(int $productId, mixed $photos, string $title): void
    {
        if (!is_array($photos)) {
            return;
        }
        $normalized = [];
        foreach ($photos as $photo) {
            if (!is_array($photo) || !filter_var($photo['url'] ?? null, FILTER_VALIDATE_URL)
                || strtolower((string)parse_url((string)$photo['url'], PHP_URL_SCHEME)) !== 'https') {
                continue;
            }
            $normalized[] = [
                'path' => (string)$photo['url'],
                'sort_order' => max(0, (int)($photo['position'] ?? 0)),
            ];
        }
        usort($normalized, static fn(array $a, array $b): int => $a['sort_order'] <=> $b['sort_order']);
        $currentStmt = $this->pdo->prepare(
            'SELECT path, sort_order, is_cover FROM product_images WHERE product_id = ? ORDER BY sort_order, id'
        );
        $currentStmt->execute([$productId]);
        $expected = array_map(
            static fn(array $image, int $index): array => $image + ['is_cover' => $index === 0 ? 1 : 0],
            $normalized,
            array_keys($normalized)
        );
        if ($currentStmt->fetchAll(PDO::FETCH_ASSOC) == $expected) {
            return;
        }
        $this->pdo->prepare('DELETE FROM product_images WHERE product_id = ?')->execute([$productId]);
        $insert = $this->pdo->prepare(
            'INSERT INTO product_images (product_id, path, alt_text, sort_order, is_cover) VALUES (?, ?, ?, ?, ?)'
        );
        foreach ($expected as $image) {
            $insert->execute([$productId, $image['path'], mb_substr($title, 0, 120), $image['sort_order'], $image['is_cover']]);
        }
    }

    private function normalizeGrade(string $grade): string
    {
        return in_array($grade, ['Nuovo', 'Expo', 'A+', 'A', 'B', 'C'], true) ? $grade : 'A';
    }

    private function storageFromSpecs(array $specs): ?int
    {
        $value = (string)($specs['Memoria'] ?? $specs['Storage'] ?? '');
        return preg_match('/(\d+)/', $value, $match) ? (int)$match[1] : null;
    }

    private function nullable(mixed $value): ?string
    {
        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    private function boundedInt(mixed $value, int $min, int $max): ?int
    {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return null;
        }
        return max($min, min($max, (int)$value));
    }

    private function mysqlDate(mixed $value): ?string
    {
        if (!$value) {
            return null;
        }
        try {
            return (new DateTimeImmutable((string)$value))
                ->setTimezone(new DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s');
        } catch (Throwable) {
            return null;
        }
    }
}
