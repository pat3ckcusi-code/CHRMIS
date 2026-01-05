 <?php
require_once('../includes/sessions.php');
require_once('../includes/db_config.php');
require_once('../includes/functions/func_dept.php');

header('Content-type: application/json');


$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
  case 'GET':
    if (isset($_GET['DeptList'])) {
          try {
              $data = get_all_DeptList($pdo);
              echo json_encode(['data' => $data], JSON_PRETTY_PRINT);
          } catch (Exception $e) {
              echo json_encode(['data' => [], 'error' => $e->getMessage()]);
          }
          exit;
        } else if (isset($_GET['EditDept']) && !empty($_GET['id'])) {
                $dept_id = intval($_GET['id']);

                
                $dept = get_dept_by_id($pdo, $dept_id);

                if ($dept) {
                    echo json_encode($dept); 
                } else {
                    echo json_encode(['error' => 'Department not found']);
                }
                exit;
            }
    


  break;

  case 'PUT':
    
  break;

  case 'POST':
  if (isset($_GET['add_deptinfo'])) {
      echo json_encode(add_deptinfo($pdo, $_POST));
      exit;
    }elseif (!empty($_POST['deptId'])) {
    $stmt = $pdo->prepare("UPDATE departments 
                           SET Dept_name = ?, Department_head = ?, Designation = ? 
                           WHERE Dept_id = ?");

          $stmt->execute([
              $_POST['txtDeptName'],     
              $_POST['txtDeptHead'],     
              $_POST['txtDesignation'],  
              $_POST['deptId']           
          ]);

          echo json_encode([
              "success" => true,
              "message" => ($stmt->rowCount() > 0) 
                          ? "Department updated successfully" 
                          : "No changes made"
          ]);
      } else {
          echo json_encode(["success" => false, "error" => "Missing parameters"]);
      }
      exit;

  break;

  case 'DELETE':
    
  break;
}
