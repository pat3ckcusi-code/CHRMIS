<?php
session_start();
require_once(__DIR__ . '/../includes/initialize.php');
header('Content-Type: application/json');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$employee_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$time_am_in = isset($_POST['time_am_in']) && $_POST['time_am_in'] !== '' ? trim($_POST['time_am_in']) : null;
$time_am_out = isset($_POST['time_am_out']) && $_POST['time_am_out'] !== '' ? trim($_POST['time_am_out']) : null;
$time_pm_in = isset($_POST['time_pm_in']) && $_POST['time_pm_in'] !== '' ? trim($_POST['time_pm_in']) : null;
$time_pm_out = isset($_POST['time_pm_out']) && $_POST['time_pm_out'] !== '' ? trim($_POST['time_pm_out']) : null;
$remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

if(!$id || !$employee_id || !$date){ echo json_encode(['success'=>false,'message'=>'Missing required fields']); exit; }

// validate pairs
if(!(($time_am_in && $time_am_out) || ($time_pm_in && $time_pm_out))){ echo json_encode(['success'=>false,'message'=>'Provide at least AM or PM Time In/Out pair']); exit; }
if($time_am_in && $time_am_out && $time_am_out <= $time_am_in){ echo json_encode(['success'=>false,'message'=>'AM Time Out must be later than AM Time In']); exit; }
if($time_pm_in && $time_pm_out && $time_pm_out <= $time_pm_in){ echo json_encode(['success'=>false,'message'=>'PM Time Out must be later than PM Time In']); exit; }
if($time_am_in && $time_am_out && $time_pm_in && $time_pm_out && $time_am_out > $time_pm_in){ echo json_encode(['success'=>false,'message'=>'AM Time Out must be earlier than or equal to PM Time In']); exit; }

// fetch existing row
$stmt = $pdo->prepare("SELECT * FROM web_logs WHERE id = :id LIMIT 1");
$stmt->execute(['id'=>$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$row){ echo json_encode(['success'=>false,'message'=>'Entry not found']); exit; }

// permission: allow if creator or Supervisor/HR/Admin
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$access = isset($_SESSION['access_level']) ? $_SESSION['access_level'] : '';
$isPriv = preg_match('/Supervisor|HR|Admin|Department/i', $access);
if(!$isPriv && $row['created_by'] !== $username){ echo json_encode(['success'=>false,'message'=>'Unauthorized to edit this entry']); exit; }

// ensure web_logs table has edited_by/edited_at columns
$colCheck = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'web_logs' AND COLUMN_NAME = 'edited_by'");
$colCheck->execute();
if($colCheck->fetchColumn() == 0){
    $pdo->exec("ALTER TABLE web_logs ADD COLUMN edited_by VARCHAR(100) NULL, ADD COLUMN edited_at DATETIME NULL");
}

// overlap checks excluding current id
$overlapConds = [];
$params = ['emp'=>$employee_id, 'date'=>$date, 'id'=>$id];
if($time_am_in && $time_am_out){
  $params['am_in'] = $time_am_in; $params['am_out'] = $time_am_out;
  $overlapConds[] = "NOT (time_am_out <= :am_in OR time_am_in >= :am_out)";
  $overlapConds[] = "NOT (time_pm_out <= :am_in OR time_pm_in >= :am_out)";
}
if($time_pm_in && $time_pm_out){
  $params['pm_in'] = $time_pm_in; $params['pm_out'] = $time_pm_out;
  $overlapConds[] = "NOT (time_pm_out <= :pm_in OR time_pm_in >= :pm_out)";
  $overlapConds[] = "NOT (time_am_out <= :pm_in OR time_am_in >= :pm_out)";
}
if(count($overlapConds) > 0){
  $confSql = "SELECT COUNT(*) FROM web_logs WHERE employee_id = :emp AND date = :date AND id != :id AND (" . implode(' OR ', $overlapConds) . ")";
  $stmt = $pdo->prepare($confSql);
  $stmt->execute($params);
  $count = $stmt->fetchColumn();
  if($count > 0){ echo json_encode(['success'=>false,'message'=>'Overlapping or duplicate entry detected']); exit; }
}

// determine status after edit
$newStatus = $isPriv ? 'Approved' : 'Pending Correction';

$upd = $pdo->prepare("UPDATE web_logs SET employee_id = :emp, date = :date, time_am_in = :am_in, time_am_out = :am_out, time_pm_in = :pm_in, time_pm_out = :pm_out, remarks = :remarks, status = :status, edited_by = :editor, edited_at = NOW()" . ($isPriv ? ", approved_by = :editor, approved_at = NOW()" : ", approved_by = NULL, approved_at = NULL") . " WHERE id = :id");
$ok = $upd->execute([
  'emp'=>$employee_id,
  'date'=>$date,
  'am_in'=>$time_am_in,
  'am_out'=>$time_am_out,
  'pm_in'=>$time_pm_in,
  'pm_out'=>$time_pm_out,
  'remarks'=>$remarks,
  'status'=>$newStatus,
  'editor'=>$username ?: 'system',
  'id'=>$id
]);

if($ok) echo json_encode(['success'=>true]); else echo json_encode(['success'=>false,'message'=>'Failed to update entry']);
