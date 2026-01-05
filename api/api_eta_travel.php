<?php
require_once('../includes/db_config.php');
require_once('../includes/functions/func_eta_travel.php');

session_start();

// Make sure user is logged in
if(!isset($_SESSION['EmpID'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$empNo = $_SESSION['EmpID'];

// 
// Cancel ETA / Locator
// 
if (isset($_GET['cancel_eta_locator'])) {
    $id = $_POST['id'] ?? null;
    if ($id) {
        $sql = "UPDATE eta_locator 
                SET status = 'Cancelled', last_updated = NOW() 
                WHERE id = :id AND status = 'Pending'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Application has been cancelled.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unable to cancel. It may already be approved or cancelled.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
    }
    exit;
}


// Add / Submit ETA / Locator
if (isset($_GET['add_eta_locator'])) {
    // Get POST data safely
    $data = $_POST;

    // Validate and map destination/location
    if(!empty($data['applicationType'])) {
        if($data['applicationType'] === 'ETA') {
            $data['destination'] = trim($data['etaDestination'] ?? '');
        } elseif($data['applicationType'] === 'Locator') {
            $data['destination'] = trim($data['locatorLocation'] ?? '');
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid application type.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please select an application type.']);
        exit;
    }

    // Map other optional fields
    $data['EmpNo'] = $empNo;
    $data['businessPurpose'] = $data['businessPurpose'] ?? '';
    $data['otherPurpose'] = $data['otherPurpose'] ?? '';
    $data['travelDetail'] = $data['travelDetail'] ?? '';
    $data['intended_departure'] = $data['intended_departure'] ?? null;
    $data['intended_arrival']   = $data['intended_arrival'] ?? null;
    $data['travelDate']          = $data['travelDate'] ?? null;
    $data['arrivalDate']          = $data['arrivalDate'] ?? null;
    // Call function to insert record
    $response = addEtaLocator($pdo, $data);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If no valid action
echo json_encode(['success' => false, 'message' => 'No action specified.']);
exit;
