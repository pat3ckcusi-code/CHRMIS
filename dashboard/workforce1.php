<?php if (!defined('PARTIALS_PATH')) {
    require_once __DIR__ . '/../includes/initialize.php';
}

include_once(PARTIALS_PATH . 'header.php');?>
<!-- AdminLTE layout should already be active here -->

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

      <!-- ========== AT-A-GLANCE HR METRICS ========== -->
      <div class="row">

        <!-- 👥 Total Workforce -->
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-primary clickable-card" data-file="../partials/workforce_summary.php">
            <div class="inner">
              <h3>1,248</h3>
              <p>Total Workforce</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
          </div>
        </div>

        <!-- 🏛️ Departments -->
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-success clickable-card" data-file="../partials/departments.php">
            <div class="inner">
              <h3>34</h3>
              <p>Departments / Offices</p>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
          </div>
        </div>

        <!-- 💼 Vacant Positions -->
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-warning clickable-card" data-file="../partials/vacant_positions.php">
            <div class="inner">
              <h3>18</h3>
              <p>Vacant Positions</p>
            </div>
            <div class="icon"><i class="fas fa-briefcase"></i></div>
          </div>
        </div>

        <!-- 📈 Performance Index -->
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-info clickable-card" data-file="../partials/performance_index.php">
            <div class="inner">
              <h3>91.4%</h3>
              <p>Performance Index</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
          </div>
        </div>

        <!-- 🕓 Attendance Rate -->
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
          <div class="small-box bg-secondary clickable-card" data-file="../partials/attendance.php">
            <div class="inner">
              <h3>96.8%</h3>
              <p>Attendance Rate</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
          </div>
        </div>
      
      </div>
      <!-- /row -->

      <!-- ========== DYNAMIC CONTENT AREA ========== -->
      <div class="card mt-3 shadow-sm">
        <div class="card-header">
          <h3 class="card-title" id="dynamicTitle">
            <i class="fas fa-chart-pie"></i> Workforce Insights
          </h3>
        </div>
        <div class="card-body table-responsive" id="dynamicContent">
          <p class="text-muted text-center">Click a card to view details.</p>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </section>
</div>

<script>
document.querySelectorAll('.clickable-card').forEach(card => {
  card.addEventListener('click', function() {
    // Remove active highlight from all cards
    document.querySelectorAll('.clickable-card').forEach(c => c.classList.remove('active'));
    this.classList.add('active');

    // Get target file and title
    const file = this.getAttribute('data-file');
    const title = this.querySelector('p').innerText;
    document.getElementById('dynamicTitle').innerHTML = `<i class="fas fa-chart-pie"></i> ${title}`;

    // Fetch and inject the partial content
    fetch(file)
      .then(response => response.text())
      .then(html => {
        const contentDiv = document.getElementById('dynamicContent');

        // 🔥 Universal Cleanup: remove any open detail popups (Department, Vacant, etc.)
        document.querySelectorAll('[id$="Details"]').forEach(el => el.remove());

        // Inject new content
        contentDiv.innerHTML = html;

        // Execute any inline or external scripts inside the fetched partial
        contentDiv.querySelectorAll("script").forEach(oldScript => {
          const newScript = document.createElement("script");
          if (oldScript.src) {
            newScript.src = oldScript.src; // for external script files
          } else {
            newScript.textContent = oldScript.textContent; // for inline JS
          }
          document.body.appendChild(newScript);
        });
      })
      .catch(() => {
        document.getElementById('dynamicContent').innerHTML =
          "<p class='text-center text-danger'>Unable to load content.</p>";
      });
  });
});
</script>


<?php include_once('../partials/footer.php'); ?>
