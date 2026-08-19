<?php
set_time_limit(300);
require_once __DIR__ . '/../../services/OltService.php';

$currentOlt = oltGetSelectedDevice();
if (!$currentOlt) {
    echo '<div class="alert alert-warning">No active OLT configured. Please configure an OLT first.</div>';
    return;
}

$oltIp = oltSnmpTarget($currentOlt);
$community = $currentOlt['read_community'];

// Standard IF-MIB OIDs
$oidIfDescr      = "1.3.6.1.2.1.2.2.1.2";
$oidIfOperStatus = "1.3.6.1.2.1.2.2.1.8";
$oidIfLastChange = "1.3.6.1.2.1.2.2.1.9";

function snmpFetch($oid)
{
    global $oltIp, $community;

    $cmd = sprintf(
        'snmpwalk -v2c -c %s %s %s 2>&1',
        escapeshellarg($community),
        escapeshellarg($oltIp),
        escapeshellarg($oid)
    );

    $output = shell_exec($cmd);

    if (!$output) {
        return [];
    }

    return preg_split('/\r\n|\r|\n/', trim($output));
}

function extractIndex($line)
{
    if (preg_match('/\.([0-9]+)\s*=\s*/', $line, $m)) {
        return (int)$m[1];
    }

    return null;
}

// Fetch SNMP data
$descrLines  = snmpFetch($oidIfDescr);
$statusLines = snmpFetch($oidIfOperStatus);
$uptimeLines = snmpFetch($oidIfLastChange);

$interfaceData = [];

/*
 * Interface names
 *
 * Example:
 * iso.3.6.1.2.1.2.2.1.2.97 = STRING: "EPON0/4:1"
 */
foreach ($descrLines as $line) {

    $index = extractIndex($line);

    if ($index === null) {
        continue;
    }

    if (preg_match('/=\s*STRING:\s*"?([^"]*)"?\s*$/', $line, $m)) {

        $name = trim($m[1]);

        $interfaceData[$index] = [
            'name' => $name
        ];
    }
}

/*
 * OperStatus
 *
 * 1 = Up
 * 2 = Down
 * 3 = Testing
 * 4 = Unknown
 * 5 = Dormant
 * 6 = Not Present
 * 7 = Lower Layer Down
 */
$statusMap = [
    1 => 'Connected',
    2 => 'Down',
    3 => 'Testing',
    4 => 'Unknown',
    5 => 'Dormant',
    6 => 'Not Present',
    7 => 'Lower Layer Down'
];

foreach ($statusLines as $line) {

    $index = extractIndex($line);

    if ($index === null) {
        continue;
    }

    if (preg_match('/=\s*INTEGER:\s*(?:\w+\()?([0-9]+)\)?/', $line, $m)) {

        $statusCode = (int)$m[1];

        if (isset($interfaceData[$index])) {
            $interfaceData[$index]['status'] =
                $statusMap[$statusCode] ?? 'Unknown';
        }
    }
}

/*
 * Uptime / ifLastChange
 *
 * Example:
 * Timeticks: (47532419) 5 days, 12:02:04.19
 */
foreach ($uptimeLines as $line) {

    $index = extractIndex($line);

    if ($index === null) {
        continue;
    }

    if (preg_match('/Timeticks:\s*\((\d+)\)/', $line, $m)) {

        $ticks = (int)$m[1];

        if (isset($interfaceData[$index])) {

            if ($ticks > 0) {

                $seconds = $ticks / 100;

                $days = (int)floor($seconds / 86400);
                $hours = (int)floor(
                    fmod($seconds, 86400) / 3600
                );
                $minutes = (int)floor(
                    fmod($seconds, 3600) / 60
                );

                $interfaceData[$index]['uptime'] =
                    sprintf(
                        "%dd %dh %dm",
                        $days,
                        $hours,
                        $minutes
                    );

            } else {

                $interfaceData[$index]['uptime'] = '-';
            }
        }
    }
}

/*
 * Keep only ONU interfaces.
 *
 * Examples:
 * EPON0/4:1
 * EPON0/3:1
 * EPON0/8:2
 */
$onuPorts = array_filter(
    $interfaceData,
    function ($data) {

        return isset($data['name'])
            && preg_match(
                '/^EPON\d+\/\d+:\d+$/',
                $data['name']
            );
    }
);

/*
 * Sort by:
 *
 * EPON card / port / ONU number
 */
uasort(
    $onuPorts,
    function ($a, $b) {

        preg_match(
            '/EPON(\d+)\/(\d+):(\d+)/',
            $a['name'],
            $x
        );

        preg_match(
            '/EPON(\d+)\/(\d+):(\d+)/',
            $b['name'],
            $y
        );

        return [
            (int)$x[1],
            (int)$x[2],
            (int)$x[3]
        ] <=> [
            (int)$y[1],
            (int)$y[2],
            (int)$y[3]
        ];
    }
);
?>

<div class="container py-4">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">

            <h5 class="mb-0 text-primary fw-bold">
                🖧 ONU Port Overview
            </h5>

            <div>
                <span class="badge bg-info text-dark me-3">
                    Total ONUs: <?= count($onuPorts) ?>
                </span>

                <button
                    onclick="location.reload()"
                    class="btn btn-sm btn-outline-primary">
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
                            <th>SL</th>
                            <th>Interface</th>
                            <th>Status</th>
                            <th>Uptime</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if (empty($onuPorts)): ?>

                        <tr>
                            <td
                                colspan="4"
                                class="text-center text-danger">
                                ⚠ No ONU data found
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php $sl = 1; ?>

                        <?php foreach ($onuPorts as $onu): ?>

                            <tr class="text-center">

                                <td>
                                    <?= $sl++ ?>
                                </td>

                                <td>
                                    <code>
                                        <?= htmlspecialchars(
                                            $onu['name']
                                        ) ?>
                                    </code>
                                </td>

                                <td>

                                    <?php

                                    $status =
                                        $onu['status']
                                        ?? 'Unknown';

                                    $badgeClass = match ($status) {

                                        'Connected'
                                            => 'bg-success',

                                        'Down'
                                            => 'bg-danger',

                                        'Testing'
                                            => 'bg-warning text-dark',

                                        'Dormant',
                                        'Lower Layer Down'
                                            => 'bg-secondary',

                                        default
                                            => 'bg-dark'
                                    };

                                    ?>

                                    <span
                                        class="badge <?= $badgeClass ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>

                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $onu['uptime'] ?? '-'
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
