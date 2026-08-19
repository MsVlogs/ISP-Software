<?php
set_time_limit(30);
date_default_timezone_set('Asia/Dhaka');

require_once __DIR__ . '/../../services/OltService.php';

$oltDevices = oltGetActiveDevices();
$currentOlt = oltGetSelectedDevice();
$pdo = oltDb();
$onuPorts = [];

if ($pdo && $currentOlt) {
    $stmt = $pdo->prepare("
        SELECT olt_ip, interface_name, serial, distance, tx_power, rx_power,
               download_bytes, upload_bytes, last_updated
        FROM onu_status
        WHERE olt_ip = :olt_ip
        ORDER BY
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(interface_name,'/',1),'EPON',-1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(interface_name,':',1),'/',-1) AS UNSIGNED),
            CAST(SUBSTRING_INDEX(interface_name,':',-1) AS UNSIGNED)
    ");
    $stmt->execute(['olt_ip' => $currentOlt['ip_address']]);
    $onuPorts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function formatBytes($bytes) {
    if ($bytes === null || $bytes <= 0) return '-';
    return round($bytes / 1073741824, 2) . ' GB';
}

function powerClass($power) {
    if ($power === null || $power === '') return 'text-muted';
    $power = (float)$power;
    if ($power >= -20) return 'text-success fw-bold';
    if ($power >= -25) return 'text-info fw-bold';
    if ($power >= -30) return 'text-warning fw-bold';
    return 'text-danger fw-bold';
}
?>

<!-- HTML Output -->
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <select id="oltSelector" class="form-select form-select-sm" style="min-width:280px" onchange="changeOlt(this.value)">
                <?php if (!$oltDevices): ?>
                    <option value="">No active OLT configured</option>
                <?php else: ?>
                    <?php foreach ($oltDevices as $olt): ?>
                        <option value="<?= (int)$olt['id'] ?>" <?= $currentOlt && (int)$currentOlt['id'] === (int)$olt['id'] ? 'selected' : '' ?>><?= htmlspecialchars($olt['device_name'] . ' (' . $olt['ip_address'] . ')') ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <span class="badge bg-info text-dark">Total ONUs: <?= count($onuPorts) ?></span>
        </div>
        <button onclick="location.reload()" class="btn btn-sm btn-outline-primary">🔄 Refresh</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary text-center">
                <tr>
                    <th scope="col">SL</th>
                    <th scope="col">Interface</th>
                    <th scope="col">Download (GB)</th>
                    <th scope="col">Upload (GB)</th>
                    <th scope="col">RX Power</th>
                    <th scope="col">TX Power</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php $sl = 1; ?>
                <?php foreach ($onuPorts as $onu): ?>
                    <tr>
                        <td><?= $sl++ ?></td>
                        <td><?= htmlspecialchars($onu['interface_name'] ?? '-') ?></td>
                        <td>
                            <?= isset($onu['download_bytes']) 
                                ? round($onu['download_bytes'] / 1073741824, 2) 
                                : '-' ?>
                        </td>
                        <td>
                            <?= isset($onu['upload_bytes']) 
                                ? round($onu['upload_bytes'] / 1073741824, 2) 
                                : '-' ?>
                        </td>
                        <td class="<?= powerClass($onu['rx_power']) ?>"><?= ($onu['rx_power'] !== null && $onu['rx_power'] !== '') ? htmlspecialchars($onu['rx_power']) . ' dBm' : '-' ?></td>
                        <td class="<?= powerClass($onu['tx_power']) ?>"><?= ($onu['tx_power'] !== null && $onu['tx_power'] !== '') ? htmlspecialchars($onu['tx_power']) . ' dBm' : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function changeOlt(oltId) {
    if (!oltId) return;
    fetch('pages/olt/set_selected_olt.php?olt_id=' + encodeURIComponent(oltId))
        .then(r => r.json())
        .then(data => { if (data.success) location.reload(); else alert(data.message || 'Unable to select OLT'); })
        .catch(() => alert('Unable to select OLT'));
}
</script>
