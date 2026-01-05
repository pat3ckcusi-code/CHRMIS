<?php
require_once('../includes/initialize.php');
include_once( PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

//require_once('../includes/session_config.php');
session_start();
if(!isset($_SESSION['Status'])){
  header("location: ../index.php");
}else{
      $access_level = $_SESSION['Status'];
    }
?> 
<!-- navbar top and side -->
 <?php include("../includes/navbar.php"); ?>
<!-- end navbar top and side -->

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Approved Leave Application</h1>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
 <section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">                    
                    <div class="card-tools">
                        <!-- Date Range Picker -->
                        <div id="reportrange" 
                             style="cursor: pointer; padding: 6px 12px; border: 1px solid #fff; background: #0d6efd; border-radius: 4px; color: #fff;">
                            <i class="far fa-calendar-alt"></i>&nbsp;
                            <span>Select Date Range</span> 
                            <i class="fas fa-caret-down"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="leaveapp_table" class="table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Type of Leave</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Date Filed</th>
                                    <th>Department</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded dynamically via DataTables or PHP -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
<?php
include_once('../partials/footer.php');
include_once('../partials/modals/modal_add_user.php');
?>
<script>	  
	 $(function () {

    // Initialize Date Range Picker first
    $('#reportrange').daterangepicker(
        {
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    // Initialize DataTable AFTER date picker exists
    var usersTable = $('#leaveapp_table').DataTable({
        responsive: true,
        order: [[0, "asc"]],
        ajax: {
            url: '../api/api_leave.php?leaveapp_table', 
            data: function (d) {
                var drp = $('#reportrange').data('daterangepicker');
                if (drp) {
                    d.startDate = drp.startDate.format('YYYY-MM-DD');
                    d.endDate = drp.endDate.format('YYYY-MM-DD');
                }
            },
            dataSrc: 'data'
        },
       columns: [
            { data: 'Name', title: 'Employee Name', className: 'align-middle' },
            { data: 'LeaveType', title: 'Type of Leave', className: 'align-middle' },
            { data: 'DateFrom', title: 'From', className: 'align-middle' },
            { data: 'DateTo', title: 'To', className: 'align-middle' },
            { data: 'DateFiled', title: 'Date Filed', className: 'align-middle' },
            { data: 'Dept', title: 'Department', className: 'align-middle' },
            { data: 'printButton', title: 'Print', className: 'text-center', orderable: false, searchable: false }
        ]

    });

    // Reload DataTable when date range changes
    $('#reportrange').on('apply.daterangepicker', function () {
        usersTable.ajax.reload();
    });
});

</script>
