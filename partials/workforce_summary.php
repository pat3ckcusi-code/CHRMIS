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
<!-- Employees per Department -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-body scroll-chart">
        <div class="bar-chart-container">
          <canvas id="deptChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

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
          while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<option value=\"{$row['Dept']}\">{$row['Dept']}</option>";
          }
          ?>
        </select>
      </div>
    </div>
  </div>
</div>

<!-- FIRST ROW: Gender & Employment Status -->
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

<!-- SECOND ROW: Age & Tenure -->
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

<!-- JS -->
<script>
// Employees per Department 
async function loadDeptChart(){
  try{
    const res = await fetch("../api/workforce_data.php");
    const data = await res.json();
    if(!data || !Array.isArray(data.dept)) throw new Error("Invalid data");

    const labels = data.dept.map(d=>d.Dept);
    const counts = data.dept.map(d=>parseInt(d.total));

    const ctx = document.getElementById("deptChart").getContext("2d");
    if(deptChart) deptChart.destroy();

    deptChart = new Chart(ctx,{
      type:"bar",
      data:{
        labels,
        datasets:[{
          data: counts,
          backgroundColor:"rgba(54,162,235,0.6)",
          borderColor:"rgba(54,162,235,1)",
          borderWidth:1
        }]
      },
      options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
          legend:{ display:false },
          tooltip:{ enabled:true }
        },
        scales:{
          x:{
            display:false
          },
          y:{
            beginAtZero:true,
            title:{ display:true, text:"Total Employees" }
          }
        },
        onClick:(e)=>{
          const points = deptChart.getElementsAtEventForMode(
            e,
            'nearest',
            {intersect:true},
            true
          );
          if(points.length){
            const index = points[0].index;
            const selectedDept = labels[index];
            showDeptEmployees(selectedDept);
          }
        }
      }
    });
  }catch(err){
    console.error("Failed to load Employees per Department chart:", err);
    Swal.fire("Error","Failed to load Employees per Department chart","error");
  }
}


