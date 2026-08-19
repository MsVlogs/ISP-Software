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

// OIDs for interface overview
$oids = [
    'interface_name' => "1.3.6.1.2.1.2.2.1.2",
    'oper_status'    => "1.3.6.1.2.1.2.2.1.8",
    'vendor_id'      => "1.3.6.1.4.1.3320.101.10.1.1.1",
    'serial_number'  => "1.3.6.1.4.1.3320.101.10.1.1.3",
    'uptime'         => "1.3.6.1.2.1.2.2.1.9",
    'onu_status'     => "1.3.6.1.4.1.3320.101.11.4.1.5"
];

// SNMP helper with error handling
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

// Convert hex to serial
function hexToSerial($hexString) {
    $hex = preg_replace('/[^0-9A-Fa-f ]/', '', $hexString);
    $hex = trim($hex);
    return empty($hex) ? null : strtoupper(str_replace(' ', ':', $hex));
}

// Format uptime from ticks
function formatUptime($ticks) {
    if(!is_numeric($ticks) || $ticks <= 0) return null;
    $seconds = (int)($ticks / 100);
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    return "{$days}d {$hours}h {$minutes}m";
}

logMessage("Starting interface status fetch from OLT: $oltIp");

// Status mapping
$statusMap = [
    1 => 'Connected',
    2 => 'Down',
    3 => 'Testing', 
    4 => 'Unknown',
    5 => 'Dormant',
    6 => 'Not Present',
    7 => 'Lower Layer Down'
];

// Fetch interface data
$data = [];
foreach($oids as $key => $oid){
    logMessage("Fetching $key...");
    $lines = snmpWalkLines($community, $oltIp, $oid);
    
    foreach($lines as $line){
        if(empty(trim($line))) continue;
        
        // Universal regex pattern
        if(preg_match('/(?:iso\.)?[\d\.]*\.(\d+)\s*=\s*([^:]+):\s*(.+)$/', $line, $matches)){
            $index = $matches[1];
            $type = trim($matches[2]);
            $value = trim($matches[3]);
            
            switch($key){
                case 'oper_status':
                case 'onu_status':
                    if($type === 'INTEGER'){
                        $statusCode = (int)$value;
                        $data[$index][$key] = $statusMap[$statusCode] ?? "Unknown($statusCode)";
                        logMessage("$key for index $index: $statusCode -> " . $data[$index][$key]);
                    }
                    break;
                    
                case 'uptime':
                    if($type === 'Timeticks' && preg_match('/\((\d+)\)/', $value, $tickMatches)){
                        $ticks = (int)$tickMatches[1];
                        $data[$index][$key] = formatUptime($ticks);
                    }
                    break;
                    
                case 'serial_number':
                    $cleanValue = trim($value, '"');
                    if($type === 'Hex-STRING'){
                        $data[$index][$key] = hexToSerial($cleanValue);
                    } else {
                        $data[$index][$key] = $cleanValue ?: null;
                    }
                    break;
                    
                default:
                    $data[$index][$key] = trim($value, '"') ?: null;
                    break;
            }
        }
    }
}

// Filter EPON interfaces
$onuPorts = array_filter($data, function($item){
    return isset($item['interface_name']) && 
           preg_match('/^EPON\d+\/\d+:\d+$/', $item['interface_name']);
});

// Sort interfaces logically
uasort($onuPorts, function($a, $b){
    if(!isset($a['interface_name']) || !isset($b['interface_name'])) return 0;
    
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $a['interface_name'], $m1);
    preg_match('/EPON(\d+)\/(\d+):(\d+)/', $b['interface_name'], $m2);
    
    if(empty($m1) || empty($m2)) return 0;
    
    return [(int)$m1[1], (int)$m1[2], (int)$m1[3]] <=> [(int)$m2[1], (int)$m2[2], (int)$m2[3]];
});

logMessage("Found " . count($onuPorts) . " EPON interfaces");

if(empty($onuPorts)){
    logMessage("WARNING: No EPON interfaces found!");
    exit;
}

// Database operations
$inserted = $updated = $errors = 0;

try {
    $pdo->beginTransaction();
    
    foreach($onuPorts as $onu){
        try {
            $checkStmt = $pdo->prepare("SELECT id FROM onu_overview WHERE olt_ip = ? AND interface_name = ?");
            $checkStmt->execute([$oltIp, $onu['interface_name']]);
            
            if($checkStmt->fetch()){
                $stmt = $pdo->prepare("
                    UPDATE onu_overview SET
                        oper_status = ?,
                        vendor_id = ?,
                        serial_number = ?,
                        uptime = ?,
                        onu_status = ?,
                        last_updated = CURRENT_TIMESTAMP
                    WHERE olt_ip = ? AND interface_name = ?
                ");
                $stmt->execute([
                    $onu['oper_status'] ?? null,
                    $onu['vendor_id'] ?? null,
                    $onu['serial_number'] ?? null,
                    $onu['uptime'] ?? null,
                    $onu['onu_status'] ?? null,
                    $oltIp,
                    $onu['interface_name']
                ]);
                $updated++;
            } else {
                $stmt = $pdo->prepare("
                    INSERT INTO onu_overview (olt_ip, interface_name, oper_status, vendor_id, serial_number, uptime, onu_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $oltIp,
                    $onu['interface_name'],
                    $onu['oper_status'] ?? null,
                    $onu['vendor_id'] ?? null,
                    $onu['serial_number'] ?? null,
                    $onu['uptime'] ?? null,
                    $onu['onu_status'] ?? null
                ]);
                $inserted++;
            }
        } catch(Exception $e) {
            logMessage("Error processing " . ($onu['interface_name'] ?? 'unknown') . ": " . $e->getMessage());
            $errors++;
        }
    }
    
    $pdo->commit();
    logMessage("SUCCESS: Database updated. Inserted: $inserted, Updated: $updated, Errors: $errors");
    
} catch(Exception $e) {
    $pdo->rollBack();
    logMessage("CRITICAL ERROR: Database transaction failed: " . $e->getMessage());
    exit(1);
}

logMessage("Interface status data fetch completed successfully.");
?>