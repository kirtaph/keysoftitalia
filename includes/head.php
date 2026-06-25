<?php

if (defined('GA_MEASUREMENT_ID') && GA_MEASUREMENT_ID): ?>
<!-- Google Analytics 4 + Consent Mode v2 -->
<script>
  // Imposta il consenso su denied di default (GDPR)
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  // Consent Mode v2: default negato finché l’utente non accetta
  gtag('consent', 'default', {
    'ad_storage': 'denied',
    'ad_user_data': 'denied',
    'ad_personalization': 'denied',
    'analytics_storage': 'denied',
    'functionality_storage': 'granted',   // opzionale per preferenze sito
    'security_storage': 'granted'
  });
</script>

<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo GA_MEASUREMENT_ID; ?>"></script>
<script>
  gtag('js', new Date());
  gtag('config', '<?php echo GA_MEASUREMENT_ID; ?>', {
    'anonymize_ip': true
  });
</script>
<?php endif;
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
$page_keywords    = $page_keywords    ?? SEO_KEYWORDS;
$meta_robots      = $meta_robots      ?? 'index, follow';
$og_image         = $og_image         ?? asset('images/og-image.jpg');

// Canonical pulito dell'URL corrente (previene duplicati per tracking query string)
if (!isset($canonical)) {
    $current_script = $_SERVER['SCRIPT_NAME'] ?? '';
    $app_path = function_exists('_detect_base_path') ? _detect_base_path() : '/';
    if ($app_path !== '/' && strpos($current_script, $app_path) === 0) {
        $rel_path = substr($current_script, strlen($app_path));
    } else {
        $rel_path = ltrim($current_script, '/');
    }
    
    // Consenti solo parametri vitali per la SEO
    $allowed_params = [];
    if ($rel_path === 'volantini.php') {
        $allowed_params = ['flyer'];
    } elseif ($rel_path === 'prodotti.php') {
        $allowed_params = ['id', 'category', 'brand'];
    }
    
    $query_string = '';
    if (!empty($allowed_params)) {
        $params = [];
        foreach ($allowed_params as $param) {
            if (!empty($_GET[$param])) {
                $params[$param] = $_GET[$param];
            }
        }
        if (!empty($params)) {
            $query_string = '?' . http_build_query($params);
        }
    }
    $canonical = url($rel_path . $query_string);
}

