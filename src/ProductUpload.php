<?php
declare(strict_types=1);

namespace KeySoftItalia;

final class ProductUpload
{
    public static function images(?array $files): array
    {
        if ($files === null) return [];
        foreach (['name', 'tmp_name', 'error', 'size'] as $key) {
            if (!isset($files[$key]) || !is_array($files[$key])) {
                throw new \InvalidArgumentException('Formato immagini non valido.');
            }
        }
        $images = [];
        $mimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
        foreach ($files['error'] as $key => $error) {
            if ($error === UPLOAD_ERR_NO_FILE) continue;
            if ($error !== UPLOAD_ERR_OK) throw new \InvalidArgumentException('Caricamento immagine non riuscito. Massimo 2 MB per immagine.');
            $tmp = $files['tmp_name'][$key] ?? null;
            $name = $files['name'][$key] ?? null;
            if (!is_string($tmp) || !is_string($name) || !is_uploaded_file($tmp)) {
                throw new \InvalidArgumentException('File immagine non valido.');
            }
            if (filesize($tmp) > 2 * 1024 * 1024) throw new \InvalidArgumentException('Immagine troppo grande. Massimo 2 MB.');
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $info = @getimagesize($tmp);
            $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($tmp);
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) || !$info || !isset($mimeTypes[$mime]) || ($info['mime'] ?? '') !== $mime) {
                throw new \InvalidArgumentException('Sono ammesse solo immagini JPG, PNG, GIF o WebP valide.');
            }
            $images[] = ['tmp' => $tmp, 'extension' => $mimeTypes[$mime]];
        }
        return $images;
    }
}
