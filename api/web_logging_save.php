<?php
session_start();
require_once(__DIR__ . '/../includes/initialize.php');
header('Content-Type: application/json');

$employee_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$time_am_in = isset($_POST['time_am_in']) && $_POST['time_am_in'] !== '' ? trim($_POST['time_am_in']) : null;
$time_am_out = isset($_POST['time_am_out']) && $_POST['time_am_out'] !== '' ? trim($_POST['time_am_out']) : null;
$time_pm_in = isset($_POST['time_pm_in']) && $_POST['time_pm_in'] !== '' ? trim($_POST['time_pm_in']) : null;
$time_pm_out = isset($_POST['time_pm_out']) && $_POST['time_pm_out'] !== '' ? trim($_POST['time_pm_out']) : null;
$remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

if(!$employee_id || !$date){ echo json_encode(['success'=>false,'message'=>'Missing required fields']); exit; }
// require at least one pair
if(!(($time_am_in && $time_am_out) || ($time_pm_in && $time_pm_out))){ echo json_encode(['success'=>false,'message'=>'Provide at least AM or PM Time In/Out pair']); exit; }
if($time_am_in && $time_am_out && $time_am_out <= $time_am_in){ echo json_encode(['success'=>false,'message'=>'AM Time Out must be later than AM Time In']); exit; }
if($time_pm_in && $time_pm_out && $time_pm_out <= $time_pm_in){ echo json_encode(['success'=>false,'message'=>'PM Time Out must be later than PM Time In']); exit; }
if($time_am_in && $time_am_out && $time_pm_in && $time_pm_out && $time_am_out > $time_pm_in){ echo json_encode(['success'=>false,'message'=>'AM Time Out must be earlier than or equal to PM Time In']); exit; }

// Build overlap conditions dynamically for any provided range
$overlapConds = [];
$params = ['emp'=>$employee_id, 'date'=>$date];

if($time_am_in && $time_am_out){
  $params['am_in'] = $time_am_in; $params['am_out'] = $time_am_out;
  // overlap with existing AM
  $overlapConds[] = "NOT (time_am_out <= :am_in OR time_am_in >= :am_out)";
  // overlap with existing PM
  $overlapConds[] = "NOT (time_pm_out <= :am_in OR time_pm_in >= :am_out)";
}
if($time_pm_in && $time_pm_out){
  $params['pm_in'] = $time_pm_in; $params['pm_out'] = $time_pm_out;
  // overlap with existing PM
  $overlapConds[] = "NOT (time_pm_out <= :pm_in OR time_pm_in >= :pm_out)";
  // overlap with existing AM
  $overlapConds[] = "NOT (time_am_out <= :pm_in OR time_am_in >= :pm_out)";
}

if(count($overlapConds) > 0){
  $confSql = "SELECT COUNT(*) FROM web_logs WHERE employee_id = :emp AND date = :date AND (" . implode(' OR ', $overlapConds) . ")";
  $stmt = $pdo->prepare($confSql);
  $stmt->execute($params);
  $count = $stmt->fetchColumn();
  if($count > 0){ echo json_encode(['success'=>false,'message'=>'Overlapping or duplicate entry detected']); exit; }
}

$status = 'Pending';
// auto-approve if user is Supervisor or HR
if(isset($_SESSION['access_level']) && preg_match('/Supervisor|HR|Admin|Department/i', $_SESSION['access_level'])){ $status = 'Approved'; }

$insert = "INSERT INTO web_logs (employee_id, date, time_am_in, time_am_out, time_pm_in, time_pm_out, remarks, status, created_by) VALUES (:emp,:date,:am_in,:am_out,:pm_in,:pm_out,:remarks,:status,:created_by)";
$i = $pdo->prepare($insert);
$ok = $i->execute([
  'emp'=>$employee_id,
  'date'=>$date,
  'am_in'=>$time_am_in,
  'am_out'=>$time_am_out,
  'pm_in'=>$time_pm_in,
  'pm_out'=>$time_pm_out,
  'remarks'=>$remarks,
  'status'=>$status,
  'created_by'=>isset($_SESSION['username'])?$_SESSION['username']:'system'
]);

if($ok) echo json_encode(['success'=>true]); else echo json_encode(['success'=>false,'message'=>'Database insert failed']);