// Check for active logo campaigns
$activeLogoCampaign = null;
try {
    // 1. First, check if there is an active flyer
    $stmtFlyer = $pdo->query("
        SELECT COUNT(*) 
        FROM flyers 
        WHERE status = 1 
          AND start_date <= CURRENT_DATE 
          AND end_date >= CURRENT_DATE
    ");
    $hasActiveFlyer = $stmtFlyer->fetchColumn() > 0;

    if ($hasActiveFlyer) {
        // Retrieve the special flyer campaign if it is enabled (status = 1)
        $stmtSystemCamp = $pdo->prepare("
            SELECT * FROM logo_campaigns 
            WHERE status = 1 AND system_key = 'flyer_active'
            LIMIT 1
        ");
        $stmtSystemCamp->execute();
        $activeLogoCampaign = $stmtSystemCamp->fetch(PDO::FETCH_ASSOC);
    }

    // 2. If no active flyer or the flyer campaign is disabled/missing, look for matching seasonal/recurring campaigns
    if (!$activeLogoCampaign) {
        // Determine the active seasonal campaign based on month-day matching (recurring annually).
        // If start_date month-day is <= end_date month-day (same year interval, e.g., 06-01 to 08-31)
        // If start_date month-day is > end_date month-day (crosses new year, e.g., 12-01 to 01-06)
        $stmtCamp = $pdo->query("
            SELECT * FROM logo_campaigns 
            WHERE status = 1 
              AND system_key IS NULL
              AND (
                (
                  DATE_FORMAT(start_date, '%m-%d') <= DATE_FORMAT(end_date, '%m-%d') 
                  AND DATE_FORMAT(CURRENT_DATE, '%m-%d') BETWEEN DATE_FORMAT(start_date, '%m-%d') AND DATE_FORMAT(end_date, '%m-%d')
                )
                OR
                (
                  DATE_FORMAT(start_date, '%m-%d') > DATE_FORMAT(end_date, '%m-%d') 
                  AND (
                    DATE_FORMAT(CURRENT_DATE, '%m-%d') >= DATE_FORMAT(start_date, '%m-%d') 
                    OR DATE_FORMAT(CURRENT_DATE, '%m-%d') <= DATE_FORMAT(end_date, '%m-%d')
                  )
                )
              )
            ORDER BY DATE_FORMAT(end_date, '%m-%d') ASC 
            LIMIT 1
        ");
        $activeLogoCampaign = $stmtCamp->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    // ignore
}
?>
<?php if ($activeLogoCampaign && !empty($activeLogoCampaign['effect_class'])): ?>
<script>document.documentElement.classList.add('<?php echo htmlspecialchars($activeLogoCampaign['effect_class']); ?>');</script>
<?php endif; ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($page_keywords, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="author" content="Key Soft Italia">
<meta name="robots" content="<?php echo htmlspecialchars($meta_robots, ENT_QUOTES, 'UTF-8'); ?>">
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

<!-- PWA Meta & Manifest -->
<link rel="manifest" href="<?php echo url('manifest.json'); ?>">
<meta name="theme-color" content="#FF6B35">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="KeySoft">
<link rel="apple-touch-icon" href="<?php echo asset('img/pwa/icon-192.png'); ?>">

<!-- Bootstrap 5.3 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS moved to footer.php for render-blocking optimization -->

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<!-- Swiper JS (deferred, non render-blocking) -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

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
<?php if ($activeLogoCampaign && !empty($activeLogoCampaign['effect_class'])): ?>
<link rel="stylesheet" href="<?php echo asset_version('css/campaigns.css'); ?>">
<?php endif; ?>

<noscript>
  <style>
    body { opacity: 1 !important; }
    .ks-reveal { opacity: 1 !important; transform: none !important; filter: none !important; }
    .hero-secondary .hero-pattern { opacity: 0.35 !important; }
    .hero-secondary .hero-icon,
    .hero-secondary .hero-title,
    .hero-secondary .hero-subtitle,
    .hero-secondary .hero-cta,
    .hero-secondary .hero-breadcrumb {
      opacity: 1 !important;
      transform: none !important;
      filter: none !important;
    }
  </style>
</noscript>

<!-- Schema.org JSON-LD (dinamico) -->
<?php
$openingSpecs = [
    [
        '@type'    => 'OpeningHoursSpecification',
        'dayOfWeek'=> ['Monday','Tuesday','Wednesday','Thursday','Friday'],
        'opens'    => '09:00',
        'closes'   => '19:00',
    ],
    [
        '@type'    => 'OpeningHoursSpecification',
        'dayOfWeek'=> 'Saturday',
        'opens'    => '09:00',
        'closes'   => '13:00',
    ],
];

$schema = $page_schema ?? [
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
    'sameAs' => array_values(array_filter([
        SOCIAL_FACEBOOK,
        SOCIAL_INSTAGRAM,
        SOCIAL_YOUTUBE,
        SOCIAL_LINKEDIN,
        SOCIAL_TIKTOK,
    ])),
    'openingHoursSpecification' => $openingSpecs,
    'aggregateRating' => [
        '@type'       => 'AggregateRating',
        'ratingValue' => '4.8',
        'reviewCount' => '187',
        'bestRating'  => '5',
        'worstRating' => '1',
    ],
    'areaServed' => [
        '@type' => 'City',
        'name'  => 'Ginosa',
    ],
    'description' => 'Key Soft Italia: riparazioni smartphone, PC e tablet a Ginosa (TA). Vendita ricondizionati, assistenza IT, consulenza informatica. Ricambi originali, garanzia 3-12 mesi.',
];
?>
<script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register("<?php echo url('service-worker.js'); ?>", { scope: "<?php echo url('/'); ?>" })
      .then(reg => console.log('✅ SW registrato:', reg.scope))
      .catch(err => console.warn('❌ SW errore:', err));
  });
}
</script>
