<?php
/**
 * Dynamic XML Sitemap Generator
 * Key Soft Italia
 */

header("Content-Type: application/xml; charset=utf-8");

// Carica la configurazione e le funzioni helper
require_once __DIR__ . '/config/config.php';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
// Mappa delle pagine statiche e frequenze di aggiornamento
$pages = [
    'index.php' => ['priority' => '1.0', 'changefreq' => 'daily'],
    'chi-siamo.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'servizi.php' => ['priority' => '0.9', 'changefreq' => 'weekly'],
    'prodotti.php' => ['priority' => '0.9', 'changefreq' => 'daily'],
    'assistenza.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'contatti.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'prenota-riparazione.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'preventivo.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'valuta-usato.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'volantini.php' => ['priority' => '0.8', 'changefreq' => 'daily'],
    'video.php' => ['priority' => '0.7', 'changefreq' => 'weekly'],
];

// Leggi la cartella servizi per trovare le sottopagine
$servizi_dir = __DIR__ . '/servizi';
if (is_dir($servizi_dir)) {
    $files = scandir($servizi_dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === 'index.php') {
            continue;
        }
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $pages['servizi/' . $file] = ['priority' => '0.85', 'changefreq' => 'weekly'];
        }
    }
}

foreach ($pages as $path => $meta) {
    $filepath = __DIR__ . '/' . $path;
    if (file_exists($filepath)) {
        $lastmod = date('Y-m-d', filemtime($filepath));
    } else {
        $lastmod = date('Y-m-d');
    }
    
    // Per SEO, usiamo URL puliti senza "index.php" per la homepage
    $url_path = ($path === 'index.php') ? '' : $path;
    $loc = url($url_path);
    
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
    echo "    <lastmod>{$lastmod}</lastmod>\n";
    echo "    <changefreq>{$meta['changefreq']}</changefreq>\n";
    echo "    <priority>{$meta['priority']}</priority>\n";
    echo "  </url>\n";
}
?>
</urlset>
