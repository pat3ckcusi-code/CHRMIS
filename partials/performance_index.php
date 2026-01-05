<!-- PERFORMANCE INDEX (EXECUTIVE CSC-BASED DASHBOARD) -->

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
  #performanceDetails {
    margin-top: 20px;
    display: none;
    animation: fadeIn 0.3s ease-in-out;
  }
  #ratingSummary {
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
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#007bff;">
      <div class="summary-title">Overall City Performance</div>
      <div class="summary-value text-primary">91.4%</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#28a745;">
      <div class="summary-title">Met or Exceeded Targets</div>
      <div class="summary-value text-success">89%</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#ffc107;">
      <div class="summary-title">Departments Below Target</div>
      <div class="summary-value text-warning">4</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#17a2b8;">
      <div class="summary-title">Top Performing Office</div>
      <div class="summary-value text-info">City Education (97.8%)</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#6f42c1;">
      <div class="summary-title">Outstanding Ratings</div>
      <div class="summary-value text-purple">132</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#dc3545;">
      <div class="summary-title">Improvement Since Last Period</div>
      <div class="summary-value text-danger">+3.2%</div>
    </div>
  </div>
</div>

<!-- 🟩 TOP CHART: Departmental Performance Index -->
<div class="card card-outline card-success shadow-sm mb-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Departmental Performance Index (All Departments)</h3>
  </div>
  <div class="card-body">
    <canvas id="deptPerformanceChart"></canvas>
  </div>
</div>

<!-- 🟦 SECOND ROW: TWO CHARTS SIDE BY SIDE -->
<div class="row">
  <!-- LEFT: Top 5 Departments -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-trophy"></i> Top 5 Performing Departments</h3>
      </div>
      <div class="card-body">
        <canvas id="topDepartmentsChart"></canvas>
      </div>
    </div>
  </div>

  <!-- RIGHT: CSC Rating Distribution -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-warning shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-balance-scale"></i> CSC Adjectival Rating Distribution</h3>
      </div>
      <div class="card-body">
        <canvas id="ratingCategoryChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- POPUP DETAILS SECTION -->
<div id="performanceDetails" class="card card-outline card-secondary shadow-sm">
  <div class="card-header bg-secondary text-white">
    <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Department Performance Breakdown (CSC Metrics)</h3>
  </div>
  <div class="card-body table-responsive">
    <table class="table table-bordered table-hover">
      <thead class="thead-light">
        <tr>
          <th>KRA</th>
          <th>Objective</th>
          <th>Actual Accomplishment</th>
          <th>Quality</th>
          <th>Efficiency</th>
          <th>Timeliness</th>
          <th>Average</th>
        </tr>
      </thead>
      <tbody id="kraTableBody"></tbody>
    </table>
    <div id="ratingSummary" class="text-center text-dark fw-bold"></div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
setTimeout(() => {
  // ✅ ALL DEPARTMENTS LIST
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

  // Randomized static performance values (80–98%)
  const deptScores = departments.map(() => Math.floor(Math.random() * 18) + 80);

  // 🟩 Departmental Performance Index
  const deptChart = new Chart(document.getElementById('deptPerformanceChart'), {
    type: 'bar',
    data: {
      labels: departments,
      datasets: [{
        label: 'Performance (%)',
        data: deptScores,
        backgroundColor: '#28a745'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { display: false } },
        y: { beginAtZero: true, max: 100 }
      },
      onClick: (evt, elements) => {
        if (elements.length > 0) {
          const index = elements[0].index;
          showDepartmentDetails(departments[index]);
        }
      }
    }
  });

  // 🟦 Top 5 Performing Departments
  new Chart(document.getElementById('topDepartmentsChart'), {
    type: 'doughnut',
    data: {
      labels: ['City Education','City Tourism / Culture / Arts','City Health & Sanitation','City Engineering / Public Works','CHRMD'],
      datasets: [{
        data: [97.8,96.4,94.8,93.5,92.9],
        backgroundColor: ['#17a2b8','#28a745','#ffc107','#007bff','#6f42c1']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  // ⚖️ CSC Adjectival Rating Distribution
  new Chart(document.getElementById('ratingCategoryChart'), {
    type: 'pie',
    data: {
      labels: ['Outstanding','Very Satisfactory','Satisfactory','Needs Improvement'],
      datasets: [{
        data: [132, 710, 326, 18],
        backgroundColor: ['#28a745','#17a2b8','#ffc107','#dc3545']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  // 🪶 Sample OPCR/IPCR Breakdown
  const performanceData = {
    'CHRMD': [
      { kra: 'HR Development', obj: 'Conduct trainings', acc: '12 programs for 250 employees', q: 4.6, e: 4.8, t: 4.9 },
      { kra: 'Records Management', obj: 'Digitize employee files', acc: '98% digitized', q: 4.4, e: 4.3, t: 4.7 }
    ],
    'City Education': [
      { kra: 'Instructional Quality', obj: 'Improve outcomes', acc: '96% passing rate', q: 4.9, e: 4.8, t: 4.8 },
      { kra: 'Program Dev.', obj: 'Launch scholarships', acc: '2 new programs', q: 4.8, e: 4.9, t: 4.9 }
    ]
  };

  // 📋 Show popup on chart click
  function showDepartmentDetails(dept) {
    const tableBody = document.getElementById('kraTableBody');
    const detailsDiv = document.getElementById('performanceDetails');
    const summary = document.getElementById('ratingSummary');
    tableBody.innerHTML = '';
    summary.innerHTML = '';

    if (!performanceData[dept]) {
      detailsDiv.style.display = 'none';
      return;
    }

    let total = 0, count = 0;
    performanceData[dept].forEach(item => {
      const avg = ((item.q + item.e + item.t) / 3).toFixed(2);
      total += parseFloat(avg);
      count++;
      tableBody.insertAdjacentHTML('beforeend', `
        <tr>
          <td>${item.kra}</td>
          <td>${item.obj}</td>
          <td>${item.acc}</td>
          <td>${item.q}</td>
          <td>${item.e}</td>
          <td>${item.t}</td>
          <td><strong>${avg}</strong></td>
        </tr>
      `);
    });

    const overall = (total / count).toFixed(2);
    let adjective = '';
    if (overall >= 4.5) adjective = 'Outstanding';
    else if (overall >= 4.0) adjective = 'Very Satisfactory';
    else if (overall >= 3.5) adjective = 'Satisfactory';
    else adjective = 'Needs Improvement';

    summary.innerHTML = `
      <i class="fas fa-star text-primary"></i> 
      <strong>Overall Rating:</strong> ${overall} 
      (<span class="text-success">${adjective}</span>)<br>
      <span class="text-muted">Based on CSC OPCR/IPCR Metrics (Q, E, T)</span>
    `;

    detailsDiv.style.display = 'block';
  }
}, 300);
</script>
