<!-- ATTENDANCE RATE (EXECUTIVE DASHBOARD) -->

<style>
  canvas { width: 100% !important; height: 230px !important; }
  .summary-box {
    border-left: 5px solid;
    padding: 15px;
    background: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
  }
  .summary-box:hover { transform: scale(1.03); }
  .summary-title { font-weight: bold; font-size: 0.9rem; color: #666; }
  .summary-value { font-size: 1.5rem; font-weight: bold; }
  #attendanceDetails {
    margin-top: 20px;
    display: none;
    animation: fadeIn 0.3s ease-in-out;
  }
  #deptSummary {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    border-radius: 8px;
    font-size: 1rem;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<!-- KPI SUMMARY -->
<div class="row">
  <div class="col-md-3 col-sm-6 mb-3">
    <div class="summary-box" style="border-color:#007bff;">
      <div class="summary-title">Overall Attendance Rate</div>
      <div class="summary-value text-primary">96.8%</div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 mb-3">
    <div class="summary-box" style="border-color:#28a745;">
      <div class="summary-title">Average Daily Presence</div>
      <div class="summary-value text-success">853</div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 mb-3">
    <div class="summary-box" style="border-color:#ffc107;">
      <div class="summary-title">Tardiness Rate</div>
      <div class="summary-value text-warning">2.4%</div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 mb-3">
    <div class="summary-box" style="border-color:#dc3545;">
      <div class="summary-title">Absence Rate</div>
      <div class="summary-value text-danger">3.2%</div>
    </div>
  </div>
</div>

<!-- CHARTS SECTION -->
<div class="row">
  <!-- Departmental Attendance Rate (Scrollable) -->
  <div class="col-md-12 mb-3">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-building"></i> Departmental Attendance Rate</h3>
      </div>
      <div class="card-body" style="overflow-x:auto;">
        <canvas id="deptAttendanceChart" style="min-width:1400px;"></canvas>
      </div>
    </div>
  </div>

  <!-- Absence & Tardiness -->
  <div class="col-md-4 mb-3">
    <div class="card card-outline card-warning shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clock"></i> Absence & Tardiness Breakdown</h3>
      </div>
      <div class="card-body">
        <canvas id="absenceChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Monthly Attendance Trend -->
  <div class="col-md-8 mb-3">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-line"></i> Monthly Attendance Trend</h3>
      </div>
      <div class="card-body">
        <canvas id="monthlyAttendanceChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- POPUP DETAILS SECTION -->
<div id="attendanceDetails" class="card card-outline card-secondary shadow-sm">
  <div class="card-header bg-secondary text-white">
    <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Department Attendance Details</h3>
  </div>
  <div class="card-body table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="thead-light">
        <tr>
          <th>Department</th>
          <th>Attendance %</th>
          <th>Average Absences</th>
          <th>Average Tardiness</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody id="deptTableBody"></tbody>
    </table>
    <div id="deptSummary" class="text-center text-dark fw-bold"></div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
setTimeout(() => {
  const departments = [
    'CHRMD','City Mayor Office','Office of the Vice Mayor','City Administrator / Chief of Staff / Secretary',
    'City Budget','City Legal','City Education','City College of Calapan','City Public Safety','City Veterinary Services',
    'City DRRM','City Nutrition','City Population Dev.','City Health & Sanitation','City Treasury','City Assessor',
    'City Economic Enterprise','BPLO','City Trade & Industry','City Medical Health Care','City Accounting / Audit',
    'City General Services','BAC','City Social Welfare Dev.','CPESO','Barangay Dev. Affairs','City Civil Registry',
    'City Youth & Sports','City Agriculture','City Cooperative Dev.','City Housing / Urban Settlements','City Arch. Planning / Design',
    'City Environment & Natural Resources','City Engineering / Public Works','Urban Planning','MISO','Gender & Development',
    'Fisheries Management','City Veterinary (Plaza)','Office for Senior Citizens','SP Secretariat','City Public Library',
    'PDAO','Convention Center','City Information','City Tourism / Culture / Arts','Community Affairs'
  ];

  const attendanceRates = departments.map(() => Math.floor(Math.random() * 10) + 91); // Random 91-100%

  // Generate colors based on attendance %
  const barColors = attendanceRates.map(rate => {
    if (rate >= 97) return '#28a745'; // green
    if (rate >= 94) return '#ffc107'; // yellow
    return '#dc3545'; // red
  });

  // Departmental Attendance Rate
  const deptChart = new Chart(document.getElementById('deptAttendanceChart'), {
    type: 'bar',
    data: {
      labels: departments,
      datasets: [{
        label: 'Attendance (%)',
        data: attendanceRates,
        backgroundColor: barColors
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, max: 100 } },
      onClick: (evt, elements) => {
        if (elements.length > 0) {
          const index = elements[0].index;
          showDeptDetails(departments[index], attendanceRates[index]);
        }
      }
    }
  });

  // Absence & Tardiness Breakdown
  new Chart(document.getElementById('absenceChart'), {
    type: 'doughnut',
    data: {
      labels: ['Present', 'Absent', 'Late'],
      datasets: [{
        data: [96.8, 3.2, 2.4],
        backgroundColor: ['#28a745','#dc3545','#ffc107']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  // Monthly Attendance Trend
  new Chart(document.getElementById('monthlyAttendanceChart'), {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct'],
      datasets: [{
        label: 'Attendance %',
        data: [96, 97, 95, 96.5, 94, 96, 97, 95.5, 96.8, 97],
        borderColor: '#007bff',
        backgroundColor: 'rgba(0,123,255,0.2)',
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, max: 100 } }
    }
  });

  // Popup department details
  function showDeptDetails(dept, rate) {
    const detailsDiv = document.getElementById('attendanceDetails');
    const tableBody = document.getElementById('deptTableBody');
    const summary = document.getElementById('deptSummary');
    tableBody.innerHTML = '';
    summary.innerHTML = '';

    const sample = [
      { abs: 4, late: 2, remark: 'Excellent attendance' },
      { abs: 6, late: 3, remark: 'Good attendance, minor tardiness' },
      { abs: 8, late: 5, remark: 'Needs improvement' }
    ];
    const random = sample[Math.floor(Math.random() * sample.length)];

    tableBody.insertAdjacentHTML('beforeend', `
      <tr>
        <td>${dept}</td>
        <td>${rate}%</td>
        <td>${random.abs}</td>
        <td>${random.late}</td>
        <td>${random.remark}</td>
      </tr>
    `);

    summary.innerHTML = `
      <i class="fas fa-user-clock text-primary"></i> 
      <strong>${dept}</strong> maintains an attendance rate of 
      <strong>${rate}%</strong>. ${random.remark}.
    `;

    detailsDiv.style.display = 'block';
  }
}, 300);
</script>
