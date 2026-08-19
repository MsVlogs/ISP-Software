<?php
require_once __DIR__ . '/Database.php';

function oltDb(): ?PDO
{
    static $pdo = null;
    static $loaded = false;

    if ($loaded) {
        return $pdo;
    }

    $loaded = true;
    $db = new Database();
    $pdo = $db->getConnection();
    return $pdo;
}

function oltEnsureSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function oltGetActiveDevices(): array
{
    $pdo = oltDb();
    if (!$pdo) return [];

    $stmt = $pdo->query(
        'SELECT id, device_name, ip_address, read_community, write_community, snmp_port, status, created_at, updated_at
         FROM tbl_olt_devices
         WHERE status = 1
         ORDER BY device_name ASC, id ASC'
    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function oltGetSelectedDevice(): ?array
{
    oltEnsureSession();
    $devices = oltGetActiveDevices();

    if (!$devices) {
        unset($_SESSION['selected_olt_id']);
        return null;
    }

    $selectedId = isset($_SESSION['selected_olt_id']) ? (int) $_SESSION['selected_olt_id'] : 0;

    foreach ($devices as $device) {
        if ((int) $device['id'] === $selectedId) {
            return $device;
        }
    }

    $_SESSION['selected_olt_id'] = (int) $devices[0]['id'];
    return $devices[0];
}

function oltSnmpTarget(array $device): string
{
    return $device['ip_address'] . ':' . (int) $device['snmp_port'];
}

function oltSelectedId(): ?int
{
    $device = oltGetSelectedDevice();
    return $device ? (int) $device['id'] : null;
}
