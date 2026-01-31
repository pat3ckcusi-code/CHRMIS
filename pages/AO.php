<?php
session_start();
if(!isset($_SESSION['Dept'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');


$Deptquery = $pdo->prepare("SELECT * FROM adminusers WHERE Dept = ?");
$Deptquery->execute([$_SESSION["Dept"]]);
$Lrow = $Deptquery->fetch(PDO::FETCH_ASSOC);
// Compute a safe department display value to avoid warnings when no DB row is returned
$deptDisplay = $Lrow['Dept'] ?? $_SESSION['Dept'] ?? 'Unknown Department';
// Determine access level for header: prefer session, then DB, then default
$access_level = 'Department Head / AO';
if (!empty($_SESSION['access_level'])) {
  $access_level = $_SESSION['access_level'];
} elseif (!empty($Lrow['access_level'] ?? '')) {
  $access_level = $Lrow['access_level'];
}

// Resolve a department-based logo file (tolerant to spaces/case) and fallback
$logo_dir = __DIR__ . '/../dist/img/logo/';
$dept_rel_path = '../dist/img/logo/AdminLTELogo.png';
if (is_dir($logo_dir)) {
  $normDept = preg_replace('/[^a-z0-9]/', '', strtolower($deptDisplay));
  $files = scandir($logo_dir);
  foreach ($files as $f) {
    if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), ['png','jpg','jpeg','gif'])) {
      $base = pathinfo($f, PATHINFO_FILENAME);
      $normFile = preg_replace('/[^a-z0-9]/', '', strtolower($base));
      if ($normFile === $normDept) {
        $dept_rel_path = '../dist/img/logo/' . $f;
        break;
      }
    }
  }
}
?>

