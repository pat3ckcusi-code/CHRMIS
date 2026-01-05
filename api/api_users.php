 <?php
require_once('../includes/sessions.php');
require_once('../includes/db_config.php');
require_once('../includes/functions/func_users.php');

header('Content-type: application/json');


$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
  case 'GET':
    if (isset($_GET['user_id'])) {
      $user_id = urldecode($_GET['user_id']);
      echo json_encode([ 'data' => get_user_by_id($pdo, $user_id) ]);
      exit;    
    }else if (isset($_GET['res'])) {
      echo json_encode([ 'data' => get_user_result($pdo, $_GET['a']) ]);
      exit;
    }else if (isset($_GET['EmpList'])) {
          try {
              $data = get_all_EmpList($pdo);
              echo json_encode(['data' => $data], JSON_PRETTY_PRINT);
          } catch (Exception $e) {
              echo json_encode(['data' => [], 'error' => $e->getMessage()]);
          }
          exit;
    }else if (isset($_GET['get_employee']) && isset($_GET['id'])) {
         $empId = $_GET['id'];
        $employee = get_employee_by_id($pdo, $empId);

        if ($employee) {
            echo json_encode($employee);
        } else {
            echo json_encode(["error" => "Employee not found"]);
        }
        exit;
    }else if (isset($_GET['RecomList'])) {
      echo json_encode([ 'data' => get_RecomList($pdo)]);
      exit;
    }
  break;

  case 'PUT':
    if (isset($_GET['user_edit'])) {
      $user_id = urldecode($_GET['user_edit']);
      echo json_encode([ 'data' => get_user_by_id($pdo, $user_id) ]);
      exit;    
    }
  break;

  case 'POST':
  if (isset($_GET['add_userinfo'])) {
      echo json_encode(add_userinfo($pdo, $_POST));
      exit;
    }else if (isset($_GET['update_department'])) {
          if (!empty($_POST['empNo']) && !empty($_POST['dept'])) {
              $stmt = $pdo->prepare("UPDATE i SET Dept = ? WHERE EmpNo = ?");
              $stmt->execute([$_POST['dept'], $_POST['empNo']]);

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
      }
      break;

  case 'DELETE':
    parse_str(file_get_contents('php://input'), $data);
    extract($data);
    echo json_encode(delete_user($pdo, $user_id));
  break;
}
