<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db_config.php';


// Get department filter (if any)
$dept = isset($_GET['dept']) ? trim($_GET['dept']) : '';

// Base SQL condition
$where = "";
$params = [];

if (!empty($dept)) {
  $where = "WHERE Dept = :dept";
  $params[':dept'] = $dept;
}

// GENDER DISTRIBUTION
$stmt = $pdo->prepare("SELECT Gender, COUNT(*) as total FROM i $where GROUP BY Gender");
$stmt->execute($params);
$genderData = $stmt->fetchAll();

// EMPLOYMENT STATUS
$stmt = $pdo->prepare("SELECT EmploymentStatus, COUNT(*) as total FROM i $where GROUP BY EmploymentStatus");
$stmt->execute($params);
$statusData = $stmt->fetchAll();

// AGE GROUP DISTRIBUTION
$stmt = $pdo->prepare("
  SELECT 
    CASE 
      WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) < 30 THEN '<30'
      WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) BETWEEN 30 AND 39 THEN '30-39'
      WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) BETWEEN 40 AND 49 THEN '40-49'
      WHEN TIMESTAMPDIFF(YEAR, BirthDate, CURDATE()) BETWEEN 50 AND 59 THEN '50-59'
      ELSE '60+' 
    END AS AgeGroup,
    COUNT(*) AS total
  FROM i
  $where
  GROUP BY AgeGroup
  ORDER BY AgeGroup
");
$stmt->execute($params);
$ageData = $stmt->fetchAll();

// LENGTH OF SERVICE
$stmt = $pdo->prepare("
  SELECT 
    CASE 
      WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) < 5 THEN '<5 yrs'
      WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) BETWEEN 5 AND 9 THEN '5-9 yrs'
      WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) BETWEEN 10 AND 14 THEN '10-14 yrs'
      WHEN TIMESTAMPDIFF(YEAR, date_hired, CURDATE()) BETWEEN 15 AND 19 THEN '15-19 yrs'
      ELSE '20+ yrs' 
    END AS ServiceLength,
    COUNT(*) AS total
  FROM i
  $where
  GROUP BY ServiceLength
  ORDER BY ServiceLength
");
$stmt->execute($params);
$tenureData = $stmt->fetchAll();

// Return JSON response
echo json_encode([
  'gender' => $genderData,
  'status' => $statusData,
  'age' => $ageData,
  'tenure' => $tenureData
]);
?>
