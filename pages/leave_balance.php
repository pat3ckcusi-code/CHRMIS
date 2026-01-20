<?php
require_once '../includes/auth.php';
include '../partials/modals/modal_apply_leave.php';
?>

<div class="row">
  <!-- LEFT CARD -->
  <div class="col-md-4">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-id-badge"></i> Current Status
        </h3>
      </div>
      <div class="card-body text-center">
        <i class="fas fa-plane-departure fa-3x text-primary"></i>
        <h4 class="mt-3">Vacation Leave Available</h4>
        <p class="text-muted">Next leave balance as of <span id="asOfDate"></span></p>
        <h3>
          <span class="badge badge-success" id="vlBalance">-- Days</span>
        </h3>
        <a href="#" class="btn btn-primary btn-block mt-3"
           data-toggle="modal" data-target="#applyLeaveModal">
          <i class="fas fa-plus-circle"></i> File Leave Application
        </a>
      </div>
    </div>
  </div>

  <!-- SUMMARY CARDS -->
  <div class="col-md-8">
    <div class="card card-info card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-clipboard-list"></i> Leave Summary
        </h3>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6 col-md-3">
            <i class="fas fa-plane fa-2x text-primary"></i>
            <h6 class="mt-2">Vacation</h6>
            <span class="badge badge-primary" id="vacation">-- Days</span>
          </div>
          <div class="col-6 col-md-3">
            <i class="fas fa-user-md fa-2x text-danger"></i>
            <h6 class="mt-2">Sick</h6>
            <span class="badge badge-danger" id="sick">-- Days</span>
          </div>
          <div class="col-6 col-md-3">
            <i class="fas fa-clock fa-2x text-warning"></i>
            <h6 class="mt-2">CTO</h6>
            <span class="badge badge-warning" id="cto">-- Days</span>
          </div>
          <div class="col-6 col-md-3">
            <i class="fas fa-star fa-2x text-success"></i>
            <h6 class="mt-2">Special</h6>
            <span class="badge badge-success" id="special">-- Days</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FILED LEAVES TABLE -->
<div class="row mt-3">
  <div class="col-12">
    <div class="card card-outline card-secondary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-file-alt"></i> Filed Leaves
        </h3>
      </div>
      <div class="card-body">
        <table id="filedLeavesTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Date Filed</th>
              <th>Type</th>
              <th>Date From</th>
              <th>Date To</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- CTO TABLE (unchanged visual) -->
<div class="row mt-3">
  <div class="col-12">
    <div class="card card-warning card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-clock"></i> Compensatory Time Off
        </h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Date Earned</th>
              <th>Hours</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>2025-09-10</td>
              <td>4 hrs</td>
              <td>Overtime Duty</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="../dist/js/leave.js"></script>
