<?php
function verify_login_admin($pdo, $username, $password) {
    date_default_timezone_set('Asia/Manila');
    $response = ['success' => false, 'message' => 'Invalid credentials'];

    try {
        // Check adminusers table (Admin / AO)        
        $adminquery = $pdo->prepare("SELECT * FROM adminusers WHERE UserName = :username");
        $adminquery->execute(['username' => $username]);
        $user = $adminquery->fetch();

        if ($user) {
            $stored   = $user["Password"];
            $isBcrypt = (strlen($stored) >= 60 && substr($stored, 0, 2) === '$2');
            $valid = $isBcrypt ? password_verify($password, $stored) : hash_equals($stored, $password);

            if ($valid) {
                // Check if password is default - compare with plain text "password"
                $isDefault = ($password === 'password');

                // Determine access based on Status
                $status = $user['Status']; // FOR APPROVAL or FOR RECOMMENDATION
                $access = ($status === "FOR APPROVAL") ? 'Admin' : 'AO';

                // Optional HR logic: only for Admins
                if ($access === "Admin" && $status === "FOR APPROVAL") {
                    $monthYear = date('F Y');
                    $d = date('d');

                    $checkquery = $pdo->prepare("SELECT * FROM monthlyadd WHERE MonthYear = :monthYear");
                    $checkquery->execute(['monthYear' => $monthYear]);

                    if ($checkquery->rowCount() == 0 && $d > 25) {
                        $pdo->query("UPDATE leavecredits SET VL = VL + 1.25, SL = SL + 1.25");
                        $adddate = $pdo->prepare("INSERT INTO monthlyadd (MonthYear) VALUES (:monthYear)");
                        $adddate->execute(['monthYear' => $monthYear]);
                    }
                }

                return [
                    'success' => true,
                    'access'  => $access,
                    'name'    => $user["AcctName"],
                    'Dept'    => $user["Dept"],
                    'Status'  => $status,
                    'EmpNo'   => $user["EmpNo"],
                    'UserName'=> $user["UserName"],
                    'username'=> $user["UserName"],
                    'access_level' => $user["access_level"],
                    'isDefaultPassword' => $isDefault,
                    'userType' => 'adminusers'
                ];
            }
        }

        // Check regular employee in i table       
        $query = $pdo->prepare("SELECT EmpNo, Password, CONCAT(Fname, ' ', Lname) AS Name 
                                FROM i WHERE EmpNo = :username");
        $query->execute(['username' => $username]);
        $emp = $query->fetch();

        if ($emp) {
            $stored   = $emp["Password"];
            $isBcrypt = (strlen($stored) >= 60 && substr($stored, 0, 2) === '$2');
            $valid = $isBcrypt ? password_verify($password, $stored) : hash_equals($stored, $password);

            if ($valid) {
                // Check if password is default - compare with plain text "password"
                $isDefault = ($password === 'password');

                return [
                    'success' => true,
                    'access'  => 'User',
                    'name'    => $emp["Name"],
                    'EmpNo'   => $emp["EmpNo"],
                    'username'=> $emp["EmpNo"],
                    'Status'  => null, 
                    'Dept'    => null,
                    'isDefaultPassword' => $isDefault,
                    'userType' => 'i'
                ];
            }
        }

    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        $response['message'] = 'Database error occurred';
        return $response;
    }

    return $response;
}
?>