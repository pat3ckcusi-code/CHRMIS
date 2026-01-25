
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');
include_once(PARTIALS_PATH . 'header.php');
include("../includes/navbar.php"); 
include('../partials/modals/modal_privacy_notice.php');
date_default_timezone_set('Asia/Manila');

// Check Privacy status from database
$stmt = $pdo->prepare("SELECT Privacy FROM i WHERE EmpNo = ? LIMIT 1");
$stmt->execute([ (string)($_SESSION["EmpID"] ?? '') ]);
$privacy_status = $stmt->fetchColumn();

$Lquery = $pdo->prepare("
   SELECT EmpNo,
       CONCAT(Fname, ' ', Mname, ' ', Lname,
              CASE WHEN Extension = 'N/A' OR Extension = '' THEN '' ELSE CONCAT(' ', Extension) END
       ) AS Name,
       Password, Dept, TelNo, EMail, profile_pic 
FROM i
WHERE EmpNo = ?");

$Lquery->execute([$_SESSION["EmpID"]]);
$Lrow = $Lquery->fetch(PDO::FETCH_ASSOC);

$emp_id = $Lrow['EmpNo'] ?? ($_SESSION['EmpID'] ?? '');

$Posquery = $pdo->prepare("
    SELECT Position 
    FROM v 
    WHERE EmpNo = ? AND IndateTo = '0000-00-00'
");
$Posquery->execute([$_SESSION["EmpID"]]);
$Posrow = $Posquery->fetch(PDO::FETCH_ASSOC);

// Check if a position was found
$position = $Posrow ? $Posrow['Position'] : "Please update position in PDS";

// Set profile picture path
$projectRoot = '/'.basename($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI']);
$profilePic = !empty($Lrow['profile_pic'])
    ? '/CHRMIS/uploads/profile_pics/' . $Lrow['profile_pic']
    : './dist/img/AdminLTELogo.png';

?>
<!-- navbar top and side -->
<link rel="stylesheet" href="../dist/css/custom.css">

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
                     <div class="text-center position-relative" style="width: 150px; margin: auto;">
                        <!-- Clickable profile picture -->
                        <img class="profile-user-img img-fluid img-circle"
                            src="<?= $profilePic ?>"
                            alt="User profile picture"
                            id="profileImage"
                            style="cursor: pointer;">

                        <!-- Spinner overlay (hidden by default) -->
                        <div id="uploadSpinner" style="
                            display: none;
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            z-index: 10;
                        ">
                            <i class="fas fa-spinner fa-pulse fa-2x text-primary"></i>
                        </div>

                        <!-- Hidden file input -->
                        <input type="file" id="fileInput" accept="image/*" style="display: none;">
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

<div class="col-md-9">
  <div class="card">
    <div class="card-header p-2">
      <ul class="nav nav-pills" id="dashboardTabs">

      <!-- DEFAULT ACTIVE TAB -->
        <li class="nav-item">
          <a class="nav-link active" href="#locator" data-file="./eta_locator.php">
            <i class="fas fa-map-marker-alt"></i> ETA / Locator
          </a>
        </li>  
       
        <li class="nav-item">
          <a class="nav-link" href="#leave" data-file="./leave_balance.php">
          <!-- <a class="nav-link" href="#leave" data-file="./under_construction_page.php"> -->
            <i class="fas fa-umbrella-beach"></i> Leave
          </a>
        </li>
        

        <!-- DOCUMENT REQUEST TAB -->
        <li class="nav-item">
          <a class="nav-link" href="#request" data-file="./document_request.php">
            <i class="fas fa-file-alt"></i> Request Document
          </a>
        </li>

      </ul>
    </div>

    <div class="card-body">
      <div id="tabContent">
        <div id="loadingMessage" class="text-center my-5">
          <i class="fas fa-spinner fa-spin fa-2x"></i> Loading...
        </div>
      </div>
    </div>
  </div>
</div>

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
<!-- end of body -->
<?php
include_once('../partials/footer.php');
include("../partials/modals/modalpassword.php");

// change password prompt
if ($Lrow['Password'] === 'password') {
?>
<script>
window.addEventListener("load", function() {
    Swal.fire({
        title: "Change Password Required",
        text: "Please change your password to continue.",
        icon: "warning",
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {            
            const modalElement = document.getElementById("modalPassword");
            const modalInstance = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            modalInstance.show();
        }
    });
});
</script>
<?php
}
?>

<script>
$(document).ready(function() {

  const tabCache = {};

  function loadTabContent(file, tabId) {

    // If cached, load it instantly
    if (tabCache[tabId]) {
      $('#tabContent').html(tabCache[tabId]);
      return;
    }

    // Show loader
    $('#tabContent').html(`
      <div class="text-center my-5">
        <i class="fas fa-spinner fa-spin fa-2x"></i> Loading...
      </div>
    `);

    $.ajax({
      url: file,
      method: 'GET',
      success: function(data) {
        tabCache[tabId] = data;       // cache the content
        $('#tabContent').html(data);  // display content

        // If the loaded content contains the Filed Leaves table, ensure leave.js is loaded
        if ($('#tabContent').find('#filedLeavesTable').length) {
          (function ensureDataTablesThenLoadLeave(done){
            function loadLeave() {
              $.getScript('../dist/js/leave.js')
                .done(function(){ console.log('leave.js dynamically loaded (initial)'); if (done) done(); })
                .fail(function(){ console.warn('Failed to dynamically load leave.js (initial)'); if (done) done(); });
            }

            if (window.jQuery && ($.fn.DataTable || $.fn.dataTable)) {
              loadLeave();
              return;
            }

            var scripts = [
              '../plugins/datatables/jquery.dataTables.js',
              '../plugins/datatables-bs4/js/dataTables.bootstrap4.js',
              '../plugins/datatables-responsive/js/dataTables.responsive.min.js'
            ];
            var i = 0;
            function next() {
              if (i >= scripts.length) { loadLeave(); return; }
              $.getScript(scripts[i])
                .done(function(){ i++; next(); })
                .fail(function(){ console.warn('Failed to load ' + scripts[i]); i++; next(); });
            }
            next();
          })();
        }
      },
      error: function() {
        $('#tabContent').html(`
          <div class="alert alert-danger text-center">Failed to load content.</div>
        `);
      }
    });
  }

  // Load ACTIVE tab by default
  const $activeTab = $('#dashboardTabs .nav-link.active');
  loadTabContent($activeTab.data('file'), $activeTab.attr('href'));

  // Handle tab switching
  $('#dashboardTabs .nav-link').on('click', function(e) {
    e.preventDefault();

    $('#dashboardTabs .nav-link').removeClass('active');
    $(this).addClass('active');

    loadTabContent($(this).data('file'), $(this).attr('href'));
  });

});
</script>


<!-- jQuery AJAX to load tabs with caching -->
<script>
$(document).ready(function() {
  const tabCache = {}; // Store loaded tab content

  function loadTabContent(file, tabId) {
    if (tabCache[tabId]) {
      $('#tabContent').html(tabCache[tabId]);
      return;
    }

    $('#tabContent').html('<div class="text-center my-5"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>');

    $.ajax({
      url: file,
      method: 'GET',
      success: function(data) {
        tabCache[tabId] = data;
        $('#tabContent').html(data);

        // If the loaded content contains the Filed Leaves table, ensure leave.js is loaded
        if ($('#tabContent').find('#filedLeavesTable').length) {
          (function ensureDataTablesThenLoadLeave(done){
            function loadLeave() {
              $.getScript('../dist/js/leave.js')
                .done(function(){ console.log('leave.js dynamically loaded'); if (done) done(); })
                .fail(function(){ console.warn('Failed to dynamically load leave.js'); if (done) done(); });
            }

            if (window.jQuery && ($.fn.DataTable || $.fn.dataTable)) {
              loadLeave();
              return;
            }

            var scripts = [
              '../plugins/datatables/jquery.dataTables.js',
              '../plugins/datatables-bs4/js/dataTables.bootstrap4.js',
              '../plugins/datatables-responsive/js/dataTables.responsive.min.js'
            ];
            var i = 0;
            function next() {
              if (i >= scripts.length) { loadLeave(); return; }
              $.getScript(scripts[i])
                .done(function(){ i++; next(); })
                .fail(function(){ console.warn('Failed to load ' + scripts[i]); i++; next(); });
            }
            next();
          })();
        }
      },
      error: function() {
        $('#tabContent').html('<div class="alert alert-danger text-center">Failed to load content.</div>');
      }
    });
  }

  // Load the ETA / Locator tab by default
  const $activeTab = $('#dashboardTabs .nav-link.active');
  loadTabContent($activeTab.data('file'), $activeTab.attr('href'));
});
</script>

<script>
$(document).ready(function() {
    $('#profileImage').click(function() {
        $('#fileInput').click();
    });

    $('#fileInput').change(function() {
        var file = this.files[0];
        if (!file) return;

        var formData = new FormData();
        formData.append('profile_pic', file);

        // Show spinner
        $('#uploadSpinner').show();

        $.ajax({
            url: '../includes/upload_profile.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Hide spinner
                $('#uploadSpinner').hide();

                // Update profile picture immediately (cache-busting)
                $('#profileImage').attr('src', response + '?' + new Date().getTime());
            },
            error: function() {
                $('#uploadSpinner').hide();
                alert('Upload failed. Please try again.');
            }
        });
    });
});
</script>
<!-- Privacy Notice Modal Handling -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  var privacyStatus = "<?php echo htmlspecialchars($privacy_status ?? ''); ?>";

  // If user has not yet agreed, show modal
  if (privacyStatus !== "Yes") {
    $("#privacyNoticeModal").modal({backdrop: 'static', keyboard: false});
  }

  document.getElementById("acceptNotice").addEventListener("click", function() {
    fetch('../includes/update_privacy.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'id=<?php echo urlencode($emp_id); ?>&agree=Yes'
    })
    .then(() => {
      $("#privacyNoticeModal").modal("hide");
      Swal.fire({
        icon: 'success',
        title: 'Accepted',
        text: 'Privacy notice accepted.',
        timer: 1500,
        showConfirmButton: false
      }).then(() => location.reload());
    })
    .catch(() => {
      Swal.fire('Error', 'Unable to save your response. Please try again.', 'error');
    });
  });

  // Replace simple alert with SweetAlert2 confirmation before redirect
  document.getElementById("declineNotice").addEventListener("click", function() {
    Swal.fire({
      title: 'Decline Privacy Notice?',
      text: 'You must accept the Privacy Notice before proceeding to fill out the E‑PDS form. Declining will return you to the login page.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, decline',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          icon: 'info',
          title: 'Redirecting',
          text: 'You will be returned to the login page.',
          timer: 1200,
          showConfirmButton: false
        }).then(() => {
          window.location.href = "../index.php";
        });
      }
    });
  });
});
</script>




            