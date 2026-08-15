<?php
set_time_limit(0);

// === OLT credentials & OIDs ===
$oltIp = "103.178.220.124:50501";
$community = "bsd";
$oids = [
    'descr'       => "1.3.6.1.2.1.2.2.1.2",
    'oper_status' => "1.3.6.1.2.1.2.2.1.8"
];
// snmpbulkwalk -v2c -c bsd -Cr10 -t 4 -r 1 -Cc 103.178.220.124:50501

// SNMP fetch function
function snmpBulkFetch($community, $oltIp, $oids){
    $data = [];
    foreach($oids as $key=>$oid){
        $lines = explode("\n", trim(shell_exec("snmpbulkwalk -v2c -c $community -t 2 -r 2 $oltIp $oid 2>&1")));
        foreach($lines as $line){
            // match integer or string values
            if(preg_match('/\.(\d+)\s*=\s*(?:STRING|INTEGER):\s*(.*)$/i', trim($line), $m)){
                $index = $m[1];
                $value = trim($m[2], "\" ");
                // clean INTEGER prefix
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
    $rawStatus = (int)preg_replace('/\D/', '', $onu['oper_status'] ?? '0'); // extract numeric only
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

// Optional debug (you can comment out later)
// echo "<pre>"; print_r($onuData); echo "</pre>";
?>

<div id="container" style="height: 600px;"></div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/networkgraph.js"></script>

<script>
Highcharts.chart('container', {
    chart: { type: 'networkgraph', marginTop: 80 },
    title: { text: 'OLT → EPON → ONU Network Diagram (Dynamic SNMP)' },
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
</script>
