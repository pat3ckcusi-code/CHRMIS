<?php
function get_user_password_by_user_id($pdo, $user_id) {
  $query = "SELECT user_id, password FROM tbl_users ";
  $query .= "WHERE user_id = ? ";
  $statement = $pdo->prepare($query);
  $statement->execute([ $user_id ]);
  return $statement->fetchObject();
}

function edit_user_password($pdo, $data) {
    extract($data);  

    $newPassword = "password";
    $query = "UPDATE i SET password = ? WHERE EmpNo = ?";

    $pdo->beginTransaction();
    try {
        $statement = $pdo->prepare($query);
        $statement->execute([$newPassword, $user_id]);

        $response = [
            'success' => true,
            'status' => 'success',
            'message' => 'Password was successfully reset!',
        ];
        $pdo->commit();
    } catch (\Exception $e) {
        $response = [
            'success' => false,
            'status' => 'failed',
            'message' => $e->getMessage(),
        ];
        $pdo->rollBack();
    }
    return $response;
}

?>
