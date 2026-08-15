<?php
set_time_limit(0);

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

// OIDs for SNMP
$oids = [
    'descr'       => "1.3.6.1.2.1.2.2.1.2",
    'oper_status' => "1.3.6.1.2.1.2.2.1.8"
];

// SNMP fetch function
function snmpBulkFetch($community, $oltIp, $oids){
    $data = [];
    foreach($oids as $key=>$oid){
        $lines = explode("\n", trim(shell_exec("snmpbulkwalk -v2c -c $community -t 2 -r 2 $oltIp $oid 2>&1")));
        foreach($lines as $line){
            if(preg_match('/\.(\d+)\s*=\s*(?:STRING|INTEGER):\s*(.*)$/i', trim($line), $m)){
                $index = $m[1];
                $value = trim($m[2], "\" ");
                $value = preg_replace('/^INTEGER:\s*/i', '', $value);
                $value = preg_replace('/^STRING:\s*/i', '', $value);
                $data[$index][$key] = $value;
            }
        }
    }
    return $data;
}

// Fetch all SNMP data
$onuData = snmpBulkFetch($community, $oltIp, $oids);

// Map SNMP oper_status to readable
$statusMap = [
    1 => 'Connected',
    2 => 'Down',
    3 => 'Testing',
    4 => 'Unknown',
    5 => 'Dormant',
    6 => 'Not Present',
    7 => 'Lower Layer Down'
];

foreach($onuData as $idx => $onu){
    $rawStatus = (int)preg_replace('/\D/', '', $onu['oper_status'] ?? '0');
    $onuData[$idx]['status'] = $statusMap[$rawStatus] ?? 'Unknown';
}

// Build EPON tree dynamically
$eponTree = [];
foreach($onuData as $onu){
    $name = $onu['descr'] ?? '';
    $status = $onu['status'] ?? 'Unknown';

    if(preg_match('/^EPON0\/(\d+):(\d+)$/', $name, $m)){
        $port = "EPON0/".$m[1];
        $eponTree[$port]['onus'][] = ['name'=>$name, 'status'=>$status];
    } elseif(preg_match('/^EPON0\/(\d+)$/', $name)){
        $eponTree[$name]['onus'] = $eponTree[$name]['onus'] ?? [];
    }
}

// Prepare Highcharts links & node colors
$links = [];
$nodesColor = [];
foreach($eponTree as $port=>$data){
    $links[] = ['OLT', $port];
    $nodesColor[$port] = '#007bff';

    foreach($data['onus'] ?? [] as $onu){
        $links[] = [$port, $onu['name']];
        $color = match(strtolower($onu['status'])){
            'connected' => 'green',
            'down' => 'red',
            default => 'orange'
        };
        $nodesColor[$onu['name']] = $color;
    }
}
$nodesColor['OLT'] = '#000000';
?>

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="text-md text-neutral-500">OLT Network Diagram</h6>
                <div class="form-group" style="width: 250px; margin: 0;">
                    <select id="oltSelector" class="form-control form-select" onchange="changeOlt(this.value)">
                        <?php foreach($oltDevices as $olt): ?>
                            <option value="<?php echo $olt['olt_id']; ?>" <?php echo $olt['olt_id'] == $selectedOltId ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($olt['olt_name'] . ' (' . $olt['olt_ip'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="container" style="height: 600px;"></div>
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/networkgraph.js"></script>

<script>
Highcharts.chart('container', {
    chart: { type: 'networkgraph', marginTop: 80 },
    title: { text: 'OLT → EPON → ONU Network Diagram (<?php echo htmlspecialchars($currentOlt['olt_name']); ?>)' },
    plotOptions: {
        networkgraph: {
            keys: ['from', 'to'],
            layoutAlgorithm: { enableSimulation: false, integration: 'verlet', linkLength: 100 }
        }
    },
    series: [{
        marker: { radius: 10 },
        dataLabels: { enabled: true },
        data: <?php echo json_encode($links); ?>,
        nodes: <?php
            $nodes = [];
            foreach($nodesColor as $id=>$color){
                $nodes[] = ['id'=>$id, 'color'=>$color];
            }
            echo json_encode($nodes);
        ?>
    }]
});

function changeOlt(oltId) {
    fetch('pages/olt/set_selected_olt.php?olt_id=' + oltId)
        .then(() => location.reload())
        .catch(err => console.error('Error:', err));
}
</script>
