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
        $rxPowers["$ponPort:$onuNo"] = $matches[3];
    }
}

// Step 5: Fetch TX power
$txPowers = [];
$lines = snmpWalkLines($community, $oltIp, $oids['tx_power']);
foreach ($lines as $line) {
    if (preg_match('/(\d+)\.(\d+) = STRING: "?(.+?)"?$/', $line, $matches)) {
        $ponPort = $matches[1];
        $onuNo   = $matches[2];
        $txPowers["$ponPort:$onuNo"] = $matches[3];
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
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="text-md text-neutral-500">Device Condition & Status</h6>
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
                    <span class="badge bg-info text-dark">Total ONUs: <?= count($onuPorts) ?></span>
                    <button onclick="location.reload()" class="btn btn-sm btn-outline-primary">🔄 Refresh</button>
                </div>
            </div>
        </div>
        <div class="card-body">
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
