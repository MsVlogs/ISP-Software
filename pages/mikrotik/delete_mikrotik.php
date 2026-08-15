<?php
require_once(__DIR__ . '/../../services/Model.php');
$obj = new Model();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $obj->raw_sql("DELETE FROM mikrotik_user WHERE id = '$id'");
    echo "<script>
            alert('Mikrotik Server successfully deleted!');
            window.location.href = '../../index.php?page=mikrotik_connection';
          </script>";
} else {
    header("Location: ../../index.php?page=mikrotik_connection");
    exit();
}
?>
