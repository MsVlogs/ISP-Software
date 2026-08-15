<?php
// Helper file to set selected OLT in session
session_start();

if (isset($_GET['olt_id'])) {
    $_SESSION['selected_olt_id'] = $_GET['olt_id'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
exit;
