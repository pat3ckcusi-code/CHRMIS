<?php
require_once('../includes/db_config.php');
session_start();

header('Content-Type: application/json');

$aoDept = $_SESSION['Dept'] ?? null;
if (!$aoDept) {
    echo json_encode(['count' => 0]);
    exit;
}

try {
    // Count approved ETAs with future travel_date
    $stmtETA = $pdo->prepare("
        SELECT COUNT(*) 
        FROM eta_locator l
        INNER JOIN i e ON e.EmpNo = l.EmpNo
        WHERE e.Dept = :dept
          AND l.application_type = 'ETA'
          AND l.status = 'Approved'
          AND l.travel_date >= CURDATE()
    ");
    $stmtETA->execute(['dept' => $aoDept]);
    $etaCount = (int)$stmtETA->fetchColumn();

    // Count approved Locators with future intended_arrival
    $stmtLOC = $pdo->prepare("
    SELECT COUNT(*) 
    FROM eta_locator l
    INNER JOIN i e ON e.EmpNo = l.EmpNo
    WHERE e.Dept = :dept
      AND l.application_type = 'Locator'
      AND l.status = 'Approved'
      AND l.intended_arrival >= NOW()
      AND l.Arrival_Time IS NULL
    ");
    $stmtLOC->execute(['dept' => $aoDept]);
    $locatorCount = (int)$stmtLOC->fetchColumn();

    echo json_encode(['count' => $etaCount + $locatorCount]);

} catch (PDOException $e) {
    echo json_encode(['count' => 0]);
}
