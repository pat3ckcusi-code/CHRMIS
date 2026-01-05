<!-- Workforce Summary Dashboard -->

<style>
  canvas { width:100% !important; height:180px !important; }
  .scroll-chart { overflow-x:auto; white-space:nowrap; }
</style>

<div class="row">
  <div class="col-md-8 mb-3">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-building"></i> Employees per Department / Office</h3>
      </div>
      <div class="card-body scroll-chart">
        <canvas id="deptChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-venus-mars"></i> Gender Distribution</h3>
      </div>
      <div class="card-body">
        <canvas id="genderChart"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <div class="card card-outline card-success shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-briefcase"></i> Employment Status</h3>
      </div>
      <div class="card-body">
        <canvas id="statusChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card card-outline card-warning shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-birthday-cake"></i> Age Group Distribution</h3>
      </div>
      <div class="card-body">
        <canvas id="ageChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card card-outline card-danger shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-clock"></i> Length of Service</h3>
      </div>
      <div class="card-body">
        <canvas id="tenureChart"></canvas>
      </div>
    </div>
  </div>
</div>
