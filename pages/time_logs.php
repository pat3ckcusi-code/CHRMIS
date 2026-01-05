<?php
// pages/time_logs.php
?>

<!-- Quick Stats -->
<div class="row">
  <div class="col-lg-6 col-12">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>4</h3>
        <p>Tardiness</p>
      </div>
      <div class="icon"><i class="fas fa-user-clock"></i></div>
    </div>
  </div>
  <div class="col-lg-6 col-12">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>2</h3>
        <p>Undertime</p>
      </div>
      <div class="icon"><i class="fas fa-hourglass-half"></i></div>
    </div>
  </div>
</div>

<!-- Daily Time Records Table -->
<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-info card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Daily Time Records</h3>
      </div>
      <div class="card-body">
        <table id="timeLogsTable" class="table table-bordered table-striped text-center">
          <thead>
            <tr>
              <th>Date</th>
              <th>AM In</th>
              <th>AM Out</th>
              <th>PM In</th>
              <th>PM Out</th>
              <th>Status</th>
              <th>Total Hours</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>2025-09-17</td>
              <td>08:12 AM</td>
              <td>12:00 PM</td>
              <td>01:00 PM</td>
              <td>05:10 PM</td>
              <td><span class="badge badge-success"><i class="fas fa-check-circle"></i> Present</span></td>
              <td>8h 58m</td>
            </tr>
            <tr>
              <td>2025-09-16</td>
              <td>08:45 AM</td>
              <td>12:05 PM</td>
              <td>01:10 PM</td>
              <td>05:05 PM</td>
              <td><span class="badge badge-danger"><i class="fas fa-clock"></i> Late</span></td>
              <td>8h 20m</td>
            </tr>
            <tr>
              <td>2025-09-15</td>
              <td>08:10 AM</td>
              <td>11:50 AM</td>
              <td>01:05 PM</td>
              <td>04:30 PM</td>
              <td><span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Undertime</span></td>
              <td>7h 50m</td>
            </tr>
            <tr>
              <td>2025-09-14</td>
              <td>08:05 AM</td>
              <td>12:00 PM</td>
              <td>01:00 PM</td>
              <td>05:15 PM</td>
              <td><span class="badge badge-success"><i class="fas fa-check-circle"></i> Present</span></td>
              <td>9h 10m</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
