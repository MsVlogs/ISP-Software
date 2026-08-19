<?php
require_once __DIR__ . '/../../services/OltService.php';

oltEnsureSession();
header('Content-Type: application/json; charset=utf-8');

$oltId = filter_input(INPUT_GET, 'olt_id', FILTER_VALIDATE_INT);
if (!$oltId) {
    echo json_encode(['success' => false, 'message' => 'Invalid OLT ID']);
    exit;
}

$pdo = oltDb();
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Database unavailable']);
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM tbl_olt_devices WHERE id = :id AND status = 1 LIMIT 1');
$stmt->execute(['id' => $oltId]);

if (!$stmt->fetchColumn()) {
    echo json_encode(['success' => false, 'message' => 'OLT is not active or does not exist']);
    exit;
}

$_SESSION['selected_olt_id'] = $oltId;
echo json_encode(['success' => true, 'olt_id' => $oltId]);
