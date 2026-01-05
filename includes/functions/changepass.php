<?php
session_start();
require_once('../db_config.php');

header('Content-Type: application/json');

// Validate request
if (!isset($_POST['newpass'], $_POST['confirmpass'])) {
    echo json_encode([
        "success" => false,
        "title"   => "Error",
        "message" => "Invalid request.",
        "icon"    => "error"
    ]);
    exit;
}

$newpass     = $_POST['newpass'];
$confirmpass = $_POST['confirmpass'];

// Check confirm password
if ($newpass !== $confirmpass) {
    echo json_encode([
        "success" => false,
        "title"   => "Mismatch!",
        "message" => "New password and confirm password do not match.",
        "icon"    => "error"
    ]);
    exit;
}

// Check password strength
if (strlen($newpass) < 8) {
    echo json_encode([
        "success" => false,
        "title"   => "Weak Password",
        "message" => "Password must be at least 8 characters long.",
        "icon"    => "error"
    ]);
    exit;
}

try {
    // Check if we have temp session data
    if (empty($_SESSION['TempUser']) || empty($_SESSION['TempUserType'])) {
        echo json_encode([
            "success" => false,
            "title"   => "Session Expired",
            "message" => "Your session has expired. Please login again.",
            "icon"    => "error"
        ]);
        exit;
    }

    $table = $_SESSION['TempUserType']; // "i" or "adminusers"
    $identifier = ($table === 'i') ? 'EmpNo' : 'UserName';
    $userValue = $_SESSION['TempUser'];

    // Hash the new password
    $hashedPass = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 12]);

    // Update DB
    $update = $pdo->prepare("UPDATE {$table} SET Password = ? WHERE {$identifier} = ?");
    $success = $update->execute([$hashedPass, $userValue]);

    if ($success && $update->rowCount() > 0) {
        // Store username before clearing session
        $username = $_SESSION['TempUser'];
        
        // Clear ALL sessions to force fresh login
        session_unset();
        session_destroy();
        
        // IMPORTANT: Don't start a new session here
        // Let the login page create a fresh session

        echo json_encode([
            "success" => true,
            "title"   => "Success!",
            "message" => "Password changed successfully.",
            "icon"    => "success",
            "username" => $username  // Return the username
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "title"   => "Error",
            "message" => "Failed to update password. Please try again.",
            "icon"    => "error"
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "title"   => "Error",
        "message" => "Server error: " . $e->getMessage(),
        "icon"    => "error"
    ]);
}
?>