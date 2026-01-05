<?php
session_start();
require_once('../includes/initialize.php');

if(!isset($_SESSION['EmpID'])){
    echo json_encode(['status'=>'error','message'=>'Invalid employee number.']);
    exit;
}

$employee_id = $_SESSION['EmpID'];
$docType = $_POST['docType'] ?? null;
$purpose = trim($_POST['purpose'] ?? '');

if(!$docType || !$purpose){
    echo json_encode(['status'=>'error','message'=>'Document type and purpose are required.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO document_requests (EmpNo, document_type, purpose, status, requested_on)
                           VALUES (:employee_id, :docType, :remarks, 'Requested', NOW())");
    $stmt->execute([
        ':employee_id' => $employee_id,
        ':docType' => $docType,
        ':remarks' => $purpose
    ]);
    echo json_encode(['status'=>'success','message'=>'Request submitted successfully.']);
} catch(PDOException $e){
    echo json_encode(['status'=>'error','message'=>'Failed to submit request. PDO Error: '.$e->getMessage()]);
}
