<!-- Departments / Offices Dashboard -->

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
  <!-- Summary KPIs -->
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#007bff;">
      <div class="summary-title">Total Departments</div>
      <div class="summary-value text-primary">46</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#28a745;">
      <div class="summary-title">Avg. Employees / Dept</div>
      <div class="summary-value text-success">19.6</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#17a2b8;">
      <div class="summary-title">Largest Department</div>
      <div class="summary-value text-info">CCC (60)</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#ffc107;">
      <div class="summary-title">Smallest Department</div>
      <div class="summary-value text-warning">PDAO (8)</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#dc3545;">
      <div class="summary-title">Avg. Performance</div>
      <div class="summary-value text-danger">92.3%</div>
    </div>
  </div>
  <div class="col-md-2 col-sm-4 mb-3">
    <div class="summary-box" style="border-color:#6f42c1;">
      <div class="summary-title">Avg. Attendance</div>
      <div class="summary-value text-purple">96.4%</div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Employee Distribution -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users"></i> Employees per Department</h3>
      </div>
      <div class="card-body">
        <canvas id="deptEmployeesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Top 5 Departments -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-trophy"></i> Top 5 Largest Departments</h3>
      </div>
      <div class="card-body">
        <canvas id="topDeptChart"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Performance Index -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-line"></i> Departmental Performance Index</h3>
      </div>
      <div class="card-body">
        <canvas id="perfChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Attendance Rate -->
  <div class="col-md-6 mb-3">
    <div class="card card-outline card-warning shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clock"></i> Departmental Attendance Rate</h3>
      </div>
      <div class="card-body">
        <canvas id="attendanceChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
setTimeout(() => {
  // ===== Static Data =====
  const deptLabels = [
    'CHRMD','City Mayor Office','Vice Mayor Office','Admin & Staff','Budget','Legal','Education','CCC',
    'Public Safety','Vet Services','DRRM','Nutrition','Population','Health','Treasury','Assessor',
    'Econ Enterprise','BPLO','Trade & Industry','Medical Health Care','Accounting','General Services',
    'BAC','Social Welfare','CPESO','Barangay Affairs','Civil Registry','Youth & Sports','Agriculture',
    'Cooperative','Housing','Architecture & Design','Environment','Engineering','Urban Planning',
    'MISO','Gender & Dev.','Fisheries','Vet (Plaza)','Senior Citizen','SP Secretariat','Library','PDAO',
    'Convention Center','Information Office','Tourism / Culture / Arts','Community Affairs'
  ];

  const deptEmployees = [
    28,60,15,22,18,12,26,60,40,20,22,15,10,55,30,24,32,18,16,14,25,22,10,38,14,12,18,20,28,10,14,16,24,26,12,14,8,8,15,10,10,8,8,12,10,8
  ];

  const deptPerformance = deptEmployees.map(()=>Math.floor(Math.random()*10)+88);
  const deptAttendance = deptEmployees.map(()=>Math.floor(Math.random()*5)+94);

  // Static employees per department (demo)
  const employeesByDept = {};
  deptLabels.forEach(label=>{
    const count = Math.min(10, Math.floor(Math.random()*8)+3);
    const positions = ['Administrative Aide', 'Office Staff', 'Engineer I', 'Accountant II', 'Development Officer', 'Nurse I', 'Clerk', 'IT Support', 'Officer-In-Charge'];
    const genders = ['Male','Female'];
    employeesByDept[label] = Array.from({length:count},(_,i)=>{
      return {
        name: `${label.split(' ')[0]} Employee ${i+1}`,
        position: positions[Math.floor(Math.random()*positions.length)],
        gender: genders[Math.floor(Math.random()*2)],
        age: Math.floor(Math.random()*25)+22
      };
    });
  });

  const baseOpts = {
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{y:{beginAtZero:true},x:{display:false}}
  };

  const deptChart = new Chart(document.getElementById('deptEmployeesChart'),{
    type:'bar',
    data:{labels:deptLabels,datasets:[{label:'Employees',data:deptEmployees,backgroundColor:'#007bff'}]},
    options:{
      ...baseOpts,
      onClick:(evt,elements)=>{
        if(elements.length>0){
          const idx = elements[0].index;
          const name = deptLabels[idx];
          showDeptDetails(name,deptEmployees[idx],deptPerformance[idx],deptAttendance[idx],employeesByDept[name]);
        }
      }
    }
  });

  // other charts
  new Chart(document.getElementById('topDeptChart'),{
    type:'bar',
    data:{
      labels:deptLabels.slice(0,5),
      datasets:[{label:'Employees',data:deptEmployees.slice(0,5),backgroundColor:'#28a745'}]
    },
    options:baseOpts
  });
  new Chart(document.getElementById('perfChart'),{
    type:'line',
    data:{labels:deptLabels,datasets:[{data:deptPerformance,borderColor:'#17a2b8',fill:false,tension:0.3}]},
    options:{...baseOpts,scales:{y:{min:85,max:100}}}
  });
  new Chart(document.getElementById('attendanceChart'),{
    type:'line',
    data:{labels:deptLabels,datasets:[{data:deptAttendance,borderColor:'#ffc107',fill:false,tension:0.3}]},
    options:{...baseOpts,scales:{y:{min:90,max:100}}}
  });

  // container for dynamic details
  const container = document.createElement('div');
  container.id = 'deptDetails';
  document.querySelector('.content').appendChild(container);

  // Show popup section with department info
  function showDeptDetails(name,count,perf,att,employees){
    container.innerHTML = '<div class="card mt-3 shadow-sm"><div class="card-header bg-light"><h3 class="card-title"><i class="fas fa-building"></i> Department Details: ' + name + '</h3></div><div class="card-body"><p><strong>Total Employees:</strong> ' + count + '</p><p><strong>Performance Index:</strong> ' + perf + '%</p><p><strong>Attendance Rate:</strong> ' + att + '%</p><div class="table-responsive"><table class="table table-sm table-striped table-bordered"><thead class="thead-light"><tr><th>#</th><th>Name</th><th>Position</th><th>Gender</th><th>Age</th></tr></thead><tbody>' + employees.map((e,i)=>'<tr><td>' + (i+1) + '</td><td>' + e.name + '</td><td>' + e.position + '</td><td>' + e.gender + '</td><td>' + e.age + '</td></tr>').join('') + '</tbody></table></div></div></div>';
    container.scrollIntoView({behavior:'smooth'});
  }
},300);
</script>
