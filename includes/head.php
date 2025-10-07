<?php
// Assicurati che config sia già incluso prima di questo file.
// Inizializza CSRF (usa l'helper)
if (function_exists('generate_csrf_token')) {
    generate_csrf_token();
} else {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

$page_title       = $page_title       ?? SITE_NAME;
$page_description = $page_description ?? SITE_DESCRIPTION;
$og_image         = $og_image         ?? asset('images/og-image.jpg');
// Canonical dell'URL corrente
$canonical        = url(ltrim($_SERVER['REQUEST_URI'] ?? '', '/'));
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars(SEO_KEYWORDS, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="author" content="Key Soft Italia">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:locale" content="it_IT">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="<?php echo htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="twitter:title" content="<?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="twitter:description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="twitter:image" content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">

<!-- Favicons -->
<link rel="icon" type="image/x-icon" href="<?php echo asset('favicon.ico'); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('images/apple-touch-icon.png'); ?>">

<!-- Bootstrap 5.3 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Remix Icons -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- CSS -->
 <?php require_once __DIR__ . '/../helpers.versioning.php'; ?>
<link rel="stylesheet" href="<?php echo asset_version('css/variables.css'); ?>">
<link rel="stylesheet" href="<?php echo asset_version('css/main.css'); ?>">
<link rel="stylesheet" href="<?php echo asset_version('css/components.css'); ?>">

<!-- AOS CSS -->
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<!-- Schema.org JSON-LD (dinamico) -->
<?php
$openingSpecs = [];
if (is_array(OPENING_HOURS)) {
    // Lunedì–Venerdì
    $openingSpecs[] = [
        '@type'    => 'OpeningHoursSpecification',
        'dayOfWeek'=> ['Monday','Tuesday','Wednesday','Thursday','Friday'],
        'opens'    => OPENING_HOURS['monday']['open'] ?? '09:00',
        'closes'   => OPENING_HOURS['monday']['close'] ?? '19:00',
    ];
    // Sabato (se presente)
    if (isset(OPENING_HOURS['saturday']['open'], OPENING_HOURS['saturday']['close'])) {
        $openingSpecs[] = [
            '@type'    => 'OpeningHoursSpecification',
            'dayOfWeek'=> 'Saturday',
            'opens'    => OPENING_HOURS['saturday']['open'],
            'closes'   => OPENING_HOURS['saturday']['close'],
        ];
    }
}

$schema = [
    '@context'  => 'https://schema.org',
    '@type'     => 'ComputerStore',
    'name'      => COMPANY_NAME,
    'image'     => asset('images/logo.png'),
    'url'       => url(),
    'telephone' => PHONE_PRIMARY,
    'priceRange'=> '€€',
    'address'   => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => COMPANY_ADDRESS,
        'addressLocality' => COMPANY_CITY,
        'addressRegion'   => COMPANY_PROVINCE,
        'postalCode'      => COMPANY_ZIP,
        'addressCountry'  => 'IT'
    ],
    // Se vuoi inserire le coordinate, aggiungi qui 'geo' => ['@type'=>'GeoCoordinates','latitude'=>..., 'longitude'=>...]
    'openingHoursSpecification' => $openingSpecs,
    'sameAs' => array_values(array_filter([
        SOCIAL_FACEBOOK,
        SOCIAL_INSTAGRAM,
        SOCIAL_YOUTUBE,
        SOCIAL_LINKEDIN,
        SOCIAL_TIKTOK,
    ])),
];
?>
<script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    // Costruisci correttamente l’URL PHP
    navigator.serviceWorker.register("<?php echo url('service-worker.dev.js'); ?>", { scope: "<?php echo url('/'); ?>" })
      .then(reg => console.log('✅ SW registrato:', reg.scope))
      .catch(err => console.warn('❌ SW errore:', err));

    console.log('SW path:', "<?php echo url('service-worker.dev.js'); ?>");
  });
}
</script>
