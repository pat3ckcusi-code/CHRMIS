<!-- Vacant Positions - Executive Overview -->

<style>
  canvas {
    width: 100% !important;
    height: 180px !important;
  }
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
</style>

<div class="row">
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#007bff;">
      <div class="summary-title">Total Vacant Positions</div>
      <div class="summary-value text-primary">18</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#28a745;">
      <div class="summary-title">Departments with Vacancies</div>
      <div class="summary-value text-success">11</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#ffc107;">
      <div class="summary-title">Highest Vacancy Dept</div>
      <div class="summary-value text-warning">CENRD (3)</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#17a2b8;">
      <div class="summary-title">Most Needed Role</div>
      <div class="summary-value text-info">Admin Aide</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#dc3545;">
      <div class="summary-title">Critical Vacancies</div>
      <div class="summary-value text-danger">5</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#6f42c1;">
      <div class="summary-title">Under Recruitment</div>
      <div class="summary-value text-purple">4</div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Vacant Positions per Department -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-building"></i> Vacant Positions per Department</h3>
      </div>
      <div class="card-body">
        <canvas id="vacantPerDept"></canvas>
      </div>
    </div>
  </div>

  <!-- Vacancy by Position Level -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-layer-group"></i> Vacancy by Position Level</h3>
      </div>
      <div class="card-body">
        <canvas id="vacantByLevel"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Vacancy by Employment Type -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-warning shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-briefcase"></i> Vacancy by Employment Type</h3>
      </div>
      <div class="card-body">
        <canvas id="vacantByType"></canvas>
      </div>
    </div>
  </div>

  <!-- Critical Vacancies -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-danger shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Critical Vacancies</h3>
      </div>
      <div class="card-body">
        <table class="table table-sm table-bordered table-striped">
          <thead class="thead-light">
            <tr><th>Department</th><th>Position</th><th>Level</th><th>Remarks</th></tr>
          </thead>
          <tbody>
            <tr><td>CENRD</td><td>Engineer II</td><td>Technical</td><td>Priority Hire</td></tr>
            <tr><td>CEPWD</td><td>Foreman</td><td>Supervisory</td><td>For Immediate Filling</td></tr>
            <tr><td>CHSD</td><td>Nurse I</td><td>Technical</td><td>DOH Compliance</td></tr>
            <tr><td>CTD</td><td>Revenue Clerk</td><td>Clerical</td><td>Urgent Vacancy</td></tr>
            <tr><td>CCC</td><td>Instructor I</td><td>Professional</td><td>New Program Opening</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
setTimeout(() => {
  // Static data
  const deptLabels = ['CHRMD','CENRD','CEPWD','CTD','CCC','CHSD','CAIAD','CGSO','CPSD','CED','CTCAO'];
  const deptVacancies = [1,3,2,1,2,2,1,1,1,2,2];

  // Static vacant position details
  const vacantByDept = {
    'CHRMD': [{ position: 'Administrative Aide I', level: 'Clerical', type: 'Permanent' }],
    'CENRD': [
      { position: 'Engineer II', level: 'Technical', type: 'Permanent' },
      { position: 'Environmental Specialist I', level: 'Professional', type: 'Permanent' },
      { position: 'Field Assistant', level: 'Clerical', type: 'Job Order' }
    ],
    'CEPWD': [
      { position: 'Foreman', level: 'Supervisory', type: 'Permanent' },
      { position: 'Mason II', level: 'Technical', type: 'Job Order' }
    ],
    'CTD': [{ position: 'Revenue Clerk I', level: 'Clerical', type: 'Permanent' }],
    'CCC': [
      { position: 'Instructor I', level: 'Professional', type: 'Permanent' },
      { position: 'Registrar Clerk', level: 'Clerical', type: 'Contractual' }
    ],
    'CHSD': [
      { position: 'Nurse I', level: 'Technical', type: 'Permanent' },
      { position: 'Medical Technologist I', level: 'Professional', type: 'Permanent' }
    ],
    'CAIAD': [{ position: 'Auditor I', level: 'Professional', type: 'Permanent' }],
    'CGSO': [{ position: 'Storekeeper', level: 'Clerical', type: 'Job Order' }],
    'CPSD': [{ position: 'Safety Officer', level: 'Technical', type: 'Permanent' }],
    'CED': [
      { position: 'Teacher I', level: 'Professional', type: 'Permanent' },
      { position: 'Teacher II', level: 'Professional', type: 'Permanent' }
    ],
    'CTCAO': [
      { position: 'Cultural Officer', level: 'Professional', type: 'Contractual' },
      { position: 'Tourism Assistant', level: 'Clerical', type: 'Job Order' }
    ]
  };

  // Bar chart (clickable)
  const ctxDept = document.getElementById('vacantPerDept');
  const deptChart = new Chart(ctxDept, {
    type: 'bar',
    data: {
      labels: deptLabels,
      datasets: [{
        label: 'Vacant Posts',
        data: deptVacancies,
        backgroundColor: '#007bff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } },
      onClick: (evt, elements) => {
        if (elements.length > 0) {
          const index = elements[0].index;
          const dept = deptLabels[index];
          showVacancyDetails(dept, vacantByDept[dept]);
        }
      }
    }
  });

  // Pie chart
  new Chart(document.getElementById('vacantByLevel'), {
    type: 'pie',
    data: {
      labels: ['Clerical', 'Technical', 'Supervisory', 'Professional'],
      datasets: [{
        data: [5, 6, 3, 4],
        backgroundColor: ['#ffc107','#28a745','#17a2b8','#6f42c1']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  // Donut chart
  new Chart(document.getElementById('vacantByType'), {
    type: 'doughnut',
    data: {
      labels: ['Permanent', 'Contractual', 'Job Order', 'Casual'],
      datasets: [{
        data: [8, 3, 4, 3],
        backgroundColor: ['#007bff','#ffc107','#17a2b8','#dc3545']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  // Container for dynamic details
  const container = document.createElement('div');
  container.id = 'vacantDetails';
  document.querySelector('.content').appendChild(container);

  // Display Department Vacancies
  function showVacancyDetails(dept, vacancies) {
    container.innerHTML = '<div class="card mt-3 shadow-sm"><div class="card-header bg-light"><h3 class="card-title"><i class="fas fa-building"></i> Department Vacancies: ' + dept + '</h3></div><div class="card-body"><table class="table table-sm table-striped table-bordered"><thead class="thead-light"><tr><th>#</th><th>Position</th><th>Level</th><th>Type</th></tr></thead><tbody>' + vacancies.map((v, i) => '<tr><td>' + (i + 1) + '</td><td>' + v.position + '</td><td>' + v.level + '</td><td>' + v.type + '</td></tr>').join('') + '</tbody></table></div></div>';
    container.scrollIntoView({ behavior: 'smooth' });
  }

}, 400);
</script>
