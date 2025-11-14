<?php
include_once 'includes/header.php';
?>

<div class="section-header">
    <h2 class="section-title">Dashboard</h2>
    <p class="section-subtitle">Benvenuto, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
</div>

<!-- Macro-categoria Preventivi -->
<div class="mb-5">
    <h3 class="mb-4">Gestione Preventivi</h3>
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-mobile-alt fa-3x mb-3"></i>
                    <h5 class="card-title">Dispositivi</h5>
                    <a href="devices.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-copyright fa-3x mb-3"></i>
                    <h5 class="card-title">Marchi</h5>
                    <a href="brands.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-tablet-alt fa-3x mb-3"></i>
                    <h5 class="card-title">Modelli</h5>
                    <a href="models.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-wrench fa-3x mb-3"></i>
                    <h5 class="card-title">Problemi</h5>
                    <a href="issues.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-hand-holding-usd fa-3x mb-3"></i>
                    <h5 class="card-title">Regole di Prezzo</h5>
                    <a href="price_rules.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                    <h5 class="card-title">Preventivi</h5>
                    <a href="quotes.php" class="btn btn-primary">Visualizza</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Macro-categoria Ricondizionati -->
<div class="mb-5">
    <h3 class="mb-4">Gestione Offerte</h3>
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-mobile-android-alt fa-3x mb-3"></i>
                    <h5 class="card-title">Prodotti</h5>
                    <?php
                        $stmt = $pdo->query('SELECT COUNT(*) FROM products WHERE is_available = 1');
                        $count = $stmt->fetchColumn();
                    ?>
                    <p class="card-text">Ci sono <?php echo $count; ?> prodotti disponibili.</p>
                    <a href="products.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-solid fa-tag fa-3x mb-3"></i>
                    <h5 class="card-title">Volantini</h5>
                    <?php
                        $stmt = $pdo->query('SELECT COUNT(*) FROM flyers WHERE status = 1');
                        $count = $stmt->fetchColumn();
                    ?>
                    <p class="card-text">Ci sono <?php echo $count; ?> volantini disponibili.</p>
                    <a href="flyers.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Macro-categoria Orari di Apertura -->
<div class="mb-5">
    <h3 class="mb-4">Gestione Orari di Apertura</h3>
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                    <h5 class="card-title">Orario Settimanale</h5>
                    <a href="weekly_hours.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h5 class="card-title">Festività</h5>
                    <a href="holidays.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-3x mb-3"></i>
                    <h5 class="card-title">Eccezioni</h5>
                    <a href="exceptions.php" class="btn btn-primary">Gestisci</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>
