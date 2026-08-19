<?php
require_once(__DIR__ . '/../../services/Model.php');
$obj = new Model();
header('Content-Type: application/json');
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
if (!$id || $status === false || $status === null) { echo json_encode(['success'=>false,'status'=>'Invalid MikroTik status request.']); exit; }
$ok = $obj->updateData('mikrotik_user', ['status' => $status], ['id' => $id]);
echo json_encode(['success'=>(bool)$ok,'status'=>$ok ? ($status ? 'MikroTik activated successfully.' : 'MikroTik deactivated successfully.') : 'Failed to update MikroTik status.']);
