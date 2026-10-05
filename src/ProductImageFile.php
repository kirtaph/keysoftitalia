<?php
declare(strict_types=1);

/** Resolve only local product uploads; remote API photos must never be unlinked. */
function productImageFile(string $path): ?string
{
    $directory = realpath(__DIR__ . '/../assets/img/recond');
    if ($directory === false || !str_starts_with($path, 'assets/img/recond/')) {
        return null;
    }
    $file = realpath(__DIR__ . '/../' . $path);
    if ($file === false || !str_starts_with($file, $directory . DIRECTORY_SEPARATOR)) {
        return null;
    }
    return $file;
}
