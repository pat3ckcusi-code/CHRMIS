<?php
session_start();
require_once(__DIR__ . '/../includes/initialize.php');

header('Content-Type: application/json');

if (!isset($_SESSION['Dept']) || $_SESSION['Dept'] !== 'Office of the Mayor') {
    echo json_encode(['pendingCount' => 0]);
    exit;
}

try {
    $sql = "SELECT COUNT(*) as cnt FROM filedleave WHERE Remarks = 'FOR APPROVAL'";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = intval($row['cnt'] ?? 0);
    echo json_encode(['pendingCount' => $count]);
} catch (Exception $e) {
    echo json_encode(['pendingCount' => 0]);
}
