<?php
declare(strict_types=1);

define('KSI_STATELESS_API', true);
define('BASE_PATH', dirname(__DIR__, 3) . DIRECTORY_SEPARATOR);
require_once BASE_PATH . 'config/config.php';
require_once BASE_PATH . 'src/RefurbishedApi.php';

use KeySoftItalia\Api\ApiException;
use KeySoftItalia\Api\ApiLogger;
use KeySoftItalia\Api\FileStore;
use KeySoftItalia\Api\HmacAuthenticator;
use KeySoftItalia\Api\JsonResponse;
use KeySoftItalia\Api\RateLimiter;
use KeySoftItalia\Api\RefurbishedRepository;
use KeySoftItalia\Api\RefurbishedValidator;

$rawBody = file_get_contents('php://input') ?: '';
$ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$path = parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH) ?: '';
$method = strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));
$externalRef = null;
$logger = new ApiLogger(getenv('KSI_API_LOG_PATH') ?: BASE_PATH . 'logs/refurbished-api.log');

try {
    $runtime = getenv('KSI_API_RUNTIME_PATH') ?: BASE_PATH . 'cache/refurbished-api';
    $store = new FileStore($runtime);
    (new RateLimiter($store, (int)(getenv('KSI_API_RATE_LIMIT') ?: 60)))->check($ip);
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    foreach ($_SERVER as $name => $value) {
        if (str_starts_with($name, 'HTTP_')) {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    (new HmacAuthenticator(
        (string)getenv('KSI_API_KEY'),
        (string)getenv('KSI_API_SECRET'),
        $store
    ))->authenticate($headers, $rawBody);

    if ($method === 'GET' && preg_match('#/api/v1/refurbished/ping/?$#', $path)) {
        $logger->write(null, '200 ping', $ip);
        JsonResponse::send(200, ['ok' => true, 'version' => '1.0']);
    }

    if ($method !== 'POST') {
        throw new ApiException(404, 'not_found', 'Endpoint non trovato');
    }
    $data = json_decode($rawBody, true);
    if (!is_array($data)) {
        throw new ApiException(422, 'validation', 'JSON non valido', ['body' => 'JSON non valido']);
    }
    $repository = new RefurbishedRepository($pdo);

    if (preg_match('#/api/v1/refurbished/upsert/?$#', $path)) {
        $externalRef = isset($data['external_ref']) ? (string)$data['external_ref'] : null;
        $result = $repository->upsert(RefurbishedValidator::validateUpsert($data));
        $status = $result['action'] === 'created' ? 201 : 200;
        $url = url('prodotti.php?sku=' . rawurlencode((string)$data['external_ref']));
        $logger->write($externalRef, $status . ' ' . $result['action'], $ip);
        JsonResponse::send($status, ['ok' => true, ...$result, 'url' => $url]);
    }

    if (preg_match('#/api/v1/refurbished/(RIC\d+)/status/?$#', $path, $matches)) {
        $externalRef = $matches[1];
        $statusValue = RefurbishedValidator::validateStatus($data);
        $productId = $repository->setStatus($externalRef, $statusValue);
        if ($productId === null) {
            throw new ApiException(404, 'not_found', 'Prodotto non trovato');
        }
        $logger->write($externalRef, '200 status:' . $statusValue, $ip);
        JsonResponse::send(200, [
            'ok' => true,
            'action' => 'updated',
            'product_id' => $productId,
            'status' => $statusValue,
            'url' => url('prodotti.php?sku=' . rawurlencode($externalRef)),
        ]);
    }
    throw new ApiException(404, 'not_found', 'Endpoint non trovato');
} catch (ApiException $e) {
    $logger->write($externalRef, $e->status . ' ' . $e->apiError, $ip);
    $payload = ['ok' => false, 'error' => $e->apiError];
    if ($e->details) {
        $payload['errors'] = $e->details;
    }
    JsonResponse::send($e->status, $payload);
} catch (Throwable $e) {
    error_log('Refurbished API: ' . $e->getMessage());
    $logger->write($externalRef, '500 internal', $ip);
    JsonResponse::send(500, ['ok' => false, 'error' => 'internal']);
}
