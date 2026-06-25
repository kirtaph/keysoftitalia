<?php
function asset_version($path) {
    $base = __DIR__ . '/assets/' . ltrim($path, '/');
    if (preg_match('/\.css$/', $path)) {
        $minPath = preg_replace('/\.css$/', '.min.css', $path);
        $minFull = __DIR__ . '/assets/' . ltrim($minPath, '/');
        if (file_exists($minFull)) {
            return url('assets/' . ltrim($minPath, '/') . '?v=' . filemtime($minFull));
        }
    }
    if (file_exists($base)) {
        return url('assets/' . ltrim($path, '/') . '?v=' . filemtime($base));
    }
    return url('assets/' . ltrim($path, '/') . '?v=' . time());
}
