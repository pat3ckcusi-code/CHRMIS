<?php 

function get_all_DeptList($pdo) {
    $query ="SELECT * FROM departments";
    $statement = $pdo->query($query);
    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    // debug
    if (!$rows) {
        error_log("No department on the list.");
    }

    return $rows;
}

function add_deptinfo($pdo, $post) {
    error_log("POST DATA: " . print_r($post, true));

    
    $DeptName     = trim($post['txtDeptName']   ?? '');
    $DeptHead     = trim($post['txtDeptHead']   ?? '');
    $Designation  = trim($post['txtDesignation']?? '');

   
    if ($DeptName === '' || $DeptHead === '' || $Designation === '') {
        return [
            'success' => false,
            'status'  => 'failed',
            'message' => 'Missing required fields.'
        ];
    }

    try {        
        $pdo->beginTransaction();

        $sql = "INSERT INTO departments (Dept_name, Department_head, Designation)
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $DeptName,
            $DeptHead,
            $Designation
        ]);

     
        $pdo->commit();

        return [
            'success' => true,
            'status'  => 'success',
            'message' => 'Department added successfully.'
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

function get_dept_by_id($pdo, $dept_id) {
    $stmt = $pdo->prepare("SELECT * FROM departments WHERE Dept_id = ?");
    $stmt->execute([$dept_id]);
    $dept = $stmt->fetch(PDO::FETCH_ASSOC);

    return $dept ?: null;
}


?>
