<?php
set_time_limit(0);
date_default_timezone_set('Asia/Dhaka');

require_once __DIR__ . '/../../services/Database.php';

$db = new Database();
$pdo = $db->getConnection();

// Logging function
function logMessage($msg){
    $time = date('Y-m-d H:i:s');
    echo "[$time] $msg" . PHP_EOL;
}

// OLT credentials
$oltIp = oltSnmpTarget($currentOlt);
$community = $currentOlt['read_community'];

// OIDs for device condition monitoring
$oids = [
    'name'     => "1.3.6.1.2.1.2.2.1.2",
    'rx_power' => "1.3.6.1.4.1.3320.101.10.5.1.5",
    'tx_power' => "1.3.6.1.4.1.3320.101.10.5.1.6", 
    'distance' => "1.3.6.1.4.1.3320.101.10.1.1.27",
    'serial'   => "1.3.6.1.4.1.3320.101.10.1.1.3",
];

$oidIfHCInOctets  = "1.3.6.1.2.1.31.1.1.1.10";
$oidIfHCOutOctets = "1.3.6.1.2.1.31.1.1.1.6";

// SNMP helper with proper error handling
function snmpWalkLines($community, $oltIp, $oid){
    $command = "snmpwalk -v2c -c $community $oltIp $oid 2>&1";
    logMessage("Executing: $command");
    
    $output = shell_exec($command);
    
    if(empty($output)){
        logMessage("ERROR: Empty SNMP response for OID: $oid");
        return [];
    }
    
    if(strpos($output, 'Timeout') !== false || strpos($output, 'No response') !== false){
        logMessage("ERROR: SNMP timeout for OID: $oid");
        return [];
    }
    
    if(strpos($output, 'No Such Object') !== false){
        logMessage("WARNING: OID not found: $oid");
        return [];
    }
    
    logMessage("SNMP response length: " . strlen($output) . " chars");
    return explode("\n", trim($output));
}

// Convert hex to readable serial
function hexToSerial($hexString) {
    $hex = preg_replace('/[^0-9A-Fa-f ]/', '', $hexString);
    $hex = trim($hex);
    return empty($hex) ? null : strtoupper(str_replace(' ', ':', $hex));
}

// Process power values
function processPowerValue($value) {
    $intValue = (int)$value;
    if($intValue == -65535 || $intValue == 65535 || $intValue == 0) return null;
    return round($intValue / 10, 1);
}

logMessage("Starting ONU device data fetch from OLT: $oltIp");

// Fetch basic data
$data = [];
foreach($oids as $key => $oid){
    logMessage("Fetching $key...");
    $lines = snmpWalkLines($community, $oltIp, $oid);
    
    foreach($lines as $line){
        if(empty(trim($line))) continue;
        
        // Universal regex for both ISO and standard format
        if(preg_match('/(?:iso\.)?[\d\.]*\.(\d+)\s*=\s*([^:]+):\s*(.+)$/', $line, $matches)){
            $index = $matches[1];
            $type = trim($matches[2]);
            $value = trim($matches[3], '"');
            
            switch($key){
                case 'rx_power':
                case 'tx_power':
                    $processed = processPowerValue($value);
                    if($processed !== null) $data[$index][$key] = $processed;
                    break;
                    
                case 'serial':
                    if($type === 'Hex-STRING'){
                        $data[$index][$key] = hexToSerial($value);
                    } else {
                        $data[$index][$key] = $value ?: null;
                    }
                    break;
                    
                case 'distance':
                    $intVal = (int)$value;
                    $data[$index][$key] = $intVal > 0 ? $intVal : null;
                    break;
                    
                default:
                    $data[$index][$key] = $value ?: null;
            }
        }
    }
}

// Fetch traffic data
logMessage("Fetching traffic data...");
foreach(snmpWalkLines($community, $oltIp, $oidIfHCInOctets) as $line){
    if(preg_match('/\.(\d+) = Counter64: (\d+)/', $line, $matches)){
        $index = $matches[1];
        $bytes = (int)$matches[2];
        if($bytes > 0) $data[$index]['download_bytes'] = $bytes;
    }
}

foreach(snmpWalkLines($community, $oltIp, $oidIfHCOutOctets) as $line){
    if(preg_match('/\.(\d+) = Counter64: (\d+)/', $line, $matches)){
        $index = $matches[1];
        $bytes = (int)$matches[2];
        if($bytes > 0) $data[$index]['upload_bytes'] = $bytes;
    }
}

// Filter EPON ports
$onuPorts = array_filter($data, function($item){
    return isset($item['name']) && preg_match('/^EPON\d+\/\d+:\d+$/', $item['name']);
});

// Sort logically
uasort($onuPorts, function($a, $b){
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $a['name'], $m1);
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $b['name'], $m2);
    return [(int)$m1[1], (int)$m1[2], (int)$m1[3]] <=> [(int)$m2[1], (int)$m2[2], (int)$m2[3]];
});

logMessage("Found " . count($onuPorts) . " EPON ports");

// Database operations
$inserted = $updated = $errors = 0;

try {
    $pdo->beginTransaction();
    
    foreach($onuPorts as $onu){
        try {
            $checkStmt = $pdo->prepare("SELECT id FROM onu_status WHERE olt_ip = ? AND interface_name = ?");
            $checkStmt->execute([$oltIp, $onu['name']]);
            
            if($checkStmt->fetch()){
                $stmt = $pdo->prepare("
                    UPDATE onu_status SET
                        serial = ?, distance = ?, tx_power = ?, rx_power = ?,
                        download_bytes = ?, upload_bytes = ?, last_updated = CURRENT_TIMESTAMP
                    WHERE olt_ip = ? AND interface_name = ?
                ");
                $stmt->execute([
                    $onu['serial'] ?? null,
                    $onu['distance'] ?? null,
                    $onu['tx_power'] ?? null,
                    $onu['rx_power'] ?? null,
                    $onu['download_bytes'] ?? null,
                    $onu['upload_bytes'] ?? null,
                    $oltIp,
                    $onu['name']
                ]);
                $updated++;
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO onu_status (olt_ip, interface_name, serial, distance, tx_power, rx_power, download_bytes, upload_bytes)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $oltIp,
                    $onu['name'],
                    $onu['serial'] ?? null,
                    $onu['distance'] ?? null,
                    $onu['tx_power'] ?? null,
                    $onu['rx_power'] ?? null,
                    $onu['download_bytes'] ?? null,
                    $onu['upload_bytes'] ?? null
                ]);
                $inserted++;
            }
        } catch(Exception $e) {
            logMessage("Error: " . $e->getMessage());
            $errors++;
        }
    }
    
    $pdo->commit();
    logMessage("SUCCESS: Inserted: $inserted, Updated: $updated, Errors: $errors");
    
} catch(Exception $e) {
    $pdo->rollBack();
    logMessage("CRITICAL ERROR: " . $e->getMessage());
}
?>