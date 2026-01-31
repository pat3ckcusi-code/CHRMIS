<?php
session_start();
require_once(__DIR__ . '/../includes/initialize.php');
header('Content-Type: application/json');

if(!isset($_SESSION['access_level']) || !preg_match('/Supervisor|HR|Admin|Department/i', $_SESSION['access_level'])){
  echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if(!$id){ echo json_encode(['success'=>false,'message'=>'Invalid id']); exit; }

$upd = $pdo->prepare("UPDATE web_logs SET status='Approved', approved_by = :approver, approved_at = NOW() WHERE id = :id");
$ok = $upd->execute(['approver'=>isset($_SESSION['username'])?$_SESSION['username']:'system','id'=>$id]);

if($ok) echo json_encode(['success'=>true]); else echo json_encode(['success'=>false,'message'=>'Failed to update']);
