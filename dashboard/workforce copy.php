<?php
if (!defined('PARTIALS_PATH')) require_once __DIR__ . '/../includes/initialize.php';
include_once(PARTIALS_PATH.'header.php');
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

    </div>
  </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fetch total workforce dynamically
fetch('/CHRMIS/api/workforce_data.php')
  .then(res => res.json())
  .then(data => {
    if (data.totalWorkforce !== undefined) {
      document.getElementById('totalWorkforce').innerText = data.totalWorkforce;
    }
  })
  .catch(err => console.error('Failed to fetch total workforce:', err));


document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll('.clickable-card');

  cards.forEach(card => {
    card.addEventListener('click', function() {
      cards.forEach(c=>c.classList.remove('active'));
      this.classList.add('active');

      const file = this.getAttribute('data-file');
      const title = this.querySelector('p').innerText;
      document.getElementById('dynamicTitle').innerHTML = `<i class="fas fa-chart-pie"></i> ${title}`;

      fetch(file).then(r=>r.text()).then(html=>{
        const contentDiv = document.getElementById('dynamicContent');
        contentDiv.innerHTML = html;
        initializeCharts();
      }).catch(()=>document.getElementById('dynamicContent').innerHTML="<p class='text-center text-danger'>Unable to load content.</p>");
    });
  });

  if(cards.length>0) cards[0].click();

  function initializeCharts() {
    fetch('/CHRMIS/api/workforce_data.php').then(r=>r.json()).then(data=>{
      // Department Chart
      new Chart(document.getElementById('deptChart').getContext('2d'),{
        type:'bar',
        data:{labels:data.department.map(d=>d.label),datasets:[{data:data.department.map(d=>d.total),backgroundColor:'#007bff'}]},
        options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{display:false},y:{beginAtZero:true}},
        onClick:(evt,elements)=>{
          if(elements.length>0){
            const dept = data.department[elements[0].index].label;
            showDeptEmployees(dept);
          }
        }}
      });

      // Gender Chart
      new Chart(document.getElementById('genderChart').getContext('2d'),{
        type:'doughnut',
        data:{labels:data.gender.map(d=>d.label),datasets:[{data:data.gender.map(d=>d.total),backgroundColor:['#007bff','#e83e8c']}]},
        options:{responsive:true,plugins:{legend:{position:'bottom'}}}
      });

      // Status Chart
      new Chart(document.getElementById('statusChart').getContext('2d'),{
        type:'pie',
        data:{labels:data.status.map(d=>d.label||'N/A'),datasets:[{data:data.status.map(d=>d.total),backgroundColor:['#28a745','#ffc107','#17a2b8','#dc3545']}]},
        options:{responsive:true,plugins:{legend:{position:'bottom'}}}
      });

      // Age Chart
      new Chart(document.getElementById('ageChart').getContext('2d'),{
        type:'bar',
        data:{labels:data.age.map(d=>d.label),datasets:[{data:data.age.map(d=>d.total),backgroundColor:'#ffc107'}]},
        options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
      });

      // Tenure Chart
      new Chart(document.getElementById('tenureChart').getContext('2d'),{
        type:'bar',
        data:{labels:data.tenure.map(d=>d.label),datasets:[{data:data.tenure.map(d=>d.total),backgroundColor:'#dc3545'}]},
        options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
      });
    }).catch(err=>console.error(err));
  }

  function showDeptEmployees(deptName) {
    fetch(`/CHRMIS/api/workforce_data.php?dept=${encodeURIComponent(deptName)}`)
      .then(r=>r.json())
      .then(data=>{
        const employees = data.employees || [];
        if(!employees.length) return Swal.fire('No Records',`No employees found for ${deptName}`,'info');

        const rows = employees.map((e,i)=>`<tr><td>${i+1}</td><td>${e.FullName}</td><td>${e.PositionTitle||'-'}</td><td>${e.Gender}</td><td>${e.Age}</td></tr>`).join('');

        Swal.fire({
          width:'70%',
          title:`<i class="fas fa-building"></i> ${deptName}`,
          html:`<div class="table-responsive" style="max-height:400px;overflow:auto;">
                  <table class="table table-sm table-striped table-bordered">
                    <thead class="table-light"><tr><th>#</th><th>Name</th><th>Position</th><th>Gender</th><th>Age</th></tr></thead>
                    <tbody>${rows}</tbody>
                  </table>
                </div>`,
          confirmButtonText:'Close'
        });
      })
      .catch(()=>Swal.fire('Error','Failed to fetch department employees','error'));
  }
});
</script>

<?php include_once('../partials/footer.php'); ?>
