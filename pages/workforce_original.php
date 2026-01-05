<?php
session_start();
if(!isset($_SESSION['Status'])){
  header("location: ../index.php");
}else{
      $access_level = $_SESSION['Status'];
    }
if (!defined('PARTIALS_PATH')) require_once __DIR__ . '/../includes/initialize.php';

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
?>
<!-- navbar top and side -->
 <?php include("../includes/navbar.php"); ?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><i class="fas fa-chart-line"></i> Workforce Analytics Overview</h1>
          <p class="text-muted mb-0">City Government of Calapan • Executive HR Dashboard</p>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">

      <!-- Total Workforce Card -->
      <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-primary clickable-card" data-file="../partials/workforce_summary.php">
            <div class="inner">
              <h3 id="totalWorkforce">0</h3>
              <p>Total Workforce</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
          </div>
        </div>
      </div>

      <!-- Dynamic Content -->
      <div class="card mt-3 shadow-sm">
        <div class="card-header">
          <h3 class="card-title" id="dynamicTitle">
            <i class="fas fa-chart-pie"></i> Workforce Insights
          </h3>
        </div>
        <div class="card-body table-responsive" id="dynamicContent">
          <!-- Will load charts automatically -->
        </div>
      </div>

    </div>
  </section>
</div>

<?php include_once('../partials/footer.php'); 
include_once('../partials/modals/modal_add_user.php');
include_once('../partials/modals/modal_EmpList.php');?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let charts = {};
let deptChart;

// Load summary on page load (default all departments)
$('#dynamicContent').load('../partials/workforce_summary.php', function(){
    loadDeptChart();
    initializeCharts();
    updateTotalWorkforce();

    // Department filter
    $('#filterDept').off('change').on('change', function(){
        const dept = $(this).val();
        initializeCharts(dept);
        loadDeptChart();
        updateTotalWorkforce(dept);
    });
});

// ===== Update Total Workforce =====
function updateTotalWorkforce(dept=''){
  fetch(`../api/workforce_data.php?dept=${encodeURIComponent(dept)}`)
    .then(res => res.json())
    .then(data => {
        let total = 0;
        if(data && data.gender) total = data.gender.reduce((sum,g)=>sum+g.total,0);
        $('#totalWorkforce').text(total);
    })
    .catch(err=>console.error("Error updating total workforce:",err));
}
</script>
