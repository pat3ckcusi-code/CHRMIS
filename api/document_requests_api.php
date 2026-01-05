<?php
session_start();
require_once('../includes/initialize.php');
require_once('HRdocument/document_request_model.php');

header('Content-Type: application/json');

if (!isset($_SESSION['EmpID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Instantiate model
$model = new DocumentRequestModel($pdo);

$action = $_REQUEST['action'] ?? '';

switch($action) {

    // List all requests
    case 'list':
        $requests = $model->getAllRequests();
        echo json_encode(['status' => 'success', 'data' => $requests]);
        break;


    // Get request by ID
    case 'get':
        $id = $_GET['id'] ?? 0;
        $request = $model->getRequestById($id);
        if($request) {
            echo json_encode(['status' => 'success', 'data' => $request]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Request not found']);
        }
        break;

    // Update request status (Approve, Reject, Complete)
    case 'update_status':
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? '';
        $hrNoteInput = $_POST['note'] ?? null;

        if (!$id || !$status) {
            echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
            exit;
        }

        // Determine HR note
        if ($status === 'Pending') {
            $note = "Your document request is being processed.";
        } elseif ($status === 'Completed') {
            $note = "Your document request has been completed and is ready for pick-up at the HR office.";
        } elseif ($status === 'Rejected') {
            if (!$hrNoteInput) {
                echo json_encode(['status' => 'error', 'message' => 'HR note is required for rejection']);
                exit;
            }
            $note = $hrNoteInput;
        } else {
            $note = null;
        }

        // Fetch employee info first
        $employee = $model->getRequestWithEmployeeEmail($id);

        if (!$employee || empty($employee['EMail'])) {
            echo json_encode(['status' => 'error', 'message' => 'Employee email not found. Database not updated.']);
            exit;
        }

        require_once '../includes/send_email.php';
        $fullName = $employee['Fname'] . ' ' . $employee['Lname'];

        // Prepare email content
        $subject = $body = '';
        switch ($status) {
            case 'Pending':
                $subject = 'Your Document Request is Being Processed';
                $body = "<p>Dear {$fullName},</p>
                        <p>Your requested document <strong>{$employee['document_type']}</strong> has been approved and is now being processed.</p>
                        <p>We will notify you once it is ready.</p>
                        <br><p>City Human Resource Office Department</p>";
                break;
            case 'Rejected':
                $subject = 'Your Document Request Has Been Rejected';
                $body = "<p>Dear {$fullName},</p>
                        <p>Your requested document <strong>{$employee['document_type']}</strong> has been rejected.</p>
                        <p><strong>Reason:</strong> {$note}</p>
                        <br><p>City Human Resource Office Department</p>";
                break;
            case 'Completed':
                $subject = 'Your Document Request is Ready for Pick-Up';
                $body = "<p>Dear {$fullName},</p>
                        <p>Your requested document <strong>{$employee['document_type']}</strong> has been completed and is ready for pick-up at the HR office.</p>
                        <br><p>City Human Resource Office Department</p>";
                break;
        }

        // Send email first
        $emailSent = sendDocumentReadyEmail(
            $employee['EMail'],
            $fullName,
            $employee['document_type'],
            $subject,
            $body
        );

        if (!$emailSent) {
            // Email failed → SweetAlert on frontend
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to send email. Please check SMTP settings.'
            ]);
            exit;
        }

        // Email succeeded → update DB
        $updated = $model->updateStatus($id, $status, $note);

        if ($updated) {
            // Log action
            $logAction = ($status === 'Pending') ? 'Approved' : $status;
            $model->logRequestAction($id, $_SESSION['EmpID'], $logAction, $note);

            echo json_encode([
                'status' => 'success',
                'message' => 'Email sent and request updated successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email sent but failed to update database.'
            ]);
        }
        break;

    // Create a new request
    case 'create':
        $documentType = $_POST['docType'] ?? '';
        $purpose = $_POST['purpose'] ?? '';
        $empNo = $_SESSION['EmpID'];

        if (!$documentType || !$purpose) {
            echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
            exit;
        }

        if ($model->createRequest($empNo, $documentType, $purpose)) {
            // Get last inserted ID
            $lastId = $pdo->lastInsertId();

            // Log creation
            $model->logRequestAction($lastId, $empNo, 'Requested', 'New document request submitted');

            echo json_encode(['status' => 'success', 'message' => 'Request submitted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit request']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
