<?php
declare(strict_types=1);
namespace KeySoftItalia;

/** Tracks new uploads until the database write succeeds. */
final class AdminMedia
{
    private array $created = [];
    private array $retired = [];
    private bool $saved = false;
    public function __construct() { register_shutdown_function(fn() => $this->abort()); }

    public function upload(?array $file, string $folder, int $maxMb = 5, bool $pdf = false, bool $svg = false): ?string
    {
        if (!$file || ($file['error'] ?? null) === UPLOAD_ERR_NO_FILE) return null;
        if (($file['error'] ?? null) !== UPLOAD_ERR_OK || !is_string($file['tmp_name'] ?? null)
            || !is_string($file['name'] ?? null) || !is_uploaded_file($file['tmp_name'])) {
            throw new \InvalidArgumentException('Caricamento file non riuscito.');
        }
        if (filesize($file['tmp_name']) > $maxMb * 1024 * 1024) throw new \InvalidArgumentException("File troppo grande. Massimo {$maxMb} MB.");
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
        if ($pdf) {
            if ($ext !== 'pdf' || $mime !== 'application/pdf') throw new \InvalidArgumentException('Seleziona un PDF valido.');
            $ext = 'pdf';
        } elseif ($svg && $ext === 'svg') {
            self::safeSvg(file_get_contents($file['tmp_name']));
        } else {
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) || !isset($types[$mime]) || !@getimagesize($file['tmp_name'])) {
                throw new \InvalidArgumentException('Seleziona un’immagine valida.');
            }
            $ext = $types[$mime];
        }
        $dir = self::directory($folder);
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) throw new \RuntimeException('Cartella upload non disponibile.');
        $relative = $folder . '/' . bin2hex(random_bytes(16)) . '.' . $ext;
        $absolute = dirname(__DIR__) . '/' . $relative;
        if (!move_uploaded_file($file['tmp_name'], $absolute)) throw new \RuntimeException('Impossibile salvare il file.');
        $this->created[] = $absolute;
        return $relative;
    }

    public static function safeSvg(string $xml): void
    {
        if (preg_match('/<!DOCTYPE|<!ENTITY|<\?/i', preg_replace('/^\s*<\?xml[^?]*\?>/i', '', $xml))) throw new \InvalidArgumentException('SVG non consentito.');
        $doc = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        try { $valid = $doc->loadXML($xml, LIBXML_NONET); }
        finally { libxml_clear_errors(); libxml_use_internal_errors($previous); }
        if (!$valid || $doc->documentElement?->localName !== 'svg' || $doc->documentElement->namespaceURI !== 'http://www.w3.org/2000/svg') throw new \InvalidArgumentException('SVG non valido.');
        foreach ($doc->getElementsByTagName('*') as $element) {
            if (str_starts_with(strtolower($element->localName), 'animate') || in_array(strtolower($element->localName), ['script', 'foreignobject', 'iframe', 'object', 'embed', 'set'], true)) throw new \InvalidArgumentException('SVG con contenuto attivo non consentito.');
            foreach ($element->attributes as $attribute) {
                $name = strtolower($attribute->localName); $value = trim($attribute->value);
                if (str_starts_with($name, 'on') || ($name === 'style' && str_contains($value, '\\')) || ($name === 'href' && !str_starts_with($value, '#'))
                    || preg_match('/javascript:|@import|url\s*\(\s*["\']?(?!#)/i', $value)) throw new \InvalidArgumentException('SVG con riferimenti esterni non consentito.');
            }
            if ($element->localName === 'style' && (str_contains($element->textContent, '\\') || preg_match('/@import|url\s*\(/i', $element->textContent))) throw new \InvalidArgumentException('Stile SVG non consentito.');
        }
    }

    private static function directory(string $folder): string
    {
        if (!in_array($folder, ['uploads/operators', 'uploads/utilities', 'uploads/flyers', 'uploads/videos', 'assets/img/team'], true)) throw new \LogicException('Cartella media non consentita.');
        return dirname(__DIR__) . '/' . $folder;
    }

    public function retire(?string $path, string $folder): void { if ($path) $this->retired[] = [$path, $folder]; }
    public function saved(): void
    {
        $this->saved = true;
        foreach ($this->retired as [$path, $folder]) self::remove($path, $folder);
    }
    public function abort(): void { if (!$this->saved) foreach ($this->created as $file) if (is_file($file)) unlink($file); }
    public static function remove(?string $path, string $folder): void
    {
        if (!$path) return;
        if ($folder === 'assets/img/team' && str_starts_with($path, 'img/team/')) $path = 'assets/' . $path;
        if (!str_starts_with($path, $folder . '/') || str_contains($path, '..') || str_contains($path, '\\')) return;
        $file = realpath(dirname(__DIR__) . '/' . $path); $base = realpath(self::directory($folder));
        if ($file && $base && str_starts_with(str_replace('\\', '/', $file), rtrim(str_replace('\\', '/', $base), '/') . '/') && is_file($file)) unlink($file);
    }
}
