<?php
require_once('../includes/sessions.php');
require_once('../includes/db_config.php');
require_once('../includes/functions/func_user_password.php');

header('Content-type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
  case 'GET':
    if(isset($_GET['user_id'])) {
      $user_id = urldecode($_GET['user_id']);
      echo json_encode([ 'data' => get_user_password_by_user_id($pdo, $user_id) ]);
      exit;
    }
  break;

  case 'PUT':
    parse_str(file_get_contents('php://input'), $data);
    echo json_encode(edit_user_password($pdo, $data));
  break;


  case 'POST':
  break;

  case 'DELETE':
  break;
}

?>
