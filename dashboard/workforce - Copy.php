<?php
if (!defined('PARTIALS_PATH')) require_once __DIR__ . '/../includes/initialize.php';
include_once(PARTIALS_PATH . 'header.php');
?>

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

      <!-- Summary Card -->
      <div class="row">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-primary clickable-card active" id="resetDashboard">
            <div class="inner">
              <h3 id="totalWorkforce">0</h3>
              <p>Total Workforce</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
          </div>
        </div>
      </div>

      <!-- Workforce Insights -->
      <div class="card mt-3 shadow-sm">
        <div class="card-header">
          <h3 class="card-title" id="dynamicTitle">
            <i class="fas fa-chart-pie"></i> Workforce Insights
          </h3>
        </div>
        <div class="card-body table-responsive" id="dynamicContent">
          <?php include('../partials/workforce_summary.php'); ?>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
function updateTotalWorkforce(dept = '') {
  fetch(`/CHRMIS/api/workforce_data.php?dept=${encodeURIComponent(dept)}`)
    .then(res => res.json())
    .then(data => {
      if (data && data.gender) {
        const total = data.gender.reduce((sum, g) => sum + Number(g.total), 0);
        document.getElementById('totalWorkforce').textContent = total;
      } else {
        document.getElementById('totalWorkforce').textContent = '0';
      }
    })
    .catch(err => console.error('Error fetching total workforce:', err));
}

document.addEventListener('DOMContentLoaded', function() {
  updateTotalWorkforce();

  // Reset dashboard when "Total Workforce" card is clicked
  document.getElementById('resetDashboard').addEventListener('click', function() {
    const deptDropdown = document.getElementById('filterDept');
    if (deptDropdown) deptDropdown.value = '';
    updateTotalWorkforce('');
    initializeCharts('');
  });

  // Watch for department filter changes
  $(document).on('change', '#filterDept', function() {
    const dept = $(this).val();
    updateTotalWorkforce(dept);
  });
});
</script>

<?php include_once('../partials/footer.php'); ?>
