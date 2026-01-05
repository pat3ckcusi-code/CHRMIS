<?php
function get_user_by_id($pdo, $user_id) {
  $query = "SELECT * FROM i WHERE EmpNo = '".$user_id."'";
  $statement = $pdo->query($query);
  return $statement->fetch();
}
//alert($('#a_user_id_no').val());
function get_user_result($pdo, $access_level) {
    if ($access_level == 'RO') {
    $query = "SELECT * FROM tbl_checklist"; 
  } else
    {
    $query = "SELECT * FROM tbl_checklist WHERE Office = '".$access_level."'";
    }    
    $statement = $pdo->query($query);
    return $statement->fetchAll();
}

//Employee List
function get_all_EmpList($pdo) {
    $query ="SELECT 
    i.EmpNo,
    CONCAT(
        i.Fname, ' ', 
        i.Mname, ' ', 
        i.Lname, 
        CASE WHEN i.Extension = 'N/A' THEN '' ELSE CONCAT(' ', i.Extension) END
    ) AS Name,                  
    i.Dept, i.date_hired,
    MAX(l.Position) AS Position
    FROM i
    LEFT JOIN v l ON i.EmpNo = l.EmpNo
    GROUP BY i.EmpNo, i.Fname, i.Mname, i.Lname, i.Lname, i.Dept;";
    $statement = $pdo->query($query);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    // debug
    if (!$rows) {
        error_log("No employees found.");
    }

    return $rows;
}
//Recommendation List
function get_RecomList($pdo) {
    $query = "SELECT * 
              FROM adminusers 
              WHERE Status = 'For Recommendation'";
    
    try {
        $statement = $pdo->query($query);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        // debug
        if (!$rows) {
            error_log("No employees found.");
        }

        return $rows;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}
function get_employee_by_id($pdo, $empId) {
    $query = "SELECT 
            i.EmpNo,
            i.Lname,
            i.Fname,
            i.Mname,
            i.Extension,
            i.Gender,
            i.Civil,
            i.BirthDate,
            i.Dept,
            v.Position
        FROM i AS i
        LEFT JOIN v AS v ON i.EmpNo = v.EmpNo
        WHERE i.EmpNo = ?";

    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$empId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}





function add_user($pdo, $data) {
  extract($data);
  $query = "INSERT INTO tbl_checklist ";
  $query .= "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $pdo->beginTransaction();
  //try {
    $statement = $pdo->prepare($query);
    $statement->execute([ '', $user_id_no, $user_name, $user_sex, $user_age, $user_office, $user_date, $user_temp, $item1, $item2, $item3, $item4, $item5, $item6, $item7 ]);
    $insert_id = $pdo->lastInsertId();       
    $pdo->commit();
    $resp = [
      'success' => true,
      'status' => 'success',
      'message' => 'Employee Record was successfully created!',
      'insert_id' => $insert_id
      ];   
  // } catch (\Exception $e) {
  //   $resp = [
  //     'success' => false,
  //     'status' => 'failed',
  //     'message' => 'Duplicate entry!',
  //   ];
  //   $pdo->rollBack();
  // }
  // return $resp;
}

// Add new employee + initialize leave credits
function add_userinfo($pdo, $post) {
    error_log("POST DATA: " . print_r($post, true));

    // 1) Map FORM NAMES to variables (MUST match modal form)
    $EmpNo      = trim($post['txtEmpNum']   ?? '');
    $EmpStatus  = trim($post['txtEmpStatus']   ?? '');
    $date_hired  = trim($post['txtDateHired']   ?? '');
    $Lname      = trim($post['txtLname']    ?? '');
    $Fname      = trim($post['txtFname']    ?? '');
    $Mname      = trim($post['txtMname']    ?? ''); 
    $Extension  = trim($post['txtExt']      ?? '');
    $rawDate    = trim($post['txtHRDate']   ?? '');  
    $Gender     = trim($post['rdSex']       ?? '');
    $Civil      = trim($post['rdHRCivil']   ?? 'Single'); 
    $Dept       = trim($post['ddDept']      ?? '');

    // 2) Validation
    if ($EmpNo === '' || $Lname === '' || $Fname === '' || $Dept === '') {
        return [
            'success' => false,
            'status'  => 'failed',
            'message' => 'Missing required fields (EmpNo, Lname, Fname, Dept).'
        ];
    }

    // 3) Date formatting
    $BirthDate = null;
    if ($rawDate !== '') {
        $ts = strtotime($rawDate);
        if ($ts === false) {
            return [
                'success' => false,
                'status'  => 'failed',
                'message' => 'Invalid BirthDate format.'
            ];
        }
        $BirthDate = date('Y-m-d', $ts);
    }

    try {
        // 4) Duplicate checks
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM i WHERE EmpNo = ?");
        $stmt->execute([$EmpNo]);
        if ((int)$stmt->fetchColumn() > 0) {
            return [
                'success' => false,
                'status'  => 'failed',
                'message' => 'Employee already exists (EmpNo duplicate).'
            ];
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM leavecredits WHERE EmpNo = ?");
        $stmt->execute([$EmpNo]);
        if ((int)$stmt->fetchColumn() > 0) {
            return [
                'success' => false,
                'status'  => 'failed',
                'message' => 'Leave credits already exist for this EmpNo.'
            ];
        }

        // 5) Transaction: insert employee + leavecredits
        $pdo->beginTransaction();

        $sqlEmp = "INSERT INTO i 
                   (EmpNo, Lname, Fname, Mname, Extension, BirthDate, Gender, Civil, Password, Dept, EmploymentStatus, date_hired)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtEmp = $pdo->prepare($sqlEmp);
        $stmtEmp->execute([
            $EmpNo,
            $Lname,
            $Fname,
            $Mname,
            $Extension,
            $BirthDate,
            $Gender,
            $Civil,
            'password',   // default password
            $Dept,
            $EmpStatus,
            $date_hired
        ]);

        $sqlCredits = "INSERT INTO leavecredits (EmpNo, VL, SL, CL, SPL, CTO)
                       VALUES (?, 0, 0, 0, 0, 0)";
        $stmtCred = $pdo->prepare($sqlCredits);
        $stmtCred->execute([$EmpNo]);

        $pdo->commit();

        return [
            'success'   => true,
            'status'    => 'success',
            'message'   => 'Employee and leave credits created successfully.',
            'insert_id' => $EmpNo
        ];
    } catch (\PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        if ($e->getCode() === '23000') {
            return [
                'success' => false,
                'status'  => 'failed',
                'message' => 'Duplicate entry detected by the database.'
            ];
        }
        return [
            'success' => false,
            'status'  => 'failed',
            'message' => 'DB Error: ' . $e->getMessage()
        ];
    }
}

//update employee department
function update_employee_dept($pdo, $empNo, $dept) {
    try {
        $stmt = $pdo->prepare("UPDATE i SET Dept = ? WHERE EmpNo = ?");
        $stmt->execute([$dept, $empNo]);

        if ($stmt->rowCount() > 0) {
            return ["success" => true, "message" => "Department updated successfully"];
        } else {
            return ["success" => false, "message" => "No changes made"];
        }
    } catch (PDOException $e) {
        return ["success" => false, "error" => $e->getMessage()];
    }
}




function delete_user($pdo, $user_id){
  $query = "DELETE FROM tbl_users ";
  $query .= "WHERE user_id = ? ";

  $pdo->beginTransaction();
  try{
    $statement = $pdo->prepare($query);
    $statement->execute([ $user_id ]);

    $resp = [
      'success' => true,
      'status' => 'success',
      'message' => 'User Record was successfully deleted!.'
    ];

    $pdo->commit();
  }catch(\Exception $e){
    $resp = [
      'success' => false,
      'status' => 'failed',
      'message' => $e->getMessage()
    ];
    $pdo->rollback();
  }
  return $resp;
}


?>
