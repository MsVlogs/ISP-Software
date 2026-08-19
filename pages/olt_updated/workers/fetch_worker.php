<?php
set_time_limit(0);
date_default_timezone_set('Asia/Dhaka'); // Worker timezone

echo "[".date("Y-m-d H:i:s")."] Worker started...\n";

// Database connection
require_once __DIR__ . '/../services/Database.php';
$db = new Database();
$pdo = $db->getConnection();

// Active window in seconds (5 minutes)
$activeWindow = 300;

while(true){

    // Fetch last active timestamp in UTC
    $stmt = $pdo->query("SELECT MAX(last_active) as last_active FROM user_activity");
    $lastActive = $stmt->fetchColumn();

    // Current server time (Asia/Dhaka)
    $now = time();

    // Convert DB timestamp to timestamp
    $lastActiveTs = $lastActive ? strtotime($lastActive) : null;

    // Debug prints
    echo "[".date("Y-m-d H:i:s")."] Last active from DB: $lastActive\n";
    $diff = $lastActiveTs ? ($now - $lastActiveTs) : null;
    echo "[".date("Y-m-d H:i:s")."] Time diff: ".($diff !== null ? $diff." seconds" : "NULL")."\n";

    // Check if user is active in last 5 minutes
    if($lastActiveTs && $diff <= $activeWindow){
        echo "[".date("Y-m-d H:i:s")."] User active. Running fetch scripts...\n";

        // Fetch ONU data
        exec("php /var/www/isp-software/pages/olt/fetch_onu_data.php", $output1, $ret1);
        foreach($output1 as $line) echo $line . "\n";
        echo "[".date("Y-m-d H:i:s")."] fetch_onu_data.php exit code: $ret1\n";

        // Fetch Interface data
        exec("php /var/www/isp-software/pages/olt/fetch_interface_data.php", $output2, $ret2);
        foreach($output2 as $line) echo $line . "\n";
        echo "[".date("Y-m-d H:i:s")."] fetch_interface_data.php exit code: $ret2\n";


    } else {
        echo "[".date("Y-m-d H:i:s")."] No active user in last 5 minutes, skipping fetch.\n";
    }

    // Sleep 60 seconds before next check
    sleep(60);
}

