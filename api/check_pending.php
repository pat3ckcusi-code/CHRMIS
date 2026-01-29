<?php
session_start();
require_once('../includes/initialize.php');

$dept = $_SESSION['Dept'] ?? '';
$access = $_SESSION['access_level'] ?? $_SESSION['AccessLevel'] ?? '';

if ($dept) {
    if ($access !== 'Department Head') {
        $sql = "SELECT COUNT(*) as pendingCount FROM eta_locator el
                INNER JOIN i ON el.EmpNo = i.EmpNo
                WHERE i.Dept = ? AND el.status = 'Pending'";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dept]);
    } else {
        $sql = "SELECT (
                SELECT COUNT(*) FROM filedleave fl
                INNER JOIN i ON fl.EmpNo = i.EmpNo
                WHERE i.Dept = ? AND fl.Status = 'For Recommendation'
            ) + (
                SELECT COUNT(*) FROM eta_locator el
                INNER JOIN i ON el.EmpNo = i.EmpNo
                WHERE i.Dept = ? AND el.status = 'Pending'
            ) as pendingCount";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dept, $dept]);
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'pendingCount' => (int)($row['pendingCount'] ?? 0)
    ]);
} else {
    echo json_encode(['pendingCount' => 0]);
}
