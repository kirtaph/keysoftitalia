<?php
// helpers/versioning.php — add ?v=timestamp to assets during development
function asset_version($path) {
    $full = __DIR__ . '/../assets/' . ltrim($path, '/');
    if (file_exists($full)) {
        return 'assets/' . ltrim($path, '/') . '?v=' . filemtime($full);
    }
    return 'assets/' . ltrim($path, '/') . '?v=' . time();
}
/* Usage in head.php:
<link rel="stylesheet" href="<?= asset_version('css/main.css') ?>">
<script src="<?= asset_version('js/app.js') ?>"></script>
*/
