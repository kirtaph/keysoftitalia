<?php
// Ensure we have a CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title><?php echo $page_title ?? SITE_NAME; ?></title>
<meta name="description" content="<?php echo $page_description ?? SITE_DESCRIPTION; ?>">
<meta name="keywords" content="riparazione computer, assistenza informatica, riparazione smartphone, vendita ricondizionati, assistenza pc, riparazione tablet, Key Soft Italia, Torino">
<meta name="author" content="Key Soft Italia">
<meta name="robots" content="index, follow">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo BASE_URL; ?>">
<meta property="og:title" content="<?php echo $page_title ?? SITE_NAME; ?>">
<meta property="og:description" content="<?php echo $page_description ?? SITE_DESCRIPTION; ?>">
<meta property="og:image" content="<?php echo asset('images/og-image.jpg'); ?>">
<meta property="og:locale" content="it_IT">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo BASE_URL; ?>">
<meta property="twitter:title" content="<?php echo $page_title ?? SITE_NAME; ?>">
<meta property="twitter:description" content="<?php echo $page_description ?? SITE_DESCRIPTION; ?>">
<meta property="twitter:image" content="<?php echo asset('images/og-image.jpg'); ?>">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo asset('favicon.ico'); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('images/apple-touch-icon.png'); ?>">

<!-- Bootstrap 5.3 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Remix Icons -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<link rel="stylesheet" href="<?php echo asset('css/variables.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('css/main.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('css/components.css'); ?>

<!-- Schema.org JSON-LD -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ComputerStore",
    "name": "Key Soft Italia",
    "image": "<?php echo asset('images/logo.png'); ?>",
    "url": "<?php echo BASE_URL; ?>",
    "telephone": "<?php echo PHONE_PRIMARY; ?>",
    "priceRange": "€€",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Via Example 123",
        "addressLocality": "Torino",
        "addressRegion": "TO",
        "postalCode": "10100",
        "addressCountry": "IT"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": 45.0705,
        "longitude": 7.6868
    },
    "openingHoursSpecification": [
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "09:00",
            "closes": "19:00"
        },
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Saturday",
            "opens": "09:00",
            "closes": "13:00"
        }
    ],
    "sameAs": [
        "https://www.facebook.com/keysoftitalia",
        "https://www.instagram.com/keysoftitalia"
    ]
}
</script>