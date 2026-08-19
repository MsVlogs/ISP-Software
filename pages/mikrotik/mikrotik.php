<?php
session_start();
require(realpath(__DIR__ . '/../../services/Model.php'));
$obj = new Model();
$allmikrotik = $obj->getAllData('mikrotik_user');
$totalData = count($obj->getAllData('mikrotik_user'));
$totalFiltered = count($obj->getAllData('mikrotik_user'));
$allData = [];

$i = 1;
foreach ($allmikrotik as $item) {
    $allData[] = [
        'sl' => $i++,
        'id' => $item['id'] ?? 'N/A',
        'mik_username' => $item['mik_username'] ?? 'N/A',
        'mik_ip' => $item['mik_ip'] ?? 'N/A',
        'mik_port' => $item['mik_port'] ?? 'N/A',
        'status' => !empty($item['status']) ? 'Enable' : 'Disabled'
    ];
};
echo json_encode([
    'data' => $allData,
    'recordsTotal' => $totalData,
    'recordsFiltered' => $totalFiltered
]);