//Show Employees in popup 
async function showDeptEmployees(dept) {
  try {
    const res = await fetch(`../api/get_department_employees.php?dept=${encodeURIComponent(dept)}`);
    const employees = await res.json();

    if (!employees.length) {
      Swal.fire("Info", "No employees found", "info");
      return;
    }

    //STATE
    let filtered = [...employees];
    let currentPage = 1;
    const rowsPerPage = 10;
    let sortColumn = null;
    let sortAsc = true;

    // HELPERS 
    const colKeys = ["EmpNo", "FullName", "Position", "Gender", "Age", "EmploymentStatus", "date_hired"];

    function renderRows() {
      const start = (currentPage - 1) * rowsPerPage;
      const pageData = filtered.slice(start, start + rowsPerPage);

      return pageData
        .map(emp => `
          <tr>
            <td>${emp.EmpNo}</td>
            <td>${emp.FullName}</td>
            <td>${emp.Position ?? ''}</td>
            <td>${emp.Gender}</td>
            <td>${emp.Age}</td>
            <td>${emp.EmploymentStatus}</td>
            <td>${emp.date_hired}</td>
          </tr>
        `)
        .join("");
    }

    function applySort(colIndex) {
      const key = colKeys[colIndex];

      if (sortColumn === colIndex) sortAsc = !sortAsc;
      else {
        sortColumn = colIndex;
        sortAsc = true;
      }

      filtered.sort((a, b) => {
        const valA = (a[key] ?? "").toString().toLowerCase();
        const valB = (b[key] ?? "").toString().toLowerCase();

        // numeric sort where needed
        if (key === "Age" || key === "EmpNo") {
          return sortAsc
            ? (parseInt(valA) || 0) - (parseInt(valB) || 0)
            : (parseInt(valB) || 0) - (parseInt(valA) || 0);
        }

        return sortAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
      });

      currentPage = 1;
      updateTable();
    }

    function applySearch(keyword) {
      keyword = keyword.toLowerCase();

      filtered = employees.filter(emp =>
        Object.values(emp).some(v =>
          String(v).toLowerCase().includes(keyword)
        )
      );

      currentPage = 1;
      updateTable();
    }

    function updateTable() {
      document.getElementById("empTableBody").innerHTML = renderRows();
      document.getElementById("pageIndicator").innerText =
        `Page ${currentPage} of ${Math.ceil(filtered.length / rowsPerPage)}`;
    }

    
    Swal.fire({
      title: dept,
      width: "1100px",
      html: `
      <style>
        .emp-table-container { max-height: 550px; overflow: auto; }
        .emp-table { width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 14px; }
        .emp-table thead th {
          position: sticky; top: 0; background: #f1f1f1;
          cursor: pointer; padding: 8px; border-bottom: 2px solid #ccc;
          white-space: nowrap;
        }
        .emp-table tbody td {
          padding: 6px 8px; border-bottom: 1px solid #e1e1e1;
          white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .emp-table th:nth-child(2), .emp-table td:nth-child(2) { width: 220px; }
        .emp-table th:nth-child(3), .emp-table td:nth-child(3) { width: 200px; }
        .emp-table th:nth-child(1), .emp-table td:nth-child(1),
        .emp-table th:nth-child(4), .emp-table td:nth-child(4),
        .emp-table th:nth-child(5), .emp-table td:nth-child(5) { width: 80px; }
        .emp-table th:nth-child(6), .emp-table td:nth-child(6),
        .emp-table th:nth-child(7), .emp-table td:nth-child(7) { width: 140px; }
      </style>

      <input id="searchBox" type="text" class="form-control mb-2"
             placeholder="Search..." />

      <div class="emp-table-container">
        <table class="emp-table">
          <thead>
            <tr>
              <th data-col="0">EmpNo</th>
              <th data-col="1">Name</th>
              <th data-col="2">Position</th>
              <th data-col="3">Gender</th>
              <th data-col="4">Age</th>
              <th data-col="5">Status</th>
              <th data-col="6">Date Hired</th>
            </tr>
          </thead>
          <tbody id="empTableBody"></tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between mt-2">
        <button id="prevBtn" class="btn btn-sm btn-secondary">Prev</button>
        <span id="pageIndicator"></span>
        <button id="nextBtn" class="btn btn-sm btn-secondary">Next</button>
      </div>
      `,
      didOpen: () => {
        // Search
        document.getElementById("searchBox").addEventListener("input", e =>
          applySearch(e.target.value)
        );

        // Sorting 
        document.querySelectorAll(".emp-table thead th").forEach(th => {
          th.addEventListener("click", () =>
            applySort(parseInt(th.dataset.col))
          );
        });

        // Pagination
        document.getElementById("prevBtn").onclick = () => {
          if (currentPage > 1) {
            currentPage--;
            updateTable();
          }
        };

        document.getElementById("nextBtn").onclick = () => {
          if (currentPage < Math.ceil(filtered.length / rowsPerPage)) {
            currentPage++;
            updateTable();
          }
        };

        // Initial render
        updateTable();
      },
      confirmButtonText: "Close"
    });

  } catch (err) {
    console.error(err);
    Swal.fire("Error", "Failed to load employee list", "error");
  }
}







