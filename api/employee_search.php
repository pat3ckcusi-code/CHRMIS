<?php
require_once __DIR__ . '/../includes/initialize.php';
header('Content-Type: application/json');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$dept = isset($_GET['dept']) ? trim($_GET['dept']) : '';

if($q === ''){ echo json_encode([]); exit; }

$like = '%' . str_replace(' ', '%', $q) . '%';

$sql = "SELECT i.EmpNo,
  CONCAT(i.Lname, ', ', i.Fname, ' ', i.Mname,
    CASE WHEN i.Extension = 'N/A' THEN '' ELSE CONCAT(' ', i.Extension) END) AS FullName,
  (SELECT v.Position FROM v WHERE v.EmpNo = i.EmpNo AND v.IndateTo = '0000-00-00' LIMIT 1) AS Position
  FROM i
  WHERE (i.EmpNo LIKE :like OR i.Lname LIKE :like OR i.Fname LIKE :like OR CONCAT(i.Lname, ' ', i.Fname) LIKE :like)
";
if($dept !== ''){ $sql .= " AND i.Dept = :dept"; }
$sql .= " ORDER BY i.Lname, i.Fname LIMIT 50";

$stmt = $pdo->prepare($sql);
$params = ['like'=>$like];
if($dept !== '') $params['dept'] = $dept;
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rows);
