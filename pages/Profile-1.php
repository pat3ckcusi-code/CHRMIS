
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

$Lquery = $pdo->prepare("
   SELECT EmpNo,
       CONCAT(Fname, ' ', Mname, ' ', Lname,
              CASE WHEN Extension = 'N/A' OR Extension = '' THEN '' ELSE CONCAT(' ', Extension) END
       ) AS Name,
       Dept, TelNo, EMail
FROM i
WHERE EmpNo = ?");

$Lquery->execute([$_SESSION["EmpID"]]);
$Lrow = $Lquery->fetch(PDO::FETCH_ASSOC);


$Posquery = $pdo->prepare("
    SELECT Position 
    FROM v 
    WHERE EmpNo = ? AND IndateTo = '0000-00-00'
");
$Posquery->execute([$_SESSION["EmpID"]]);
$Posrow = $Posquery->fetch(PDO::FETCH_ASSOC);

// Check if a position was found
$position = $Posrow ? $Posrow['Position'] : "Please update position in PDS";

?>
<!-- navbar top and side -->
 <?php include("../includes/navbar.php"); ?>
<!-- end navbar top and side -->
    

    <!-- start of body -->
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="../../dist/img/AdminLTELogo.png"
                        alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">
                    <?php echo htmlspecialchars($Lrow['Name']); ?>
                    </h3>

                    <p class="text-muted text-center">
                    <?php echo htmlspecialchars($Lrow['EmpNo']); ?>
                    </p>
                </div>
                </div>

            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               <strong><i class="fas fa-address-card"></i> Designation</strong>
                    <p class="text-muted"><?php echo htmlspecialchars($position); ?></p>
                    <hr>
                    <strong><i class="fas fa-building"></i> Department</strong>
                    <p class="text-muted"><?php echo htmlspecialchars($Lrow['Dept']); ?></p>
                    <hr>
                    <strong><i class="fas fa-briefcase"></i> Employment Status</strong>
                    <p class="text-muted">Permanent</p>
                    <hr>
                    <strong><i class="fas fa-envelope"></i> Email</strong>
                    <p class="text-muted"><?php echo htmlspecialchars($Lrow['EMail']); ?></p>
                    <hr>
                    <strong><i class="far fa-address-book"></i> Contact</strong>
                    <p class="text-muted"><?php echo htmlspecialchars($Lrow['TelNo']); ?></p>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <?php
// dashboard.php
?>

<div class="col-md-9">
  <div class="card">
    <div class="card-header p-2">
      <ul class="nav nav-pills" id="dashboardTabs">
        <li class="nav-item">
          <a class="nav-link active" href="#overview" data-file="./overview.php">
            <i class="fas fa-id-badge"></i> Overview
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#leave" data-file="./leave_balance.php">
            <i class="fas fa-umbrella-beach"></i> Leave Balance
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#locator" data-file="./eta_locator.php">
            <i class="fas fa-map-marker-alt"></i> ETA / Locator
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#dtr" data-file="./time_logs.php">
            <i class="fas fa-clock"></i> Time Logs
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#service" data-file="./service_record.php">
            <i class="fas fa-archive"></i> Service Record
          </a>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content" id="tabContent">
        <div id="loadingMessage" class="text-center my-5">
          <i class="fas fa-spinner fa-spin fa-2x"></i> Loading...
        </div>
      </div>
    </div>
  </div>
</div>

<!-- jQuery AJAX to load tabs with caching -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  const tabCache = {}; // Store loaded tab content

  function loadTabContent(file, tabId) {
    if (tabCache[tabId]) {
      // Load cached content if available
      $('#tabContent').html(tabCache[tabId]);
      return;
    }

    $('#tabContent').html('<div class="text-center my-5"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>');

    $.ajax({
      url: file,
      method: 'GET',
      success: function(data) {
        tabCache[tabId] = data; // Cache the loaded content
        $('#tabContent').html(data);
      },
      error: function() {
        $('#tabContent').html('<div class="alert alert-danger text-center">Failed to load content.</div>');
      }
    });
  }

  // Load default active tab
  const $activeTab = $('#dashboardTabs .nav-link.active');
  loadTabContent($activeTab.data('file'), $activeTab.attr('href'));

  // Handle tab click
  $('#dashboardTabs .nav-link').on('click', function(e) {
    e.preventDefault();

    $('#dashboardTabs .nav-link').removeClass('active');
    $(this).addClass('active');

    const file = $(this).data('file');
    const tabId = $(this).attr('href');

    loadTabContent(file, tabId);
  });
});
</script>




      </div> <!-- /.tab-content -->
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col-md-9 -->






                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
 

  
</div>
<!-- end of body -->

<?php
include_once('../partials/footer.php');
include("../partials/modals/modalpassword.php");
?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>




            