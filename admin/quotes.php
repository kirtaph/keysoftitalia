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
                    <td><?php echo htmlspecialchars($quote['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($quote['device_name']); ?></td>
                    <td><?php echo htmlspecialchars($quote['brand_text']); ?></td>
                    <td><?php echo htmlspecialchars($quote['model_text']); ?></td>
                    <td>
                        <?php
                            $problems = json_decode($quote['problems_json'], true);
                            if ($problems) {
                                foreach ($problems as $problem) {
                                    echo htmlspecialchars($problem) . '<br>';
                                }
                            }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($quote['description']); ?></td>
                    <td>
                        <?php
                            if ($quote['est_min'] && $quote['est_max']) {
                                echo htmlspecialchars($quote['est_min']) . ' - ' . htmlspecialchars($quote['est_max']);
                            } elseif ($quote['est_min']) {
                                echo htmlspecialchars($quote['est_min']);
                            }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']); ?></td>
                    <td><a href="mailto:<?php echo htmlspecialchars($quote['email']); ?>"><?php echo htmlspecialchars($quote['email']); ?></a></td>
                    <td><?php echo htmlspecialchars($quote['phone']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once 'includes/footer.php'; ?>
