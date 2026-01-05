<?php
include '../includes/db_config.php';

if (!isset($_POST['id']) || !isset($_POST['arrivalTime']) || !isset($_POST['empNo'])) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$id = $_POST['id'];
$arrivalTime = $_POST['arrivalTime'];  
$empNo = $_POST['empNo'];

$dateNow = date("Y-m-d");
$fullTime = $dateNow . " " . $arrivalTime . ":00";

try {

    $query = "UPDATE eta_locator
              SET Arrival_Time = :arrival
              WHERE id = :id AND EmpNo = :empNo";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":arrival", $fullTime);
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":empNo", $empNo);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Arrival updated successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database update failed."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
