<?php
/**
 * Key Soft Italia - Servizio Vendita
 * Nuovo e ricondizionato: apertura, ricondizionati, cosa trovi in negozio, MyShape, contatto.
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}

require_once BASE_PATH . 'config/config.php';

// Prodotti in evidenza dal database
$featured_products = [];
try {
    $sql = "
    SELECT
      p.id, p.sku, p.title,
      p.list_price,             -- listino
      p.price_eur,              -- prezzo
      p.grade,
      b.name AS brand,
      m.name AS model,
      d.name AS device,
      COALESCE(
        MAX(CASE WHEN pi.is_cover = 1 THEN pi.path END),
        MAX(pi.path)
      ) AS image_path
    FROM products p
    JOIN models  m ON p.model_id = m.id
    JOIN brands  b ON m.brand_id = b.id
    JOIN devices d ON b.device_id = d.id
    LEFT JOIN product_images pi ON pi.product_id = p.id
    WHERE p.is_featured = 1 AND p.is_available = 1
    GROUP BY p.id
    ORDER BY p.created_at DESC
    LIMIT 3
    ";
    $stmt = $pdo->query($sql);
    $featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_products = [];
}

$esc = static fn($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
// Foto prodotto solo se il file esiste davvero (stessa regola della home).
$media = static function (?string $path): ?string {
    if (!$path) return null;
    if (filter_var($path, FILTER_VALIDATE_URL)) return in_array(parse_url($path, PHP_URL_SCHEME), ['http', 'https'], true) ? $path : null;
    $root = str_replace('\\', '/', (string)realpath(BASE_PATH)) . '/';
    foreach ([ltrim($path, '/'), 'assets/' . ltrim($path, '/')] as $candidate) {
        $full = realpath(BASE_PATH . $candidate);
        if ($full && is_file($full) && str_starts_with(str_replace('\\', '/', $full), $root) && !str_contains($candidate, 'placeholder')) return url($candidate);
    }
    return null;
};

// SEO Meta
$page_title = "Vendita Computer, Elettronica ed Elettrodomestici - Key Soft Italia";
$page_description = "Vendita computer, notebook, smartphone ricondizionati nei nostri laboratori e prodotti nuovi a Ginosa. Ampia scelta di TV, Hi-Fi, elettrodomestici, videosorveglianza e telefonia fissa.";
$page_keywords = "vendita computer ginosa, notebook ricondizionati, smartphone usati garantiti, accessori informatica, vendita elettrodomestici, smart tv ginosa, videosorveglianza";

// Breadcrumbs
$breadcrumbs = [
    ['label' => 'Servizi', 'url' => '../servizi.php'],
    ['label' => 'Vendita', 'url' => 'vendita.php']
];

$categories = [
    'Informatica' => ['PC fissi per ufficio e gaming', 'Notebook e workstation ricondizionati', 'Componenti e monitor', 'Tastiere, mouse e accessori'],
    'Telefonia' => ['Smartphone nuovi e ricondizionati', 'Tablet Android e iPad', 'Cover e ricarica', 'Telefonia fissa'],
    'TV e audio' => ['Smart TV LED e OLED', 'Hi-Fi e amplificatori', 'Cuffie e speaker wireless', 'Soundbar e decoder'],
    'Casa e persona' => ['Elettrodomestici da cucina', 'Rasoi e regolabarba', 'Asciugacapelli e piastre', 'Bellezza e benessere'],
    'Videosorveglianza' => ['Telecamere IP', 'Registratori NVR e DVR', 'Kit gestibili da smartphone', 'Accessori e alimentatori'],
    'Storage e rete' => ['Hard disk e SSD', 'Router e ripetitori Wi-Fi', 'Switch e cavi di rete', 'Chiavette USB e schede SD'],
];

$srv_picture = function (string $name, string $alt, bool $priority = false): string {
    $img = fn(string $v) => asset("img/servizi/{$name}-{$v}.webp");
    return '<picture><source media="(max-width:767px)" srcset="' . $img('mobile') . '">'
        . '<img src="' . $img('desktop') . '" alt="' . htmlspecialchars($alt) . '" width="1920" height="1080" decoding="async" '
        . ($priority ? 'fetchpriority="high"' : 'loading="lazy"') . '></picture>';
};
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <?php include '../includes/head.php'; ?>
    <link rel="stylesheet" href="<?php echo asset_version('css/pages/servizio.css'); ?>">
</head>
<body class="site-aligned service-page">

    <?php include '../includes/header.php'; ?>
<main id="main-content">

    <section class="srv-head" aria-labelledby="srv-title" data-glide-from>
      <div class="srv-still srv-head-still">
        <?= $srv_picture('vendita-apertura', 'Notebook, smartphone, tablet e auricolari su un banco espositivo di un negozio di tecnologia la sera, immagine illustrativa', true) ?>
      </div>
      <span class="srv-lens" aria-hidden="true"><i></i></span>
      <div class="container srv-head-copy">
        <h1 id="srv-title">Nuovo e ricondizionato, <span>pronto all’uso.</span></h1>
        <p>Ricondizionati certificati nei nostri laboratori, fino al 40% in meno del nuovo.</p>
        <div class="srv-actions">
          <a href="<?= url('prodotti.php') ?>" class="srv-button">Vedi i prodotti <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
          <a href="#categorie" class="srv-link">Cosa trovi in negozio <i class="ri-arrow-down-line" aria-hidden="true"></i></a>
        </div>
        <div class="hero-breadcrumb"><?= generate_breadcrumbs($breadcrumbs) ?></div>
      </div>
    </section>

    <section id="ricondizionati" class="srv-proof" data-glide-to aria-labelledby="ricondizionati-title">
      <div class="srv-statement-track">
        <div class="container srv-statement-stage">
          <h2 id="ricondizionati-title" class="visually-hidden">Ricondizionati garantiti</h2>
          <p class="srv-statement">Ogni ricondizionato supera <em>oltre 30 test</em> hardware e software, costa <em>fino al 40% in meno</em> del nuovo e ha <em>12 mesi</em> di garanzia scritta. Prima e dopo l’acquisto, <em>ci trovi in negozio.</em></p>
        </div>
      </div>
      <div class="container">
        <div class="srv-shelf" data-srv-reveal>
          <h3>In negozio adesso</h3>
          <?php if (!$featured_products): ?>
          <p class="srv-shelf-empty">Scopri i dispositivi disponibili nel <a href="<?= url('prodotti.php') ?>">catalogo</a>.</p>
          <?php else: ?>
          <ul class="srv-shelf-list" role="list">
            <?php foreach ($featured_products as $product):
              $name = trim((string)$product['title']) ?: trim(($product['brand'] ?? '') . ' ' . ($product['model'] ?? ''));
              $grade = trim((string)$product['grade']);
              if ($grade !== '') $name = trim(preg_replace('/\s*ricondizionato\s+grado\s+\S+\s*$/iu', '', $name)) ?: $name;
              $price = (float)$product['price_eur'];
              $list = (float)($product['list_price'] ?? 0);
              $photo = $media($product['image_path']);
              $kind = mb_strtolower($product['device'] . ' ' . $name);
              $icon = match (true) {
                  str_contains($kind, 'smartphone') => 'ri-smartphone-line',
                  str_contains($kind, 'tablet') => 'ri-tablet-line',
                  str_contains($kind, 'notebook') || str_contains($kind, 'computer') => 'ri-macbook-line',
                  str_contains($kind, 'console') => 'ri-gamepad-line',
                  str_contains($kind, 'tv') => 'ri-tv-2-line',
                  default => 'ri-shopping-bag-3-line',
              };
            ?>
            <li>
              <a href="<?= url('prodotti.php?sku=' . rawurlencode($product['sku'])) ?>">
                <span class="srv-shelf-photo<?= $photo ? '' : ' is-icon' ?>">
                  <?php if ($photo): ?><img src="<?= $esc($photo) ?>" alt="" width="320" height="240" loading="lazy" decoding="async"><?php else: ?><i class="<?= $icon ?>" aria-hidden="true"></i><?php endif; ?>
                  <span class="srv-shelf-tag"><?= $grade !== '' ? 'Ricondizionato · Grado ' . $esc($grade) : 'Nuovo' ?></span>
                </span>
                <span class="srv-shelf-name"><?= $esc($name) ?></span>
                <span class="srv-shelf-price">
                  <?php if ($list > $price && $price > 0): ?><s>€ <?= number_format($list, 0, ',', '.') ?></s><?php endif; ?>
                  <strong>€ <?= number_format($price, 0, ',', '.') ?></strong>
                </span>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <a class="srv-link" href="<?= url('prodotti.php') ?>">Vedi tutti i prodotti <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
        </div>
      </div>
    </section>

    <?php
    $cat_images = ['informatica', 'telefonia', 'tv-audio', 'casa', 'videosorveglianza', 'storage-rete'];
    $cat_alts = ['PC desktop, monitor e tastiera', 'Smartphone e tablet su un bancone', 'Smart TV e soundbar in un soggiorno', 'Elettrodomestici da cucina e per la cura della persona', 'Telecamere di videosorveglianza su un muro', 'Router, dischi SSD e schede di memoria'];
    $cat_img = fn(int $i, string $v) => asset('img/servizi/vendita-cat-' . $cat_images[$i] . '-' . $v . '.webp');
    ?>
    <section id="categorie" class="srv-reel" aria-labelledby="categorie-title" style="--srv-count:<?= count($categories) ?>">
      <div class="srv-reel-stage">
        <div class="srv-reel-frames" aria-hidden="true">
          <?php foreach (array_keys($categories) as $i => $group): ?>
          <picture><source media="(max-width:767px)" srcset="<?= $cat_img($i, 'mobile') ?>"><img src="<?= $cat_img($i, 'desktop') ?>" alt="" width="1500" height="1200" loading="lazy" decoding="async"></picture>
          <?php endforeach; ?>
        </div>
        <span class="srv-lens" aria-hidden="true"><i></i></span>
        <div class="container srv-reel-ui">
          <h2 id="categorie-title">Cosa trovi <span>in negozio.</span></h2>
          <ol class="srv-reel-list" role="list">
            <?php $i = 0; foreach ($categories as $group => $items): ?>
            <li class="srv-reel-item" data-i="<?= $i ?>">
              <span class="srv-reel-frame"><img src="<?= $cat_img($i, 'desktop') ?>" alt="<?= $esc($cat_alts[$i]) ?>, immagine illustrativa" width="1500" height="1200" loading="lazy" decoding="async"></span>
              <h3><?= $group ?></h3>
              <div class="srv-reel-more"><p><?= implode(' · ', $items) ?></p></div>
            </li>
            <?php $i++; endforeach; ?>
          </ol>
          <p class="srv-reel-count" aria-hidden="true"><b>01</b> / <?= str_pad((string)count($categories), 2, '0', STR_PAD_LEFT) ?><i class="srv-reel-meter"></i></p>
        </div>
      </div>
    </section>

    <section id="myshape" class="srv-process" aria-labelledby="myshape-title" style="--srv-count:3">
      <div class="srv-process-stage">
        <div class="srv-still srv-process-still">
          <?= $srv_picture('vendita-myshape', 'Un tecnico stacca dal foglio la pellicola protettiva appena tagliata sulla sagoma di uno smartphone, dietro il plotter, immagine illustrativa') ?>
        </div>
        <span class="srv-lens" aria-hidden="true"><i></i></span>
        <div class="container srv-process-copy">
          <div>
            <h2 id="myshape-title">Pellicole MyShape, <span>tagliate su misura.</span></h2>
            <p class="srv-process-lede">Per smartphone, tablet, smartwatch e console portatili.</p>
            <ol class="srv-process-steps" role="list">
              <li><b>01</b><p>Scegliamo il tuo modello tra oltre 30.000.</p></li>
              <li><b>02</b><p>Il plotter taglia la pellicola in TPU al millimetro, in pochi secondi.</p></li>
              <li><b>03</b><p>La applichiamo noi in negozio, senza bolle.</p></li>
            </ol>
            <span class="srv-process-meter" aria-hidden="true"><i></i><i></i><i></i></span>
            <p class="srv-tags">Anti-shock · Autorigenerante · Lucida o opaca</p>
          </div>
        </div>
      </div>
    </section>

    <section class="srv-notes" aria-label="Acquisto e consegna">
      <div class="container srv-notes-grid">
        <a href="<?= url('valuta-usato.php') ?>"><strong>Permuta dell’usato</strong> Lo valutiamo in negozio e lo scaliamo dal prezzo. <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
        <p><strong>Ritiro o spedizione</strong> In negozio o con consegna nelle province di Taranto e Matera.</p>
        <a href="<?= url('preventivo.php') ?>"><strong>Configurazioni su richiesta</strong> Preventivo gratuito per qualsiasi configurazione. <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
      </div>
    </section>

    <section id="section-cta" class="srv-contact" aria-labelledby="srv-contact-title">
      <div class="container">
        <h2 id="srv-contact-title">Cerchi un prodotto<br><span>specifico?</span></h2>
        <div class="srv-contact-actions">
          <a href="<?= url('preventivo.php') ?>" class="srv-button">Richiedi un preventivo <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
          <a href="<?= whatsapp_link('Salve, vorrei informazioni sulla disponibilità di un prodotto') ?>" class="srv-link" target="_blank" rel="noopener noreferrer">Scrivici su WhatsApp <i class="ri-whatsapp-line" aria-hidden="true"></i></a>
          <a href="<?= url('contatti.php') ?>" class="srv-link">Contatti e orari <i class="ri-arrow-right-up-line" aria-hidden="true"></i></a>
        </div>
      </div>
    </section>

    <script>
        window.KS_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            whatsappNumber: '<?php echo WHATSAPP_NUMBER; ?>'
        };
    </script>
</main>
<?php include '../includes/footer.php'; ?>
<script src="<?= asset_version('js/pages/servizio.js') ?>" defer></script>
<script src="<?= asset_version('js/opening-glide.js') ?>" defer></script>
</body>
</html>
