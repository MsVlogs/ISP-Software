<?php
if (!isset($_POST['ip'], $_POST['port'], $_POST['community'])) {
    echo "❌ Invalid input.";
    exit;
}

$ip = $_POST['ip'];
$port = $_POST['port'];
$community = $_POST['community'];
$timeout = 2; // seconds

// Step 1: SNMP Check
snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
$snmp_response = @snmpget("{$ip}:{$port}", $community, '1.3.6.1.2.1.1.5.0'); // sysName OID

if ($snmp_response !== false) {
    echo "✅ SNMP OK: Device name - $snmp_response";
    exit;
}

// Step 2: Ping fallback
function ping($host, $timeout = 2) {
    $os = strtoupper(PHP_OS_FAMILY);
    if ($os === 'WINDOWS') {
        $cmd = "ping -n 1 -w " . ($timeout * 1000) . " $host";
    } else {
        $cmd = "ping -c 1 -W " . (int)$timeout . " " . escapeshellarg((string)$host);
    }
    exec($cmd, $output, $status);
    return $status === 0;
}

if (ping($ip, $timeout)) {
    echo "⚠️ SNMP Failed, but Ping is OK!";
} else {
    echo "❌ Device is unreachable (SNMP & Ping both failed).";
}
?>
