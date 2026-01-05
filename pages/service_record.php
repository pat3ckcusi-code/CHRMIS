<?php
// pages/service_record.php
?>

<!-- Summary Boxes -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner"><h3>12</h3><p>Years of Service</p></div>
      <div class="icon"><i class="fas fa-calendar-alt"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner"><h3>IT Officer II</h3><p>Last Position</p></div>
      <div class="icon"><i class="fas fa-user-tie"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner"><h3>3</h3><p>Promotions</p></div>
      <div class="icon"><i class="fas fa-level-up-alt"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner"><h3>Regular</h3><p>Employment Status</p></div>
      <div class="icon"><i class="fas fa-briefcase"></i></div>
    </div>
  </div>
</div>

<!-- Career Timeline -->
<div class="card card-info card-outline mt-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-stream"></i> Career Timeline</h3>
  </div>
  <div class="card-body">
    <div class="timeline">
      <div class="time-label"><span class="bg-primary">2013</span></div>
      <div>
        <i class="fas fa-user-plus bg-green"></i>
        <div class="timeline-item">
          <span class="time"><i class="fas fa-clock"></i> Jan 2013</span>
          <h3 class="timeline-header">Appointed as <b>IT Support Staff</b></h3>
        </div>
      </div>
      <div>
        <i class="fas fa-level-up-alt bg-yellow"></i>
        <div class="timeline-item">
          <span class="time"><i class="fas fa-clock"></i> Mar 2016</span>
          <h3 class="timeline-header">Promoted to <b>IT Assistant</b></h3>
        </div>
      </div>
      <div>
        <i class="fas fa-exchange-alt bg-info"></i>
        <div class="timeline-item">
          <span class="time"><i class="fas fa-clock"></i> Jul 2019</span>
          <h3 class="timeline-header">Transferred to <b>Management Information Systems Dept.</b></h3>
        </div>
      </div>
      <div>
        <i class="fas fa-level-up-alt bg-red"></i>
        <div class="timeline-item">
          <span class="time"><i class="fas fa-clock"></i> May 2022</span>
          <h3 class="timeline-header">Promoted to <b>IT Officer II</b></h3>
        </div>
      </div>
      <div><i class="fas fa-clock bg-gray"></i></div>
    </div>
  </div>
</div>

<!-- Detailed Service Record Table -->
<div class="card card-primary card-outline mt-3">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-table"></i> Detailed Service Record</h3>
  </div>
  <div class="card-body">
    <table id="serviceRecordTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>From</th>
          <th>To</th>
          <th>Designation</th>
          <th>Status</th>
          <th>Department</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>2013-01-05</td>
          <td>2016-03-15</td>
          <td>IT Support Staff</td>
          <td>Probationary</td>
          <td>ICT Division</td>
          <td>Initial Appointment</td>
        </tr>
        <tr>
          <td>2016-03-16</td>
          <td>2019-07-01</td>
          <td>IT Assistant</td>
          <td>Contractual</td>
          <td>ICT Division</td>
          <td>Promoted</td>
        </tr>
        <tr>
          <td>2019-07-02</td>
          <td>2022-05-15</td>
          <td>IT Assistant</td>
          <td>Regular</td>
          <td>Management Information Systems</td>
          <td>Transfer</td>
        </tr>
        <tr>
          <td>2022-05-16</td>
          <td>Present</td>
          <td>IT Officer II</td>
          <td>Regular</td>
          <td>Management Information Systems</td>
          <td>Promoted</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
