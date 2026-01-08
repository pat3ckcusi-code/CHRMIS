 <?php
require_once('../includes/sessions.php');
require_once('../includes/db_config.php');
require_once('../includes/functions/func_leave.php');
require_once('../includes/send_email.php');

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
    }else if (isset($_GET['leaveapplication_table'])) {
        $startDate = $_REQUEST['startDate'] ?? null;
        $endDate = $_REQUEST['endDate'] ?? null;
        echo json_encode([
            'data' => get_all_leaveapp($pdo, $startDate, $endDate)
        ]);
        exit;
    }else if (isset($_GET['ForRecom'])) {
    $startDate = $_REQUEST['startDate'] ?? null;
    $endDate   = $_REQUEST['endDate'] ?? null;

    echo json_encode([
        'data' => get_all_ForRecom($pdo, $startDate, $endDate)
    ]);
    exit;
    }else if (isset($_GET['leaveapp_table'])) {
        $startDate = $_REQUEST['startDate'] ?? null;
        $endDate = $_REQUEST['endDate'] ?? null;
        echo json_encode([
            'data' => get_all_leaveapp($pdo, $startDate, $endDate)
        ]);
        exit;
    }else if (isset($_GET['disapprovedleave_table'])) {
        $startDate = $_REQUEST['startDate'] ?? null;
        $endDate = $_REQUEST['endDate'] ?? null;
        echo json_encode([
            'data' => get_all_disapprovedleave($pdo, $startDate, $endDate)
        ]);
        exit;
    }else if (isset($_GET['empleave_table'])) {
        $dept = $_REQUEST['dept'] ?? null;

        // If dept = "All Departments", treat it as no filter
        if ($dept === "All Departments") {
            $dept = null;
        }

        echo json_encode([
            'data' => get_all_leavecredits($pdo, $dept)
        ]);
        exit;
    }
  break;

  case 'PUT':
    // parse_str(file_get_contents('php://input'), $data);
    // echo json_encode(edit_user($pdo, $data));
  break;

  case 'POST':
  if (isset($_GET['add_user'])) {      
     // echo json_encode(add_user($pdo, $_POST));
      exit;
    }else if (isset($_GET['add_userinfo'])) {
      echo json_encode(add_userinfo($pdo, $_POST));
      exit;
    
    }else if (isset($_GET['approve_leave'])) {
      if (!isset($_POST['leave_id'])) {
        echo json_encode(['success' => false, 'message' => 'Leave ID required']);
        exit;
      }
      
      try {
        $leaveId = $_POST['leave_id'];
        
        // Fetch leave and employee data
        $sqlFetch = "SELECT fl.*, i.EMail, i.Fname, i.Lname
                     FROM filedleave fl
                     INNER JOIN i ON fl.EmpNo = i.EmpNo
                     WHERE fl.LeaveID = ?";
        $stmtFetch = $pdo->prepare($sqlFetch);
        $stmtFetch->execute([$leaveId]);
        $leaveData = $stmtFetch->fetch(PDO::FETCH_ASSOC);
        
        if (!$leaveData) {
          echo json_encode(['success' => false, 'message' => 'Leave record not found']);
          exit;
        }
        
        // Update status
        $sqlUpdate = "UPDATE filedleave SET Status = 'Approved' WHERE LeaveID = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$leaveId]);

        // Deduct leave credits
        $leaveTypeMap = [
            'Vacation' => 'VL',
            'Sick' => 'SL',
            'Maternity' => 'ML',
            'Paternity' => 'PL',
            'Adoption' => 'AD',
            'Solo Parent' => 'SPL',
            'VAWC' => 'VAWC',
            'Gynecological' => 'GY',
            'Emergency' => 'EM',
            'Special Privilege' => 'SPP',
            'Study' => 'ST',
            'LWOP' => 'LWOP',
        ];
        $leaveTypeName = $leaveData['LeaveTypeName'] ?? $leaveData['LeaveType'] ?? null;
        $empNo = $leaveData['EmpNo'];
        $dateFrom = $leaveData['DateFrom'];
        $dateTo = $leaveData['DateTo'];
        $column = isset($leaveTypeMap[$leaveTypeName]) ? $leaveTypeMap[$leaveTypeName] : null;
        if ($column) {
            // Calculate number of days (inclusive)
            $days = 1;
            if ($dateFrom && $dateTo) {
                $start = new DateTime($dateFrom);
                $end = new DateTime($dateTo);
                $days = $start->diff($end)->days + 1;
            }
            // Deduct from leavecredits
            $sqlDeduct = "UPDATE leavecredits SET $column = GREATEST($column - ?, 0) WHERE EmpNo = ?";
            $stmtDeduct = $pdo->prepare($sqlDeduct);
            $stmtDeduct->execute([$days, $empNo]);
        }
        
        // Send approval email
        $employeeName = $leaveData['Fname'] . ' ' . $leaveData['Lname'];
        sendLeaveApprovalEmail(
          $leaveData['EMail'],
          $employeeName,
          $leaveData['LeaveTypeName'],
          $leaveData['DateFrom'],
          $leaveData['DateTo'],
          'Approved'
        );
        
        echo json_encode(['success' => true, 'message' => 'Leave application approved']);
      } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
      }
      exit;
    }else if (isset($_GET['reject_leave'])) {
      if (!isset($_POST['leave_id'])) {
        echo json_encode(['success' => false, 'message' => 'Leave ID required']);
        exit;
      }
      
      try {
        $leaveId = $_POST['leave_id'];
        
        // Fetch leave and employee data
        $sqlFetch = "SELECT fl.*, i.EMail, i.Fname, i.Lname
                     FROM filedleave fl
                     INNER JOIN i ON fl.EmpNo = i.EmpNo
                     WHERE fl.LeaveID = ?";
        $stmtFetch = $pdo->prepare($sqlFetch);
        $stmtFetch->execute([$leaveId]);
        $leaveData = $stmtFetch->fetch(PDO::FETCH_ASSOC);
        
        if (!$leaveData) {
          echo json_encode(['success' => false, 'message' => 'Leave record not found']);
          exit;
        }
        
        // Update status
        $sqlUpdate = "UPDATE filedleave SET Status = 'Disapproved' WHERE LeaveID = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$leaveId]);
        
        // Send rejection email
        $employeeName = $leaveData['Fname'] . ' ' . $leaveData['Lname'];
        sendLeaveApprovalEmail(
          $leaveData['EMail'],
          $employeeName,
          $leaveData['LeaveTypeName'],
          $leaveData['DateFrom'],
          $leaveData['DateTo'],
          'Disapproved'
        );
        
        echo json_encode(['success' => true, 'message' => 'Leave application rejected']);
      } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
      }
      exit;
    }
    
  break;

  
}
