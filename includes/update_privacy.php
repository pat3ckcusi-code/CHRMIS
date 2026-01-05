<?php
require_once('../includes/db_config.php');

if (isset($_POST['id']) && isset($_POST['agree']) && $_POST['agree'] == 'Yes') {
    $stmt = $pdo->prepare("UPDATE i SET Privacy = 'Yes' WHERE EmpNo = ?");
    $stmt->execute([$_POST['id']]);
    echo "OK";
} else {
    echo "Error";
}
?>
