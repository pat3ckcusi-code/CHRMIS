<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CHRMis</title>
  <link rel="icon" href="../dist/img/mbs.jpg">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
    include_once('core_stylesheet.php');
    //$currentuserID = $_SESSION[$session_id]->EmpNo;
    //$employee_name = $_SESSION[$session_id]->EmpName;
    //$office = $_SESSION[$session_id]->office;
    //$access_level = $_SESSION[$session_id]->access_level;
    $background_image = "../dist/img/mbs.jpg";
    // $dashboardurl = "../pages/dashboard_itf.php";
    $redirect = 'Profile.php'; // default
    if (isset($_SESSION['Access'], $_SESSION['Status'])) {
        if ($_SESSION['Access'] === 'Admin') {
            $redirect = 'Workforce.php';
        } elseif ($_SESSION['Access'] === 'User') {
            $redirect = 'Profile.php';
        }elseif ($_SESSION['Access'] === 'AO' && $_SESSION['Status'] === 'FOR RECOMMENDATION') {
            $redirect = 'AO.php';
        } elseif ($_SESSION['Status'] === 'FOR Encoder') {
            $redirect = 'LeaveApp.php';
        } elseif ($_SESSION['Status'] === 'Frontline') {
            $redirect = 'front_office_clerk.php';
        }
    }
  ?>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</head>
<body class="hold-transition sidebar-collapse">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark" 
       style="background: #b92b27;
              background: -webkit-linear-gradient(to right, #1565C0, #b92b27);
              background: linear-gradient(to right, #1565C0, #b92b27);">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo $redirect; ?>" class="nav-link">Calapan City Human Resource Management Department</a>
      </li>
    </ul>
 
    <!-- Right navbar links -->
    <?php if(isset($_SESSION['Access'])) { ?>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-2x"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <!-- Change Password -->
            <a href="#modalPassword" data-toggle="modal" class="dropdown-item" style="font-family: Century Gothic; font-weight: bold;">
              <i class="fa fa-gear fa-fw"></i>&nbsp;&nbsp; Change Password
            </a>
            <div class="dropdown-divider"></div>
            <!-- Logout -->
            <a href="#" class="dropdown-item brand-link" style="font-family: Century Gothic; font-weight: bold;" data-action="user_logout">
              <i class="fa fa-sign-out fa-fw"></i>&nbsp;&nbsp; Logout
            </a>
          </div>
        </li>
      </ul>
    <?php } ?>
  </nav>
  <!-- /.navbar -->
</div>
