<?php
include_once 'includes/header.php';

$stmt = $pdo->query('SELECT q.*, d.name as device_name FROM quotes q JOIN devices d ON q.device_id = d.id ORDER BY q.created_at DESC');
$quotes = $stmt->fetchAll();
?>

<div class="section-header">
    <h2 class="section-title">Visualizza Preventivi</h2>
</div>

<div class="card mb-4">
    <div class="card-header">Filtri e Ricerca</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Cerca per cliente, email, telefono...">
            </div>
            <div class="col-md-6">
                <select id="deviceFilter" class="form-select">
                    <option value="">Filtra per dispositivo</option>
                    <?php
                    $devices_stmt = $pdo->query('SELECT DISTINCT name FROM devices ORDER BY name ASC');
                    $devices = $devices_stmt->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($devices as $device): ?>
                        <option value="<?php echo htmlspecialchars($device, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($device, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
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
                    <td><?php echo htmlspecialchars($quote['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($quote['device_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($quote['brand_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($quote['model_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
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
                    <td><?php echo htmlspecialchars($quote['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const deviceFilter = document.getElementById('deviceFilter');
    const tableBody = document.querySelector('.table tbody');
    const tableRows = tableBody.getElementsByTagName('tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const deviceTerm = deviceFilter.value.toLowerCase();

        for (let i = 0; i < tableRows.length; i++) {
            const deviceCell = tableRows[i].getElementsByTagName('td')[1];
            const clientCell = tableRows[i].getElementsByTagName('td')[7];
            const emailCell = tableRows[i].getElementsByTagName('td')[8];
            const phoneCell = tableRows[i].getElementsByTagName('td')[9];

            if (deviceCell && clientCell && emailCell && phoneCell) {
                const deviceText = deviceCell.textContent.toLowerCase();
                const clientText = clientCell.textContent.toLowerCase();
                const emailText = emailCell.textContent.toLowerCase();
                const phoneText = phoneCell.textContent.toLowerCase();

                const searchMatch = clientText.includes(searchTerm) || emailText.includes(searchTerm) || phoneText.includes(searchTerm);
                const deviceMatch = deviceTerm === '' || deviceText.includes(deviceTerm);

                if (searchMatch && deviceMatch) {
                    tableRows[i].style.display = '';
                } else {
                    tableRows[i].style.display = 'none';
                }
            }
        }
    }

    searchInput.addEventListener('keyup', filterTable);
    deviceFilter.addEventListener('change', filterTable);
});
</script>
