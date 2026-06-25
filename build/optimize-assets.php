<?php
/**
 * Key Soft Italia — Asset Optimization Script
 * Run: php build/optimize-assets.php
 * 
 * Minifies CSS/JS files and generates WebP copies of images.
 */

$root = __DIR__ . '/..';
$publicCss = $root . '/assets/css';
$publicJs  = $root . '/assets/js';
$publicImg = $root . '/assets/img';

$errors = [];

// --- 1. Minify CSS ---
echo "=== Minifying CSS ===\n";
$cssFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($publicCss, RecursiveDirectoryIterator::SKIP_DOTS));
foreach ($cssFiles as $file) {
    if ($file->getExtension() !== 'css') continue;
    $path = $file->getRealPath();
    $content = file_get_contents($path);
    
    // Basic minification
    $minified = preg_replace([
        '/\/\*[\s\S]*?\*\//',    // Remove comments
        '/\s*([{}:;,])\s*/',      // Remove whitespace around tokens
        '/\s{2,}/',                // Collapse whitespace
        '/;\}/',                   // Remove trailing semicolons
    ], ['$1', '$1', ' ', '}'], $content);
    
    $minPath = preg_replace('/\.css$/', '.min.css', $path);
    if (file_put_contents($minPath, $minified)) {
        $saved = strlen($content) - strlen($minified);
        echo "  ✓ " . basename($path) . " (saved {$saved}B)\n";
    } else {
        $errors[] = "Failed to write: $minPath";
    }
}

// --- 2. JS Minification (SKIPPED — use a proper tool like terser) ---
echo "\n=== JS Minification (SKIPPED) ===\n";
echo "  ⚠ Use a proper JS minifier (terser, esbuild) instead:\n";
echo "     npx terser assets/js/main.js -o assets/js/main.min.js\n";
echo "     npx terser assets/js/pages/products.js -o assets/js/pages/products.min.js\n";
echo "     npx terser assets/js/pages/toast.js -o assets/js/pages/toast.min.js\n";

// --- 3. Generate WebP copies ---
echo "\n=== Generating WebP images ===\n";
$webpSupported = extension_loaded('gd') && function_exists('imagewebp');
if (!$webpSupported) {
    echo "  ⚠ GD/WebP extension not available. Skipping image conversion.\n";
} else {
    $imgFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($publicImg, RecursiveDirectoryIterator::SKIP_DOTS));
    $converted = 0;
    foreach ($imgFiles as $file) {
        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) continue;
        
        $path = $file->getRealPath();
        $webpPath = preg_replace('/\.(png|jpe?g)$/', '.webp', $path);
        if (file_exists($webpPath)) continue; // skip if already exists
        
        $img = match ($ext) {
            'png'  => @imagecreatefrompng($path),
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            default => null,
        };
        if (!$img) continue;
        
        // Preserve alpha for PNG
        if ($ext === 'png') {
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
        }
        
        if (imagewebp($img, $webpPath, 80)) {
            $orig = filesize($path);
            $webp = filesize($webpPath);
            $savedPct = $orig > 0 ? round((1 - $webp / $orig) * 100) : 0;
            echo "  ✓ " . basename($path) . " → .webp (saved {$savedPct}%)\n";
            $converted++;
        }
        imagedestroy($img);
    }
    if ($converted === 0) echo "  No new images to convert.\n";
}

// --- Summary ---
echo "\n=== Done ===\n";
if ($errors) {
    echo "Errors:\n";
    foreach ($errors as $e) echo "  ✗ $e\n";
    exit(1);
}
exit(0);
