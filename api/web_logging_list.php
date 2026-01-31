<?php
session_start();
require_once(__DIR__ . '/../includes/initialize.php');
header('Content-Type: application/json');

$employee_id = isset($_GET['EmpNo']) ? trim($_GET['EmpNo']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';

$sql = "SELECT id, EmpNo, date, time_am_in, time_am_out, time_pm_in, time_pm_out, remarks FROM web_logs";
$conds = [];
if($employee_id){ $conds[] = "EmpNo = :emp"; }
if($date){ $conds[] = "date = :date"; }
if(count($conds)) $sql .= ' WHERE ' . implode(' AND ', $conds);
$sql .= ' ORDER BY date DESC, time_am_in DESC LIMIT 50';

$stmt = $pdo->prepare($sql);
$params = [];
if($employee_id) $params['emp'] = $employee_id;
if($date) $params['date'] = $date;
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format times for client convenience
foreach ($rows as &$r) {
  $r['time_am_in'] = $r['time_am_in'] ?: '';
  $r['time_am_out'] = $r['time_am_out'] ?: '';
  $r['time_pm_in'] = $r['time_pm_in'] ?: '';
  $r['time_pm_out'] = $r['time_pm_out'] ?: '';
}
unset($r);
echo json_encode(['rows'=>$rows]);
