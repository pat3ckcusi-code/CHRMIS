<?php
session_start();
require_once('../includes/db_config.php');
require_once('../includes/session_config.php');
require_once('../includes/functions/func_login.php');

header('Content-type: application/json');

$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : null;

if (!$username || !$password) {
    echo json_encode([
        'success' => false,
        'message' => 'Username and password are required.'
    ]);
    exit;
}

// Clear any leftover temp session if this is not a password change flow
if (!isset($_POST['isPasswordChange'])) {
    unset($_SESSION['TempUser'], $_SESSION['TempUserType'], $_SESSION['TempAccess']);
}

try {
    $loginResult = verify_login_admin($pdo, $username, $password);

    if ($loginResult['success']) {

        // Check if using default password
        if ($loginResult['isDefaultPassword']) {
            // Store temporary session for modal workflow
            $_SESSION['TempUser'] = $loginResult['username'];
            $_SESSION['TempUserType'] = $loginResult['userType'];
            $_SESSION['TempAccess'] = $loginResult['access'];
            
            // Store the actual username/ID for the modal
            $userIdentifier = ($loginResult['userType'] === 'i') ? 'EmpNo' : 'UserName';
            $_SESSION['TempUserIdentifier'] = $loginResult[$userIdentifier] ?? $username;

            echo json_encode([
                'success'   => true,
                'message'   => 'You are using a default password. Please change it.',
                'showModal' => true,
                'username'  => $loginResult['username'],
                'userType'  => $loginResult['userType']
            ]);
            exit;
        }

        // Normal login → store permanent session variables
        $_SESSION['User']     = $username;
        $_SESSION['UserName'] = $loginResult['UserName'] ?? $loginResult['username'];
        $_SESSION['EmpName']  = $loginResult['name'] ?? null;
        $_SESSION['Dept']     = $loginResult['Dept'] ?? null;
        $_SESSION['EmpID']    = $loginResult['EmpNo'] ?? null;
        $_SESSION['Access']   = $loginResult['access'];
        $_SESSION['access_level']   = $loginResult['access_level'] ?? null;
        $_SESSION['Status']   = $loginResult['Status'] ?? null;
        $_SESSION['UserType'] = $loginResult['userType'] ?? null;

        // Determine redirect
        $redirect = '../pages/Login.php'; // fallback
        if ($_SESSION['Access'] === 'Admin' && $_SESSION['Status'] === 'FOR APPROVAL') {
            $redirect = 'Workforce.php';
        } elseif ($_SESSION['Access'] === 'AO' && $_SESSION['Status'] === 'FOR RECOMMENDATION') {
            $redirect = 'AO.php';
        } elseif ($_SESSION['Access'] === 'User') {
            $redirect = '../pages/Profile.php';
        } elseif ($_SESSION['Status'] === 'FOR Encoder') {
            $redirect = 'LeaveApp.php';
        }elseif ($_SESSION['Status'] === 'Frontline') {
            $redirect = 'front_office_clerk.php';
        }

        echo json_encode([
            'success'  => true,
            'message'  => "Welcome, " . ($_SESSION["EmpName"] ?? $loginResult['username']) . "!",
            'redirect' => $redirect
        ]);

    } else {
        echo json_encode([
            'success' => false,
            'message' => $loginResult['message'] ?? 'Invalid credentials'
        ]);
    }

} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again later.'
    ]);
} catch (Exception $e) {
    error_log("System Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'System error occurred. Please try again later.'
    ]);
}

exit;
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('#formLogin').submit(function(e) {
    e.preventDefault(); // prevent normal form submission

    const submitBtn = $(this).find('[type="submit"]');
    const originalBtnText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Logging in...');

    $.post('../includes/process_login.php', $(this).serialize(), function(data) {
        console.log('Login response:', data);

        if (data.success) {

            // Default password detected → show modal
            if (data.showModal) {
                $('#modalContainer').load('../partials/modals/modalpassword.php', function() {
                    $('#modalPassword').modal('show');
                });
            } 
            // Normal login → redirect
            else if (data.redirect) {
                window.location.href = data.redirect;
            }

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: data.message
            });
        }

    }, 'json')
    .fail(function() {
        Swal.fire('Error', 'Failed to connect to server. Please try again.', 'error');
    })
    .always(function() {
        submitBtn.prop('disabled', false).html(originalBtnText);
    });
});
</script>


