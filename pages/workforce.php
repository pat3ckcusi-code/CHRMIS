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

        <!-- 💼 Vacant Positions -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-warning clickable-card" data-file="../partials/vacant_positions.php">
            <div class="inner">
              <h3>18</h3>
              <p>Vacant Positions</p>
            </div>
            <div class="icon"><i class="fas fa-briefcase"></i></div>
          </div>
        </div> -->

        <!-- 📈 Performance Index -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-info clickable-card" data-file="../partials/performance_index.php">
            <div class="inner">
              <h3>91.4%</h3>
              <p>Performance Index</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
          </div>
        </div> -->

        <!-- 🕓 Attendance Rate -->
        <!-- <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-secondary clickable-card" data-file="../partials/attendance.php">
            <div class="inner">
              <h3>96.8%</h3>
              <p>Attendance Rate</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
          </div>
        </div> -->

         <!-- 🗂️ Leave Management -->
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-success clickable-card" data-file="../partials/mayor_pending_approval.php">
            <div class="inner">
              <h3 id="pendingLeavesCount">0</h3>
              <p>Leave Management</p>
            </div>
            <div class="icon"><i class="fas fa-file-signature"></i></div>
          </div>
        </div>

        <!-- ✈️ Travel Orders (Mayor) -->
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-teal clickable-card" data-file="../partials/mayor_travel_orders.php">
            <div class="inner">
              <h3 id="pendingTOCount">0</h3>
              <p>Filed Travel Orders</p>
            </div>
            <div class="icon"><i class="fas fa-suitcase-rolling"></i></div>
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
let pendingTOPrevCount;
let pendingTOFirstLoad = true;
let pendingLeavesPrevCount = 0;
let pendingLeavesFirstLoad = true;

function bindFilterDept() {
  $('#filterDept').off('change').on('change', function(){
    const dept = $(this).val();
    initializeCharts(dept);
    loadDeptChart();
    updateTotalWorkforce(dept);
  });
}

// Load summary on page load (default all departments)
$('#dynamicContent').load('../partials/workforce_summary.php', function(){
    loadDeptChart();
    initializeCharts();
    updateTotalWorkforce();
  updatePendingLeavesCount();
  updatePendingTOCount();
    bindFilterDept();
});

//  Update Total Workforce 
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

// Update pending leaves count by loading the mayor_pending partial and reading its badge
function updatePendingLeavesCount(){
  fetch('../partials/mayor_pending_approval.php')
    .then(res => res.text())
    .then(html => {
      const tmp = document.createElement('div');
      tmp.innerHTML = html;
      const badge = tmp.querySelector('#approvalsBadge');
      const alertCount = tmp.querySelector('#pendingAlertCount');
      const raw = (badge && badge.textContent.trim()) || (alertCount && alertCount.textContent.trim()) || '0';
      const val = parseInt(raw, 10) || 0;
      const el = document.getElementById('pendingLeavesCount');
      if (el) el.textContent = val;

      // show toast on first load or when count increases
      if (pendingLeavesFirstLoad || val > pendingLeavesPrevCount) {
        if (val > 0 && typeof Swal !== 'undefined') {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: `${val} Pending Approval${val > 1 ? 's' : ''}`,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
          });
        }
      }
      pendingLeavesPrevCount = val;
      pendingLeavesFirstLoad = false;
    })
    .catch(err => console.error('Error updating pending leaves count:', err));
}

// Update pending travel orders count for Mayor dashboard
function updatePendingTOCount(){
  fetch('../api/get_travel_orders_mayor.php')
    .then(res => res.json())
    .then(rows => {
      const val = (Array.isArray(rows)) ? rows.length : 0;
      const el = document.getElementById('pendingTOCount');
      if (el) el.textContent = val;

      // show toast on first load or when count increases
      if (pendingTOFirstLoad || val > (pendingTOPrevCount || 0)) {
        if (val > 0 && typeof Swal !== 'undefined') {
          const newCount = val - (pendingTOPrevCount || 0);
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
        // brief visual highlight on the card
        const card = document.querySelector('.clickable-card[data-file="../partials/mayor_travel_orders.php"]');
        if (card) {
          card.classList.add('border-warning');
          setTimeout(()=>card.classList.remove('border-warning'), 4000);
        }
      }
      pendingTOPrevCount = val;
      pendingTOFirstLoad = false;
    })
    .catch(err => {
      console.error('Error updating pending travel orders count:', err);
      const el = document.getElementById('pendingTOCount');
      if (el) el.textContent = '0';
    });
}

document.querySelectorAll('.clickable-card').forEach(card => {
  card.addEventListener('click', function() {
    // Remove active highlight from all cards
    document.querySelectorAll('.clickable-card').forEach(c => c.classList.remove('active'));
    this.classList.add('active');

    // Get target file and title
    const file = this.getAttribute('data-file');
    const title = this.querySelector('p').innerText;
    document.getElementById('dynamicTitle').innerHTML = `<i class="fas fa-chart-pie"></i> ${title}`;

    // If the card is for workforce summary, reload and update
    if (file === '../partials/workforce_summary.php') {
      $('#dynamicContent').load(file, function() {
        loadDeptChart && loadDeptChart();
        initializeCharts && initializeCharts();
        updateTotalWorkforce && updateTotalWorkforce();
        bindFilterDept();
      });
    } else {
      // Fetch and inject the partial content for other cards
      fetch(file)
        .then(response => response.text())
        .then(html => {
          const contentDiv = document.getElementById('dynamicContent');
          document.querySelectorAll('[id$="Details"]').forEach(el => el.remove());
          contentDiv.innerHTML = html;
          contentDiv.querySelectorAll("script").forEach(oldScript => {
            const newScript = document.createElement("script");
            if (oldScript.src) {
              newScript.src = oldScript.src; 
            } else {
              newScript.textContent = oldScript.textContent; 
            }
            document.body.appendChild(newScript);
          });
          bindFilterDept();
        })
        .catch(() => {
          document.getElementById('dynamicContent').innerHTML =
            "<p class='text-center text-danger'>Unable to load content.</p>";
        });
    }
  });
});
// refresh pending leaves count periodically (every 60s)
setInterval(updatePendingLeavesCount, 60000);
// refresh pending travel orders count periodically (every 60s)
setInterval(updatePendingTOCount, 60000);
</script>
