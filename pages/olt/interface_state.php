<?php
set_time_limit(300);

$oltIp = "103.103.33.114:161";
$community = "BDCOM-OLT-1";

// OIDs
$oidIfDescr      = "1.3.6.1.2.1.2.2.1.2";
$oidIfOperStatus = "1.3.6.1.2.1.2.2.1.8";
$oidOnuUpTime    = "1.3.6.1.2.1.2.2.1.9";
$oidMacAddr      = "1.3.6.1.4.1.37950.1.1.5.12.1.12.1.6";
// snmpbulkwalk -v2c -c BDCOM-OLT-1 -Cr10 -t 4 -r 1 -Cc 103.103.33.114:161

function snmpFetch($oid) {
    global $oltIp, $community;
    $out = shell_exec("snmpwalk -v2c -c $community $oltIp $oid");
    return $out ? explode("\n", trim($out)) : [];
}

// Fetch data
$descrLines    = snmpFetch($oidIfDescr);
$statusLines   = snmpFetch($oidIfOperStatus);
$uptimeLines   = snmpFetch($oidOnuUpTime);
$macLines      = snmpFetch($oidMacAddr);

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
        $days = (int) floor($sec / 86400);
        $hours = (int) floor(fmod($sec, 86400) / 3600);
        $minutes = (int) floor(fmod($sec, 3600) / 60);
        $interfaceData[$m[1]]['uptime'] = sprintf("%dd %dh %dm", $days, $hours, $minutes);
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

<div class="container py-4">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary fw-bold">
                🖧 ONU Port Overview
            </h5>
            <div>
                <span class="badge bg-info text-dark me-3">Total ONUs: <?= count($onuPorts) ?></span>
                <button onclick="location.reload()" class="btn btn-sm btn-outline-primary">
                    🔄 Refresh
                </button>
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
