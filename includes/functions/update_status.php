<?php
require_once('../initialize.php');

if(isset($_POST['id'], $_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "UPDATE eta_locator SET status = ?, last_updated = NOW() WHERE id = ? AND status = 'Pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $id]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
