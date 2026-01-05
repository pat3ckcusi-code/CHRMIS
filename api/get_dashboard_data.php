<?php
require_once __DIR__ . '/../includes/db_config.php';
header('Content-Type: application/json');

$dept = $_GET['dept'] ?? '';

if (!$dept) {
    echo json_encode([]);
    exit;
}

// Get employees in that department
$sql = "
SELECT i.EmpNo, CONCAT(i.Fname, ' ', IFNULL(CONCAT(LEFT(i.Mname,1),'. '),''), i.Lname, ' ', IFNULL(i.Extension,'')) AS FullName,
       v.Position,
       CASE WHEN i.Gender IS NULL OR i.Gender='' THEN 'Unknown' ELSE i.Gender END AS Gender,
       TIMESTAMPDIFF(YEAR, i.BirthDate, CURDATE()) AS Age
FROM i
LEFT JOIN v ON i.EmpNo = v.EmpNo AND v.IndateTo = '0000-00-00'
WHERE i.Dept = :dept
ORDER BY i.Lname, i.Fname
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['dept'=>$dept]);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($employees);