// Main Dashboard Charts
function initializeCharts(dept=''){
  const ctxGender = document.getElementById('genderChart').getContext('2d');
  const ctxStatus = document.getElementById('statusChart').getContext('2d');
  const ctxAge = document.getElementById('ageChart').getContext('2d');
  const ctxTenure = document.getElementById('tenureChart').getContext('2d');

  $(".charts-row").fadeTo(200,0.2);

  fetch(`../api/workforce_data.php?dept=${encodeURIComponent(dept)}`)
    .then(res=>res.json())
    .then(data=>{
      if(!data) return console.warn('No data');

      for(const key in charts) if(charts[key]) charts[key].destroy();

      const ageOrder=['<30','30-39','40-49','50-59','60+'];
      const tenureOrder=['<1 yr','1-4 yrs','5-9 yrs','10-14 yrs','15-19 yrs','20+ yrs'];
      data.age.sort((a,b)=>ageOrder.indexOf(a.AgeGroup)-ageOrder.indexOf(b.AgeGroup));
      data.tenure.sort((a,b)=>tenureOrder.indexOf(a.ServiceLength)-tenureOrder.indexOf(b.ServiceLength));

      const commonOptions={responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'},tooltip:{enabled:true}},interaction:{mode:'nearest',intersect:false},animation:{duration:800,easing:'easeOutQuart'}};

      charts.gender=new Chart(ctxGender,{type:'doughnut',data:{labels:data.gender.map(g=>g.Gender||'Unknown'),datasets:[{data:data.gender.map(g=>g.total),backgroundColor:['#36A2EB','#FF6384']}]},options:commonOptions});
      charts.status=new Chart(ctxStatus,{type:'pie',data:{labels:data.status.map(s=>s.EmploymentStatus||'Unknown'),datasets:[{data:data.status.map(s=>s.total),backgroundColor:['#FFCE56','#4BC0C0','#9966FF','#FF9F40','#FF6384']}]},options:commonOptions});
      charts.age=new Chart(ctxAge,{
        type:'bar',
        data:{
          labels:data.age.map(a=>a.AgeGroup),
          datasets:[{label:'Employees',data:data.age.map(a=>a.total),backgroundColor:'#FF9F40'}]
        },
        options:{
          ...commonOptions,
          scales:{y:{beginAtZero:true,ticks:{precision:0}}},
          onClick: async function(e, elements) {
            // Chart.js v3+ passes elements as 2nd arg
            let points = elements && elements.length ? elements : this.getElementsAtEventForMode(e, 'nearest', {intersect:true}, true);
            if(points.length){
              const index = points[0].index;
              const selectedAgeGroup = data.age[index].AgeGroup;
              await showAgeGroupEmployees(selectedAgeGroup, dept);
            }
          }
        }
      });
      // Tenure chart: use CSC Loyalty Award milestone years
      const milestoneYears = [10,15,20,25,30,35,40];

      // fetch employee list to compute counts per milestone (exact years)
      fetch(`../api/get_department_employees.php?dept=${encodeURIComponent(dept)}`)
        .then(r=>r.json())
        .then(employees=>{
          const counts = milestoneYears.map(m => {
            return employees.reduce((acc, emp) => {
              const hired = new Date(emp.date_hired);
              if (isNaN(hired)) return acc;
              const today = new Date();
              const years = today.getFullYear() - hired.getFullYear() - (today < new Date(today.getFullYear(), hired.getMonth(), hired.getDate()) ? 1 : 0);
              return acc + (years === m ? 1 : 0);
            }, 0);
          });

          charts.tenure = new Chart(ctxTenure,{
            type: 'bar',
            data: {
              labels: milestoneYears.map(y=>`${y} yrs`),
              datasets: [{ label: 'Employees', data: counts, backgroundColor: '#2600ffff' }]
            },
            options: {
              ...commonOptions,
              scales: { y: { beginAtZero:true, ticks:{precision:0} } },
              onClick: async function(e, elements) {
                let points = elements && elements.length ? elements : this.getElementsAtEventForMode(e, 'nearest', {intersect:true}, true);
                if(points.length){
                  const index = points[0].index;
                  const selectedYear = milestoneYears[index];
                  await showTenureEmployees(`${selectedYear} yrs`, dept);
                }
              }
            }
          });
        })
        .catch(err=>{
          console.error('Failed to load tenure employee list:', err);
        });
// Show employees in selected length of service milestone (CSC Loyalty Award)
async function showTenureEmployees(serviceLength, dept='') {
  try {
    const milestone = parseInt(serviceLength);
    if (isNaN(milestone)) {
      Swal.fire("Info", `Invalid milestone: ${serviceLength}` , "info");
      return;
    }

    // Fetch all employees for the department (or all)
    const res = await fetch(`../api/get_department_employees.php?dept=${encodeURIComponent(dept)}`);
    const employees = await res.json();

    function getYears(emp) {
      const hired = new Date(emp.date_hired);
      if (isNaN(hired)) return null;
      const today = new Date();
      return today.getFullYear() - hired.getFullYear() - (today < new Date(today.getFullYear(), hired.getMonth(), hired.getDate()) ? 1 : 0);
    }

    function getAward(years) {
      if ([10,15].includes(years)) return 'Bronze';
      if ([20,25].includes(years)) return 'Silver';
      if ([30,35,40].includes(years)) return 'Gold';
      return '';
    }

    // Filter employees who exactly match the milestone year
    let filtered = employees.filter(emp => getYears(emp) === milestone);

    if (!filtered.length) {
      Swal.fire("Info", `No employees found for milestone ${serviceLength}` , "info");
      return;
    }

    // Modal state and helpers (search, sort, pagination)
    let currentPage = 1;
    const rowsPerPage = 10;
    let sortColumn = null;
    let sortAsc = true;
    let searchKeyword = '';
    const colKeys = ["EmpNo", "FullName", "Position", "Dept", "Gender", "Age", "EmploymentStatus", "Award", "date_hired"];

    function renderRows() {
      const start = (currentPage - 1) * rowsPerPage;
      const pageData = filtered.slice(start, start + rowsPerPage);
      return pageData.map(emp => {
        const years = getYears(emp);
        const award = getAward(years);
        return `
          <tr>
            <td>${emp.EmpNo}</td>
            <td>${emp.FullName}</td>
            <td>${emp.Position ?? ''}</td>
            <td>${emp.Dept ?? ''}</td>
            <td>${emp.Gender}</td>
            <td>${emp.Age}</td>
            <td>${emp.EmploymentStatus}</td>
            <td>${award}</td>
            <td>${emp.date_hired}</td>
          </tr>
        `;
      }).join("");
    }

    function applySort(colIndex) {
      const key = colKeys[colIndex];
      if (sortColumn === colIndex) sortAsc = !sortAsc;
      else {
        sortColumn = colIndex;
        sortAsc = true;
      }
      filtered.sort((a, b) => {
        let valA, valB;
        if (key === 'Award') {
          valA = getAward(getYears(a)) || '';
          valB = getAward(getYears(b)) || '';
        } else {
          valA = (a[key] ?? "").toString().toLowerCase();
          valB = (b[key] ?? "").toString().toLowerCase();
        }
        if (key === "Age" || key === "EmpNo") {
          return sortAsc
            ? (parseInt(valA) || 0) - (parseInt(valB) || 0)
            : (parseInt(valB) || 0) - (parseInt(valA) || 0);
        }
        return sortAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
      });
      currentPage = 1;
      updateTable();
    }

    function applySearch(keyword) {
      searchKeyword = keyword.toLowerCase();
      filtered = employees.filter(emp => {
        const years = getYears(emp);
        if (years !== milestone) return false;
        return Object.values(emp).some(v => String(v).toLowerCase().includes(searchKeyword)) || (getAward(years).toLowerCase().includes(searchKeyword));
      });
      currentPage = 1;
      updateTable();
    }

    function updateTable() {
      document.getElementById("tenureEmpTableBody").innerHTML = renderRows();
      document.getElementById("tenurePageIndicator").innerText =
        `Page ${currentPage} of ${Math.ceil(filtered.length / rowsPerPage)}`;
    }

    Swal.fire({
      title: `Employees with Length of Service: ${serviceLength}`,
      width: "1300px",
      html: `
        <style>
          .emp-table-container { max-height: 700px; overflow: auto; }
          .emp-table { width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 14px; }
          .emp-table thead th {
            position: sticky; top: 0; background: #f1f1f1;
            cursor: pointer; padding: 8px; border-bottom: 2px solid #ccc;
            white-space: nowrap;
          }
          .emp-table tbody td {
            padding: 6px 8px; border-bottom: 1px solid #e1e1e1;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
          }
          .emp-table th:nth-child(1), .emp-table td:nth-child(1) { width: 80px; }
          .emp-table th:nth-child(2), .emp-table td:nth-child(2) { width: 240px; }
          .emp-table th:nth-child(3), .emp-table td:nth-child(3) { width: 180px; }
          .emp-table th:nth-child(4), .emp-table td:nth-child(4) { width: 200px; }
          .emp-table th:nth-child(5), .emp-table td:nth-child(5) { width: 60px; text-align:center; }
          .emp-table th:nth-child(6), .emp-table td:nth-child(6) { width: 50px; text-align:center; }
          .emp-table th:nth-child(7), .emp-table td:nth-child(7) { width: 120px; }
          .emp-table th:nth-child(8), .emp-table td:nth-child(8) { width: 100px; }
          .emp-table th:nth-child(9), .emp-table td:nth-child(9) { width: 120px; }
        </style>

        <input id="tenureSearchBox" type="text" class="form-control mb-2" placeholder="Search..." />

        <div class="emp-table-container">
          <table class="emp-table">
            <thead>
              <tr>
                <th data-col="0">EmpNo</th>
                <th data-col="1">Name</th>
                <th data-col="2">Position</th>
                <th data-col="3">Department</th>
                <th data-col="4">Gender</th>
                <th data-col="5">Age</th>
                <th data-col="6">Status</th>
                <th data-col="7">Award</th>
                <th data-col="8">Date Hired</th>
              </tr>
            </thead>
            <tbody id="tenureEmpTableBody"></tbody>
          </table>
        </div>

        <div class="d-flex justify-content-between mt-2">
          <button id="tenurePrevBtn" class="btn btn-sm btn-secondary">Prev</button>
          <span id="tenurePageIndicator"></span>
          <button id="tenureNextBtn" class="btn btn-sm btn-secondary">Next</button>
        </div>
      `,
      didOpen: () => {
        document.getElementById("tenureSearchBox").addEventListener("input", e =>
          applySearch(e.target.value)
        );
        document.querySelectorAll(".emp-table thead th").forEach(th => {
          th.addEventListener("click", () =>
            applySort(parseInt(th.dataset.col))
          );
        });
        document.getElementById("tenurePrevBtn").onclick = () => {
          if (currentPage > 1) {
            currentPage--;
            updateTable();
          }
        };
        document.getElementById("tenureNextBtn").onclick = () => {
          if (currentPage < Math.ceil(filtered.length / rowsPerPage)) {
            currentPage++;
            updateTable();
          }
        };
        updateTable();
      },
      confirmButtonText: "Close"
    });
  } catch (err) {
    console.error(err);
    Swal.fire("Error", "Failed to load employee list for length of service", "error");
  }
}

      $(".charts-row").fadeTo(300,1);
    })
    .catch(err=>console.error("Fetch error:",err));
}

// Show employees in selected age group in a modal
async function showAgeGroupEmployees(ageGroup, dept='') {
  try {
    // Fetch all employees for the department (or all)
    const res = await fetch(`../api/get_department_employees.php?dept=${encodeURIComponent(dept)}`);
    const employees = await res.json();
    // Filter by age group
    let filtered = employees.filter(emp => {
      const age = parseInt(emp.Age);
      if (ageGroup === '<30') return age < 30;
      if (ageGroup === '30-39') return age >= 30 && age <= 39;
      if (ageGroup === '40-49') return age >= 40 && age <= 49;
      if (ageGroup === '50-59') return age >= 50 && age <= 59;
      if (ageGroup === '60+') return age >= 60;
      return false;
    });

    if (!filtered.length) {
      Swal.fire("Info", `No employees found for age group ${ageGroup}` , "info");
      return;
    }

    // Modal state and helpers (search, sort, pagination)
    let currentPage = 1;
    const rowsPerPage = 10;
    let sortColumn = null;
    let sortAsc = true;
    let searchKeyword = '';
    const colKeys = ["EmpNo", "FullName", "Position", "Dept", "Gender", "Age", "EmploymentStatus", "date_hired"];

    function renderRows() {
      const start = (currentPage - 1) * rowsPerPage;
      const pageData = filtered.slice(start, start + rowsPerPage);
      return pageData.map(emp => `
        <tr>
          <td>${emp.EmpNo}</td>
          <td>${emp.FullName}</td>
          <td>${emp.Position ?? ''}</td>
          <td>${emp.Dept ?? ''}</td>
          <td>${emp.Gender}</td>
          <td>${emp.Age}</td>
          <td>${emp.EmploymentStatus}</td>
          <td>${emp.date_hired}</td>
        </tr>
      `).join("");
    }

    function applySort(colIndex) {
      const key = colKeys[colIndex];
      if (sortColumn === colIndex) sortAsc = !sortAsc;
      else {
        sortColumn = colIndex;
        sortAsc = true;
      }
      filtered.sort((a, b) => {
        const valA = (a[key] ?? "").toString().toLowerCase();
        const valB = (b[key] ?? "").toString().toLowerCase();
        if (key === "Age" || key === "EmpNo") {
          return sortAsc
            ? (parseInt(valA) || 0) - (parseInt(valB) || 0)
            : (parseInt(valB) || 0) - (parseInt(valA) || 0);
        }
        return sortAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
      });
      currentPage = 1;
      updateTable();
    }

    function applySearch(keyword) {
      searchKeyword = keyword.toLowerCase();
      filtered = employees.filter(emp =>
        Object.values(emp).some(v =>
          String(v).toLowerCase().includes(searchKeyword)
        ) && (function() {
          const age = parseInt(emp.Age);
          if (ageGroup === '<30') return age < 30;
          if (ageGroup === '30-39') return age >= 30 && age <= 39;
          if (ageGroup === '40-49') return age >= 40 && age <= 49;
          if (ageGroup === '50-59') return age >= 50 && age <= 59;
          if (ageGroup === '60+') return age >= 60;
          return false;
        })()
      );
      currentPage = 1;
      updateTable();
    }

    function updateTable() {
      document.getElementById("ageEmpTableBody").innerHTML = renderRows();
      document.getElementById("agePageIndicator").innerText =
        `Page ${currentPage} of ${Math.ceil(filtered.length / rowsPerPage)}`;
    }

    Swal.fire({
      title: `Employees in Age Group: ${ageGroup}`,
      width: "1300px",
      html: `
        <style>
          .emp-table-container { max-height: 700px; overflow: auto; }
          .emp-table { width: 100%; table-layout: fixed; border-collapse: collapse; font-size: 14px; }
          .emp-table thead th {
            position: sticky; top: 0; background: #f1f1f1;
            cursor: pointer; padding: 8px; border-bottom: 2px solid #ccc;
            white-space: nowrap;
          }
          .emp-table tbody td {
            padding: 6px 8px; border-bottom: 1px solid #e1e1e1;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
          }
          .emp-table th:nth-child(1), .emp-table td:nth-child(1) { width: 80px; }
          .emp-table th:nth-child(2), .emp-table td:nth-child(2) { width: 240px; }
          .emp-table th:nth-child(3), .emp-table td:nth-child(3) { width: 180px; }
          .emp-table th:nth-child(4), .emp-table td:nth-child(4) { width: 200px; }
          .emp-table th:nth-child(5), .emp-table td:nth-child(5) { width: 60px; text-align:center; }
          .emp-table th:nth-child(6), .emp-table td:nth-child(6) { width: 50px; text-align:center; }
          .emp-table th:nth-child(7), .emp-table td:nth-child(7) { width: 120px; }
          .emp-table th:nth-child(8), .emp-table td:nth-child(8) { width: 120px; }
        </style>

        <input id="ageSearchBox" type="text" class="form-control mb-2" placeholder="Search..." />

        <div class="emp-table-container">
          <table class="emp-table">
            <thead>
              <tr>
                <th data-col="0">EmpNo</th>
                <th data-col="1">Name</th>
                <th data-col="2">Position</th>
                <th data-col="3">Department</th>
                <th data-col="4">Gender</th>
                <th data-col="5">Age</th>
                <th data-col="6">Status</th>
                <th data-col="7">Date Hired</th>
              </tr>
            </thead>
            <tbody id="ageEmpTableBody"></tbody>
          </table>
        </div>

        <div class="d-flex justify-content-between mt-2">
          <button id="agePrevBtn" class="btn btn-sm btn-secondary">Prev</button>
          <span id="agePageIndicator"></span>
          <button id="ageNextBtn" class="btn btn-sm btn-secondary">Next</button>
        </div>
      `,
      didOpen: () => {
        document.getElementById("ageSearchBox").addEventListener("input", e =>
          applySearch(e.target.value)
        );
        document.querySelectorAll(".emp-table thead th").forEach(th => {
          th.addEventListener("click", () =>
            applySort(parseInt(th.dataset.col))
          );
        });
        document.getElementById("agePrevBtn").onclick = () => {
          if (currentPage > 1) {
            currentPage--;
            updateTable();
          }
        };
        document.getElementById("ageNextBtn").onclick = () => {
          if (currentPage < Math.ceil(filtered.length / rowsPerPage)) {
            currentPage++;
            updateTable();
          }
        };
        updateTable();
      },
      confirmButtonText: "Close"
    });
  } catch (err) {
    console.error(err);
    Swal.fire("Error", "Failed to load employee list for age group", "error");
  }
}
</script>
