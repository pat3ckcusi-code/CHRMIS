<?php
session_start();
if (!isset($_SESSION['EmpID'])) header("location: ../index.php");

require_once('../includes/initialize.php'); 
include_once(PARTIALS_PATH . 'header.php');

// Load logged-in employee
$stmt = $pdo->prepare("SELECT * FROM adminusers WHERE EmpNo = ? LIMIT 1");
$stmt->execute([$_SESSION['EmpID']]);
$Lrow = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$Lrow) { session_destroy(); header("location: ../index.php"); exit; }

// Check HR Front Office access
if ($Lrow['Dept'] !== 'City Human Resource Management Department') {
    echo "<h3 class='text-danger text-center mt-5'>ACCESS DENIED</h3>";
    exit;
}
?>

<?php include("../includes/navbar.php"); ?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <h1><i class="fas fa-file-alt"></i> Document Request Management</h1>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <!-- Profile -->
          <div class="col-md-3">
            <div class="card card-primary card-outline text-center">
              <div class="card-body box-profile">
                <img class="profile-user-img img-fluid img-circle" src="../dist/img/AdminLTELogo.png" alt="User">
                <h3 class="profile-username mt-2"><?= htmlspecialchars($Lrow['Dept']) ?></h3>
                <p class="text-muted">Front Office / HR</p>
              </div>
            </div>
          </div>

          <!-- Requests Table -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <h3 class="card-title"><i class="fas fa-list"></i> Request History</h3>
                <div class="ml-auto d-flex align-items-center" style="gap:8px;">
                  <select id="filterDocType" class="form-control form-control-sm">
                    <option value="">All types</option>
                  </select>
                  <select id="filterStatus" class="form-control form-control-sm">
                    <option value="">All status</option>
                    <option value="Requested">Requested</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Rejected">Rejected</option>
                  </select>
                  <button id="printReportBtn" class="btn btn-secondary btn-sm" title="Print report"><i class="fas fa-print"></i> Print</button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table id="hrRequestsTable" class="table table-bordered table-hover table-striped">
                  <thead class="thead-light">
                    <tr>
                      <th>Emp No.</th>
                      <th>Employee Name</th>
                      <th>Department</th>
                      <th>Document Type</th>
                      <th>Purpose</th>
                      <th>Requested On</th>
                      <th>Status</th>
                      <th>Remarks</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php include_once('../partials/footer.php'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="../dist/js/hr-document-requests.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
