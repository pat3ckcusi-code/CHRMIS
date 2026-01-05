<style>
canvas {
  width: 100% !important;
  height: 220px !important;
}
.chart-container {
  position: relative;
  width: 100%;
  padding: 10px;
}
</style>

<!-- FILTER -->
<div class="card mb-4 shadow-sm border-0">
  <div class="card-body">
    <div class="row g-3 align-items-center">
      <div class="col-md-3">
        <select id="filterDept" class="form-select">
          <option value="">All Departments</option>
          <?php
          require_once __DIR__ . '/../includes/db_config.php';
          $stmt = $pdo->query("SELECT DISTINCT Dept FROM i ORDER BY Dept");
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value=\"{$row['Dept']}\">{$row['Dept']}</option>";
          }
          ?>
        </select>
      </div>
    </div>
  </div>
</div>

<!-- FIRST ROW -->
<div class="row charts-row">
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-venus-mars"></i> Gender Distribution</h3></div>
      <div class="card-body chart-container"><canvas id="genderChart"></canvas></div>
    </div>
  </div>
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-briefcase"></i> Employment Status</h3></div>
      <div class="card-body chart-container"><canvas id="statusChart"></canvas></div>
    </div>
  </div>
</div>

<!-- SECOND ROW -->
<div class="row charts-row">
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-warning shadow-sm">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-birthday-cake"></i> Age Group Distribution</h3></div>
      <div class="card-body chart-container"><canvas id="ageChart"></canvas></div>
    </div>
  </div>
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-danger shadow-sm">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-user-clock"></i> Length of Service</h3></div>
      <div class="card-body chart-container"><canvas id="tenureChart"></canvas></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let charts = {}; 

function initializeCharts(dept = '') {
  const ctxGender = document.getElementById('genderChart').getContext('2d');
  const ctxStatus = document.getElementById('statusChart').getContext('2d');
  const ctxAge = document.getElementById('ageChart').getContext('2d');
  const ctxTenure = document.getElementById('tenureChart').getContext('2d');

  // Smooth transition: fade out all chart rows before reload
  $(".charts-row").fadeTo(200, 0.2);

  fetch(`/CHRMIS/api/workforce_data.php?dept=${encodeURIComponent(dept)}`)
    .then(res => res.json())
    .then(data => {
      if (!data) return console.warn('No data returned from API');

      // destroy old charts
      for (const key in charts) if (charts[key]) charts[key].destroy();

      // 🔹 Sort order
      const ageOrder = ['<30','30-39','40-49','50-59','60+'];
      const tenureOrder = ['<1 yr','1-4 yrs','5-9 yrs','10-14 yrs','15-19 yrs','20+ yrs'];
      data.age.sort((a, b) => ageOrder.indexOf(a.AgeGroup) - ageOrder.indexOf(b.AgeGroup));
      data.tenure.sort((a, b) => tenureOrder.indexOf(a.ServiceLength) - tenureOrder.indexOf(b.ServiceLength));

      const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' }, tooltip: { enabled: true } },
        interaction: { mode: 'nearest', intersect: false },
        animation: { duration: 800, easing: 'easeOutQuart' }
      };

      charts.gender = new Chart(ctxGender, {
        type: 'doughnut',
        data: {
          labels: data.gender.map(g => g.Gender || 'Unknown'),
          datasets: [{ data: data.gender.map(g => g.total), backgroundColor: ['#36A2EB', '#FF6384'] }]
        },
        options: commonOptions
      });

      charts.status = new Chart(ctxStatus, {
        type: 'pie',
        data: {
          labels: data.status.map(s => s.EmploymentStatus || 'Unknown'),
          datasets: [{ data: data.status.map(s => s.total), backgroundColor: ['#FFCE56','#4BC0C0','#9966FF','#FF9F40','#FF6384'] }]
        },
        options: commonOptions
      });

      // ✅ Improved tooltip & no flicker
      const barOptions = {
        ...commonOptions,
        animation: { duration: 900, easing: 'easeOutCubic' },
        scales: { y: { beginAtZero: true, ticks: { precision:0 } } }
      };

      charts.age = new Chart(ctxAge, {
        type: 'bar',
        data: { labels: data.age.map(a => a.AgeGroup), datasets: [{ label: 'Employees', data: data.age.map(a => a.total), backgroundColor: '#FF9F40' }] },
        options: barOptions
      });

      charts.tenure = new Chart(ctxTenure, {
        type: 'bar',
        data: { labels: data.tenure.map(t => t.ServiceLength), datasets: [{ label: 'Employees', data: data.tenure.map(t => t.total), backgroundColor: '#9966FF' }] },
        options: barOptions
      });

      // Smooth fade-in after load
      $(".charts-row").fadeTo(300, 1);
    })
    .catch(err => console.error("Fetch error:", err));
}

document.addEventListener('DOMContentLoaded', () => {
  initializeCharts();

  $('#filterDept').off('change').on('change', function() {
    const dept = $(this).val();
    initializeCharts(dept);
  });
});
</script>
