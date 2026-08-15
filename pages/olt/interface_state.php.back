<?php
set_time_limit(300);

// Get selected OLT from session or default to first active OLT
$selectedOltId = $_SESSION['selected_olt_id'] ?? null;
$oltDevices = $obj->view_all_by_cond('tbl_olt_devices', 'status=1') ?? [];

if (!$selectedOltId && !empty($oltDevices)) {
    $selectedOltId = $oltDevices[0]['olt_id'];
    $_SESSION['selected_olt_id'] = $selectedOltId;
}

// Get current OLT details
$currentOlt = $obj->view_by_id('tbl_olt_devices', $selectedOltId);

if (!$currentOlt) {
    echo '<div class="alert alert-danger">No OLT device selected or configured. Please configure OLT devices first.</div>';
    return;
}

// OLT credentials from database
$oltIp = $currentOlt['olt_ip'] . ':' . $currentOlt['olt_port'];
$community = $currentOlt['community'];

// OIDs
$oidIfDescr      = "1.3.6.1.2.1.2.2.1.2";
$oidIfOperStatus = "1.3.6.1.2.1.2.2.1.8";
$oidOnuUpTime    = "1.3.6.1.2.1.2.2.1.9";
$oidMacAddr      = "1.3.6.1.4.1.37950.1.1.5.12.1.12.1.6";

function snmpFetch($oid, $oltIp, $community) {
    $out = shell_exec("snmpwalk -v2c -c $community $oltIp $oid");
    return $out ? explode("\n", trim($out)) : [];
}

// Fetch data
$descrLines    = snmpFetch($oidIfDescr, $oltIp, $community);
$statusLines   = snmpFetch($oidIfOperStatus, $oltIp, $community);
$uptimeLines   = snmpFetch($oidOnuUpTime, $oltIp, $community);
$macLines      = snmpFetch($oidMacAddr, $oltIp, $community);

$interfaceData = [];

// --- Interface Name ---
foreach ($descrLines as $line) {
    if (preg_match('/\.(\d+) = STRING: (.+)/', $line, $m)) {
        $interfaceData[$m[1]] = ['name' => trim($m[2])];
    }
}

// --- Status ---
$statusMap = [1 => 'Connected', 2 => 'Down', 3 => 'Testing', 4 => 'Unknown', 5 => 'Dormant', 6 => 'Not Present', 7 => 'Lower Layer Down'];
foreach ($statusLines as $line) {
    if (preg_match('/\.(\d+) = INTEGER: \w+\((\d+)\)/', $line, $m)) {
        $interfaceData[$m[1]]['status'] = $statusMap[$m[2]] ?? 'Unknown';
    }
}

// --- Uptime ---
foreach ($uptimeLines as $line) {
    if (preg_match('/\.(\d+) = Timeticks: \((\d+)\)/', $line, $m)) {
        $sec = (int)$m[2] / 100;
        $interfaceData[$m[1]]['uptime'] = sprintf("%dd %dh %dm", $sec/86400, ($sec%86400)/3600, ($sec%3600)/60);
    }
}

// --- Filter only ONUs ---
$onuPorts = array_filter($interfaceData, fn($d) => isset($d['name']) && preg_match('/^EPON\d+\/\d+:\d+$/', $d['name']));

// --- Sort ONUs by EPON port ---
uasort($onuPorts, function($a, $b) {
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $a['name'], $x);
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $b['name'], $y);
    return [$x[1],$x[2],$x[3]] <=> [$y[1],$y[2],$y[3]];
});

$onuPortsKeys = array_keys($onuPorts);

// --- MAC Addresses order-wise mapping ---
$macList = [];
foreach ($macLines as $line) {
    if (preg_match('/= STRING: "?(.+?)"?$/', $line, $m)) {
        $macList[] = $m[1];
    }
}

// Assign MACs in sorted order
foreach ($onuPortsKeys as $i => $key) {
    $onuPorts[$key]['mac_addr'] = $macList[$i] ?? '-';
}
?>

<div class="col-md-12">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary fw-bold">
                    🖧 ONU Port Overview
                </h5>
                <div class="d-flex gap-2 align-items-center">
                    <div class="form-group" style="width: 280px; margin: 0;">
                        <select id="oltSelector" class="form-control form-select" onchange="changeOlt(this.value)">
                            <option value="">-- Select OLT Device --</option>
                            <?php foreach($oltDevices as $olt): ?>
                                <option value="<?php echo $olt['olt_id']; ?>" <?php echo $olt['olt_id'] == $selectedOltId ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($olt['olt_name'] . ' (' . $olt['olt_ip'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <span class="badge bg-info text-dark me-3">Total ONUs: <?= count($onuPorts) ?></span>
                    <button onclick="location.reload()" class="btn btn-sm btn-outline-primary">
                        🔄 Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Interface</th>
                            <th scope="col">Status</th>
                            <th scope="col">MAC Address</th>
                            <th scope="col">Uptime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($onuPorts)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-danger">⚠ No ONU data found</td>
                            </tr>
                        <?php else: ?>
                            <?php $sl = 1; foreach ($onuPorts as $onu): ?>
                                <tr class="text-center">
                                    <td><?= $sl++ ?></td>
                                    <td><code><?= htmlspecialchars($onu['name']) ?></code></td>
                                    <td>
                                        <?php
                                            $status = $onu['status'] ?? 'Unknown';
                                            $badgeClass = match ($status) {
                                                'Connected' => 'bg-success',
                                                'Down' => 'bg-danger',
                                                'Testing' => 'bg-warning text-dark',
                                                'Dormant', 'Lower Layer Down' => 'bg-secondary',
                                                default => 'bg-dark',
                                            };
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                    </td>
                                    <td><code><?= $onu['mac_addr'] ?? '-' ?></code></td>
                                    <td><?= $onu['uptime'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $obj->start_script(); ?>
<script>
function changeOlt(oltId) {
    if (oltId) {
        fetch('pages/olt/set_selected_olt.php?olt_id=' + oltId)
            .then(() => location.reload())
            .catch(err => console.error('Error:', err));
    }
}
</script>
<?php $obj->end_script(); ?>
