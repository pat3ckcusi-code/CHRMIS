<?php
session_start();
if(!isset($_SESSION['Status']) || $_SESSION['Status'] !== 'leave'){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

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
          <div class="col-sm-6"><h1>Leave Portal</h1></div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          

          <!-- Right Column: Interactive Dashboard -->
          <div class="col-md-12">

            <!-- Dashboard Cards -->
            <div class="row">            
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-warning clickable-card" data-file="../partials/manage_leave_credits.php">
                  <div class="inner"><h3>2</h3><p>Manage Leave Credits</p></div>
                  <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
                </div>
              </div>
              
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-teal clickable-card" data-file="../partials/approved_leaves.php">
                  <div class="inner"><h3><i class="fas fa-check"></i></h3><p>Approved Leaves</p></div>
                  <div class="icon"><i class="fas fa-user-clock"></i></div>
                </div>
              </div>

              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-secondary clickable-card" data-file="../partials/pending_leaves.php">
                  <div class="inner"><h3><i class="fas fa-eye"></i></h3><p>Pending Requests (View)</p></div>
                  <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                </div>
              </div>

              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-danger clickable-card" data-file="../partials/cancel_leave_application.php">
                  <div class="inner"><h3><i class="fas fa-ban"></i></h3><p>Cancel Leave</p></div>
                  <div class="icon"><i class="fas fa-user-times"></i></div>
                </div>
              </div>
              <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="small-box bg-primary clickable-card" data-file="../partials/lwop.php">
                  <div class="inner"><h3><i class="fas fa-user-minus"></i></h3><p>Leave Without Pay</p></div>
                  <div class="icon"><i class="fas fa-user-slash"></i></div>
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
?>
<!-- jQuery AJAX to load tabs with caching -->
<script>
$(document).ready(function() {
       
    // Function to load card content (exposed globally so partials can request reload by dept)
    window.loadCard = function(file) {
      $("#dynamicContent").html('<div class="text-center my-5"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>');
      // Use jQuery.load so any inline <script> in the partial is executed
      $("#dynamicContent").load(file, function(response, status, xhr) {
        if (status === 'error') {
          console.error('Failed to load', file, status, xhr && xhr.statusText, xhr && xhr.responseText);
          $("#dynamicContent").html('<p class="text-danger text-center">Failed to load content. See console for details.</p>');
        } else {
          console.log('Loaded partial', file);
        }
      });
    };

  
    // Clickable card handler (delegated) with logging for diagnostics
    $(document).on('click', '.clickable-card', function() {
      console.log('clickable-card clicked', this, $(this).data('file'));
      $(".clickable-card").removeClass("active");
      $(this).addClass("active");
      const file = $(this).data("file");
      if (!file) {
        console.error('No data-file on clicked card');
        return;
      }
      try { loadCard(file); }
      catch (e) { console.error('loadCard failed', e); }
    });

    // Delegate dept filter change events from loaded partials
    $(document).on('change', '#approvedDeptFilter', function() {
      const dept = $(this).val() || 'ALL';
      loadCard('../partials/approved_leaves.php?dept=' + encodeURIComponent(dept));
    });

    $(document).on('change', '#pendingDeptFilter', function() {
      const dept = $(this).val() || 'ALL';
      loadCard('../partials/pending_leaves.php?dept=' + encodeURIComponent(dept));
    });

    // Load default card (On Duty) on page load
    $(".clickable-card[data-file='../partials/on_duty.php']").trigger("click");

  

});
</script>


