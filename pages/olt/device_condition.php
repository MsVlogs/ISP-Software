<?php
set_time_limit(300);

// OLT credentials
$oltIp = "103.103.33.114:161";
$community = "BDCOM-OLT-1";
// snmpbulkwalk -v2c -c bsd -Cr10 -t 4 -r 1 -Cc 103.103.33.114:161

// OIDs for interface info, RX & TX power
$oids = [
    'name'           => "1.3.6.1.2.1.2.2.1.2",        // Interface name
    'download_bytes' => "1.3.6.1.2.1.31.1.1.1.10",   // ifHCInOctets
    'upload_bytes'   => "1.3.6.1.2.1.31.1.1.1.6",    // ifHCOutOctets
    'rx_power'       => "1.3.6.1.4.1.37950.1.1.5.12.2.1.8.1.7", // RX power
    'tx_power'       => "1.3.6.1.4.1.37950.1.1.5.12.2.1.8.1.6", // TX power
];

// Function to run snmpbulkwalk
function snmpWalkLines($community, $oltIp, $oid) {
    $cmd = "snmpbulkwalk -v2c -c $community $oltIp $oid";
    $output = shell_exec($cmd);
    return explode("\n", trim($output));
}

// Step 1: Fetch interface names
$interfaces = [];
$lines = snmpWalkLines($community, $oltIp, $oids['name']);
foreach ($lines as $line) {
    if (preg_match('/\.(\d+) = STRING: "?(.+?)"?$/', $line, $matches)) {
        $interfaces[$matches[1]] = $matches[2]; // key = ifIndex
    }
}

// Step 2: Fetch download bytes
$downloads = [];
$lines = snmpWalkLines($community, $oltIp, $oids['download_bytes']);
foreach ($lines as $line) {
    if (preg_match('/\.(\d+) = Counter64: (\d+)/', $line, $matches)) {
        $downloads[$matches[1]] = (int)$matches[2];
    }
}

// Step 3: Fetch upload bytes
$uploads = [];
$lines = snmpWalkLines($community, $oltIp, $oids['upload_bytes']);
foreach ($lines as $line) {
    if (preg_match('/\.(\d+) = Counter64: (\d+)/', $line, $matches)) {
        $uploads[$matches[1]] = (int)$matches[2];
    }
}

// Step 4: Fetch RX power
$rxPowers = [];
$lines = snmpWalkLines($community, $oltIp, $oids['rx_power']);
foreach ($lines as $line) {
    if (preg_match('/(\d+)\.(\d+) = STRING: "?(.+?)"?$/', $line, $matches)) {
        $ponPort = $matches[1];
        $onuNo   = $matches[2];
        $rxPowers["$ponPort:$onuNo"] = $matches[3]; // e.g., "0.00 mW (-27.96 dBm)"
    }
}

// Step 5: Fetch TX power
$txPowers = [];
$lines = snmpWalkLines($community, $oltIp, $oids['tx_power']);
foreach ($lines as $line) {
    if (preg_match('/(\d+)\.(\d+) = STRING: "?(.+?)"?$/', $line, $matches)) {
        $ponPort = $matches[1];
        $onuNo   = $matches[2];
        $txPowers["$ponPort:$onuNo"] = $matches[3]; // e.g., "0.00 mW (-3.00 dBm)"
    }
}

// Step 6: Combine data by matching EPONx/y:z → PON port / ONU
$onuPorts = [];
foreach ($interfaces as $ifIndex => $name) {
    if (preg_match('/^EPON\d+\/(\d+):(\d+)$/', $name, $m)) {
        $ponPort = $m[1];
        $onuNo   = $m[2];
        $key     = "$ponPort:$onuNo";

        $onuPorts[] = [
            'name'           => $name,
            'download_bytes' => $downloads[$ifIndex] ?? null,
            'upload_bytes'   => $uploads[$ifIndex] ?? null,
            'rx_power'       => $rxPowers[$key] ?? null,
            'tx_power'       => $txPowers[$key] ?? null,
        ];
    }
}

// Step 7: Sort EPON interfaces logically
uasort($onuPorts, function ($a, $b) {
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $a['name'], $m1);
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $b['name'], $m2);
    return [$m1[1], $m1[2], $m1[3]] <=> [$m2[1], $m2[2], $m2[3]];
});
?>

<!-- HTML Output -->
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="badge bg-info text-dark me-3">Total ONUs: <?= count($onuPorts) ?></span>
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
                        <td><?= htmlspecialchars($onu['name'] ?? '-') ?></td>
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
                        <td><?= htmlspecialchars($onu['rx_power'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($onu['tx_power'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
