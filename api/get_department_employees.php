<?php
require_once __DIR__ . '/../includes/db_config.php';
header('Content-Type: application/json');

$dept = $_GET['dept'] ?? '';
if (empty($dept)) {
    echo json_encode([]);
    exit;
}

// ===== Fetch employees from table i + current position from table v =====
$stmt = $pdo->prepare("
    SELECT 
        i.EmpNo,
        CONCAT(
            i.Lname, ', ', i.Fname, ' ', i.Mname,
            CASE WHEN i.Extension = 'N/A' THEN '' ELSE CONCAT(' ', i.Extension) END
        ) AS FullName,
        i.BirthDate,
        i.Dept,
        i.EmploymentStatus,
        i.Gender,
        i.date_hired,
        (SELECT v.Position  
         FROM v  
         WHERE v.EmpNo = i.EmpNo AND v.IndateTo = '0000-00-00'  
         LIMIT 1) AS Position
    FROM i
    WHERE i.Dept = ?
    ORDER BY i.Lname, i.Fname
");
$stmt->execute([$dept]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== Calculate Age =====
foreach ($rows as &$row) {
    $birthDate = new DateTime($row['BirthDate']);
    $today = new DateTime();
    $row['Age'] = $today->diff($birthDate)->y;
}

echo json_encode($rows);
