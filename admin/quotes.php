<?php
include_once 'includes/header.php';

$stmt = $pdo->query('SELECT q.*, d.name as device_name FROM quotes q JOIN devices d ON q.device_id = d.id ORDER BY q.created_at DESC');
$quotes = $stmt->fetchAll();
?>

<div class="section-header">
    <h2 class="section-title">Visualizza Preventivi</h2>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Dispositivo</th>
                <th>Marchio</th>
                <th>Modello</th>
                <th>Problemi</th>
                <th>Descrizione</th>
                <th>Prezzo Stimato</th>
                <th>Cliente</th>
                <th>Email</th>
                <th>Telefono</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($quotes as $quote): ?>
                <tr>
                    <td><?php echo htmlspecialchars($quote['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($quote['device_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($quote['brand_text'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($quote['model_text'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php
                            $problems = json_decode($quote['problems_json'], true);
                            if ($problems) {
                                foreach ($problems as $problem) {
                                    echo htmlspecialchars($problem, ENT_QUOTES, 'UTF-8') . '<br>';
                                }
                            }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($quote['description'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php
                            if ($quote['est_min'] && $quote['est_max']) {
                                echo '€ ' . htmlspecialchars(number_format($quote['est_min'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars(number_format($quote['est_max'], 2, ',', '.'), ENT_QUOTES, 'UTF-8');
                            } elseif ($quote['est_min']) {
                                echo '€ ' . htmlspecialchars(number_format($quote['est_min'], 2, ',', '.'), ENT_QUOTES, 'UTF-8');
                            }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><a href="mailto:<?php echo htmlspecialchars($quote['email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($quote['email'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                    <td><?php echo htmlspecialchars($quote['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once 'includes/footer.php'; ?>
