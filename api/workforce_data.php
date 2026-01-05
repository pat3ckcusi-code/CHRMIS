<?php
require_once __DIR__ . '/../includes/db_config.php';
header('Content-Type: application/json');

$deptFilter = isset($_GET['dept']) && $_GET['dept'] !== '' ? $_GET['dept'] : null;

// ===== Employees per Department =====
$deptSql = "SELECT Dept, COUNT(*) AS total FROM i";
if ($deptFilter) $deptSql .= " WHERE Dept = :dept";
$deptSql .= " GROUP BY Dept ORDER BY Dept";

$stmt = $pdo->prepare($deptSql);
if ($deptFilter) $stmt->execute(['dept' => $deptFilter]);
else $stmt->execute();
$deptData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== Gender Distribution =====
$genderSql = "SELECT CASE WHEN Gender IS NULL OR Gender = '' THEN 'Unknown' ELSE Gender END AS Gender, COUNT(*) AS total FROM i";
if ($deptFilter) $genderSql .= " WHERE Dept = :dept";
$genderSql .= " GROUP BY Gender";
$stmt = $pdo->prepare($genderSql);
if ($deptFilter) $stmt->execute(['dept'=>$deptFilter]);
else $stmt->execute();
$genderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== Employment Status =====
$statusSql = "SELECT CASE WHEN EmploymentStatus IS NULL OR EmploymentStatus = '' THEN 'Unknown' ELSE EmploymentStatus END AS EmploymentStatus, COUNT(*) AS total FROM i";
if ($deptFilter) $statusSql .= " WHERE Dept = :dept";
$statusSql .= " GROUP BY EmploymentStatus";
$stmt = $pdo->prepare($statusSql);
if ($deptFilter) $stmt->execute(['dept'=>$deptFilter]);
else $stmt->execute();
$statusData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== Age Group Distribution =====
$ageSql = "SELECT 
    CASE 
        WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) < 30 THEN '<30'
        WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) BETWEEN 30 AND 39 THEN '30-39'
        WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) BETWEEN 40 AND 49 THEN '40-49'
        WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) BETWEEN 50 AND 59 THEN '50-59'
        ELSE '60+'
    END AS AgeGroup,
    COUNT(*) AS total
FROM i";
if ($deptFilter) $ageSql .= " WHERE Dept = :dept";
$ageSql .= " GROUP BY AgeGroup";
$stmt = $pdo->prepare($ageSql);
if ($deptFilter) $stmt->execute(['dept'=>$deptFilter]);
else $stmt->execute();
$ageData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===== Length of Service / Tenure =====
$tenureSql = "SELECT 
    CASE
        WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) < 1 THEN '<1 yr'
        WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) BETWEEN 1 AND 4 THEN '1-4 yrs'
        WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) BETWEEN 5 AND 9 THEN '5-9 yrs'
        WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) BETWEEN 10 AND 14 THEN '10-14 yrs'
        WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) BETWEEN 15 AND 19 THEN '15-19 yrs'
        ELSE '20+ yrs'
    END AS ServiceLength,
    COUNT(*) AS total
FROM i";
if ($deptFilter) $tenureSql .= " WHERE Dept = :dept";
$tenureSql .= " GROUP BY ServiceLength";
$stmt = $pdo->prepare($tenureSql);
if ($deptFilter) $stmt->execute(['dept'=>$deptFilter]);
else $stmt->execute();
$tenureData = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'dept' => $deptData,
    'gender' => $genderData,
    'status' => $statusData,
    'age' => $ageData,
    'tenure' => $tenureData
]);
