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
          <div class="col-sm-6"><h1>Department Head / AO</h1></div>
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
                <img class="profile-user-img img-fluid img-circle" src="../../dist/img/AdminLTELogo.png" alt="User profile picture">
                <h3 class="profile-username mt-2"><?php echo htmlspecialchars($Lrow['Dept']); ?></h3>
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

              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-warning clickable-card" data-file="../partials/on_leave.php">
                  <div class="inner"><h3>2</h3><p>On Leave</p></div>
                  <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
                </div>
              </div>
            
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
                $.post("../includes/functions/update_status.php", { id: id, status: "Approved" })
                    .done(function() {
                        $(".clickable-card.active").trigger("click");
                        Swal.fire("Approved!", "The application has been approved.", "success");
                        updateETALocatorCard(); 
                    })
                    .fail(function() {
                        Swal.fire("Error!", "Failed to update the application.", "error");
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
                $.post("../includes/functions/update_status.php", { id: id, status: "Rejected" })
                    .done(function() {
                        $(".clickable-card.active").trigger("click");
                        Swal.fire("Rejected!", "The application has been rejected.", "success");
                        updateETALocatorCard(); 
                    })
                    .fail(function() {
                        Swal.fire("Error!", "Failed to update the application.", "error");
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


