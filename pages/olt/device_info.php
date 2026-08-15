<?php
require 'vendor/autoload.php'; // FreeDSx SNMP

// $ip = '172.35.156.14';
$ip = '103.103.33.114:161';
$community = 'BDCOM-OLT-1';

$error = null;
$device = [];

try {
    // SNMP GET using FreeDSx
    $snmp = new \FreeDSx\Snmp\SnmpClient([
        'host' => $ip,
        'version' => 2,
        'community' => $community,
    ]);

    $sysDescr = $snmp->getValue('1.3.6.1.2.1.1.1.0');
    $sysName = $snmp->getValue('1.3.6.1.2.1.1.5.0');
    // $cpuUsage = $snmp->getValue('1.3.6.1.2.1.4.2.0'); // Optional, may not be supported

    // System uptime
    $uptimeRaw = shell_exec("snmpwalk -v2c -c $community $ip 1.3.6.1.2.1.1.3.0");
    preg_match('/Timeticks:.*\((\d+)\)\s+(.+)/', $uptimeRaw, $uptimeMatches);
    $sysUpTime = $uptimeMatches[2] ?? 'N/A';

    // Memory usage
    $oid = "1.3.6.1.4.1.3320.9.48.1";
    $output = shell_exec("snmpwalk -v2c -c $community $ip $oid");
    $lines = explode("\n", trim($output));
    $memoryUsage = 'N/A';
    if (isset($lines[0])) {
        preg_match('/INTEGER:\s*(\d+)/', $lines[0], $matches);
        $memoryUsage = $matches[1] ?? 'N/A';
    }

    $device = [
        'sysDescr' => $sysDescr,
        'sysName' => $sysName,
        'sysUpTime' => $sysUpTime,
        // 'cpuUsage' => $cpuUsage,
        'memoryUsage' => $memoryUsage,
    ];

} catch (Exception $e) {
    $error = 'SNMP query failed: ' . $e->getMessage();
}
?>


<div class="container py-4">
  <div class="card mx-auto" style="max-width: 480px; border-radius: 0.5rem;">
    <div class="card-header text-center fw-bold fs-5 bg-light" style="border-radius: 0.5rem 0.5rem 0 0;">
      Device Information
    </div>
    <div class="card-body">
      <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php else: ?>
        <dl class="row mb-0">
          <dt class="col-5 text-muted">System Description</dt>
          <dd class="col-7"><?= htmlspecialchars($device['sysDescr'] ?? 'N/A') ?></dd>

          <dt class="col-5 text-muted">System Name</dt>
          <dd class="col-7"><?= htmlspecialchars($device['sysName'] ?? 'N/A') ?></dd>

          <dt class="col-5 text-muted">System Uptime</dt>
          <dd class="col-7"><?= htmlspecialchars($device['sysUpTime'] ?? 'N/A') ?></dd>

          <!-- <dt class="col-5 text-muted">CPU Usage</dt>
          <dd class="col-7"><?= htmlspecialchars($device['cpuUsage'] ?? 'N/A') ?></dd> -->

          <dt class="col-5 text-muted">Memory Usage</dt>
          <dd class="col-7"><?= htmlspecialchars($device['memoryUsage'] ?? 'N/A') ?>%</dd>
        </dl>
      <?php endif; ?>
    </div>
    <div class="card-footer text-center text-muted small" style="border-radius: 0 0 0.5rem 0.5rem;">
      SNMP data from <code><?= htmlspecialchars($ip) ?></code>
    </div>
  </div>
</div>