<?php include("../includes/navbar.php"); ?>
<link rel="stylesheet" href="../dist/css/custom.css">
<!-- Custom CSS for AO Dashboard -->
<style>
.clickable-card.active {
    border: 3px solid #333;
    transform: scale(1.05);
    transition: all 0.2s ease-in-out;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.clickable-card:hover {
    transform: scale(1.03);
    transition: all 0.2s ease-in-out;
    cursor: pointer;
}
</style>


<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1><?php echo htmlspecialchars($access_level); ?></h1></div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Left Column: Profile -->
          <div class="col-md-3">
            <div class="card card-primary card-outline text-center">
              <div class="card-body box-profile">
                <img class="profile-user-img img-fluid img-circle" src="<?php echo htmlspecialchars($dept_rel_path); ?>" alt="User profile picture">
                <h3 class="profile-username mt-2"><?php echo htmlspecialchars($deptDisplay); ?></h3>
              </div>
            </div>

            <div class="card card-primary">
              <div class="card-header"><h3 class="card-title">About Me</h3></div>
              <div class="card-body">
                <strong><i class="fas fa-address-card"></i> No. of Staff</strong>
                <p class="text-muted">10</p>
                
                
              </div>
            </div>
          </div>

          <!-- Right Column: Interactive Dashboard -->
          <div class="col-md-9">

            <!-- Dashboard Cards -->
            <div class="row">
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-info clickable-card" data-file="../partials/on_duty.php">
                  <div class="inner"><h3 id="onDutyCount">0</h3><p>On Duty Today</p></div>
                  <div class="icon"><i class="fas fa-user-check"></i></div>
                </div>
              </div>

              <!-- <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-warning clickable-card" data-file="../partials/manage_leave_credits.php">
                  <div class="inner"><h3>2</h3><p>On Leave</p></div>
                  <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
                </div>
              </div> -->
            
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                  <div class="small-box bg-success clickable-card" data-file="../partials/AO_eta_locator.php">
                      <div class="inner">
                          <h3 id="etaLocatorCount">0</h3><p>On ETA / Locator</p>
                      </div>
                      <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                  </div>
              </div>


              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-danger clickable-card" data-file="../partials/pending_approval.php">
                  <div class="inner"><h3 id="pendingCount">3</h3><p>Pending Approvals</p></div>
                  <div class="icon"><i class="fas fa-file-signature"></i></div>
                </div>
              </div>

              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-primary clickable-card" data-file="../partials/employee_statistics.php">
                  <div class="inner"><h3><i class="fas fa-chart-bar"></i></h3><p>Statistics</p></div>
                  <div class="icon"><i class="fas fa-chart-pie"></i></div>
                </div>
              </div>
              
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-indigo clickable-card" data-file="../partials/travel_order.php">
                  <div class="inner"><h3><i class="fas fa-plane"></i></h3><p>Travel Order</p></div>
                  <div class="icon"><i class="fas fa-paper-plane"></i></div>
                </div>
              </div>

              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-teal clickable-card" data-file="../partials/travel_orders_list.php">
                  <div class="inner"><h3><i class="fas fa-list-alt"></i></h3><p>Filed Travel Orders</p></div>
                  <div class="icon"><i class="fas fa-info-circle"></i></div>
                </div>
              </div>

              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-secondary clickable-card" data-file="../partials/web_logging.php">
                  <div class="inner"><h3><i class="fas fa-book"></i></h3><p>Web-Based Logging (Manual Entry)</p></div>
                  <div class="icon"><i class="fas fa-pen"></i></div>
                </div>
              </div>
            </div>


            <!-- Dynamic Content -->
            <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title" id="dynamicTableTitle"><i class="fas fa-list"></i> Employees</h3>
              </div>
              <div class="card-body table-responsive" id="dynamicContent">
                <p class="text-muted text-center">Click a card to view details.</p>
              </div>
            </div>

          </div>


        </div> <!-- /.row -->
      </div> <!-- /.container-fluid -->
    </section>
  </div>
</div>


<?php
include_once('../partials/footer.php');
// include("../partials/modals/modalpassword.php");
?>
<!-- Using global SweetAlert2 and jQuery loaded in partials/core_javascript.php -->
 <!-- jQuery AJAX to load tabs with caching -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
       
    // Function to load card content 
    function loadCard(file) {
        $("#dynamicContent").html('<div class="text-center my-5"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>');
        $.get(file)
            .done(function(data) {
                $("#dynamicContent").html(data);
            })
            .fail(function() {
                $("#dynamicContent").html('<p class="text-danger text-center">Failed to load content.</p>');
            });
    }

  
    // Clickable card handler
    
    $(".clickable-card").click(function() {
        $(".clickable-card").removeClass("active");
        $(this).addClass("active");
        const file = $(this).data("file");
        loadCard(file);
    });

    // Load default card (On Duty) on page load
    $(".clickable-card[data-file='../partials/on_duty.php']").trigger("click");

  
    // Approve / Reject buttons
    
    $(document).on("click", ".approve-btn", function() {
        const id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to approve this application?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745", 
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, approve it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
              const processing = Swal.fire({
                title: 'Processing...',
                text: 'Updating status and sending notifications. Please wait.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });

              $.post("../includes/functions/update_status.php", { id: id, status: "Approved" })
                .done(function(response) {
                  try { response = typeof response === 'string' ? JSON.parse(response) : response; } catch(e) {}
                  Swal.close();
                  $(".clickable-card.active").trigger("click");

                  if (!response || !response.success) {
                    Swal.fire('Error!', (response && response.message) ? response.message : 'Failed to update the application.', 'error');
                  } else {
                    let msg = 'The application has been approved.';
                    if (response.updated === false) {
                      Swal.fire('Notice', 'No change was made (record may already be processed).', 'info');
                    }

                    // Email statuses
                    const appEmail = response.appEmailSent;
                    const deptEmail = response.deptEmailSent;

                    if (appEmail === true && deptEmail === true) {
                      Swal.fire('Approved!', msg + '\nEmails sent to applicant and department head.', 'success');
                    } else if (appEmail === false && deptEmail === false) {
                      Swal.fire('Updated', msg + '\nBut email notifications were not sent.', 'warning');
                    } else if (appEmail === true && deptEmail === false) {
                      Swal.fire('Updated', msg + '\nApplicant notified, but failed to notify department head.', 'warning');
                    } else if (appEmail === false && deptEmail === true) {
                      Swal.fire('Updated', msg + '\nDepartment head notified, but applicant email failed.', 'warning');
                    } else {
                      Swal.fire('Updated', msg, 'success');
                    }

                    updateETALocatorCard();
                  }
                })
                .fail(function() {
                  Swal.close();
                  Swal.fire('Error!', 'Server error occurred while processing.', 'error');
                });
            }
        });
    });

    $(document).on("click", ".reject-btn", function() {
        const id = $(this).data("id");
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to reject this application?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33", 
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, reject it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
              const processing = Swal.fire({
                title: 'Processing...',
                text: 'Updating status and sending notifications. Please wait.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                  Swal.showLoading();
                }
              });

              $.post("../includes/functions/update_status.php", { id: id, status: "Rejected" })
                .done(function(response) {
                  try { response = typeof response === 'string' ? JSON.parse(response) : response; } catch(e) {}
                  Swal.close();
                  $(".clickable-card.active").trigger("click");

                  if (!response || !response.success) {
                    Swal.fire('Error!', (response && response.message) ? response.message : 'Failed to update the application.', 'error');
                  } else {
                    let msg = 'The application has been rejected.';
                    if (response.updated === false) {
                      Swal.fire('Notice', 'No change was made (record may already be processed).', 'info');
                    }

                    const appEmail = response.appEmailSent;
                    const deptEmail = response.deptEmailSent;

                    if (appEmail === true && deptEmail === true) {
                      Swal.fire('Rejected!', msg + '\nEmails sent to applicant and department head.', 'success');
                    } else if (appEmail === false && deptEmail === false) {
                      Swal.fire('Updated', msg + '\nBut email notifications were not sent.', 'warning');
                    } else if (appEmail === true && deptEmail === false) {
                      Swal.fire('Updated', msg + '\nApplicant notified, but failed to notify department head.', 'warning');
                    } else if (appEmail === false && deptEmail === true) {
                      Swal.fire('Updated', msg + '\nDepartment head notified, but applicant email failed.', 'warning');
                    } else {
                      Swal.fire('Updated', msg, 'success');
                    }

                    updateETALocatorCard();
                  }
                })
                .fail(function() {
                  Swal.close();
                  Swal.fire('Error!', 'Server error occurred while processing.', 'error');
                });
            }
        });
    });

    
    // Pending approvals notification
    
    let firstLoad = true;
    function fetchPendingCount() {
        $.ajax({
            url: '../api/check_pending.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                const current = Number($('#pendingCount').text().trim()) || 0;
                const newCount = Number(data.pendingCount) || 0;
                $('#pendingCount').text(newCount);

                if (firstLoad || newCount > current) {
                    if (newCount > 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: `${newCount} Pending Approval${newCount > 1 ? 's' : ''}`,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                }
                firstLoad = false;
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error, status, xhr.responseText);
            }
        });
    }

    // Initial load and interval
    fetchPendingCount();
    setInterval(fetchPendingCount, 10000);

 
    // ETA / Locator card count

    function updateETALocatorCard() {
        $.getJSON('../api/get_eta_locator_count.php')
            .done(function(data) {
                $('#etaLocatorCount').text(data.count);
            })
            .fail(function(jqxhr, textStatus, error) {
                console.error("ETA/Locator card AJAX error:", textStatus, error);
            });
    }

    // on Duty card count
    function updateOnDutyCard() {
    $.getJSON('../partials/on_duty.php?json=1')
        .done(function(data) {
            $('#onDutyCount').text(data.count);
        });
      }

    
    // ETA / Locator card count
    updateETALocatorCard();
    setInterval(updateETALocatorCard, 15000);

    // On Duty card count
    updateOnDutyCard();
    setInterval(updateOnDutyCard, 15000);


    

});
</script>


