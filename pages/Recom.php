<?php
require_once('../includes/session_config.php');
session_start();
if(!isset($_SESSION['Status'])){
  header("location: ../index.php");
}else{
      $access_level = $_SESSION['Status'];
    }
require_once('../includes/initialize.php');
include_once( PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
?> 
  <!-- Toastr -->
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
<!-- navbar top and side -->
 <?php include("../includes/navbar.php"); ?>
<!-- end navbar top and side -->

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Employee List</h1>
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
                        <!-- Date Range Picker or other tools -->
                    </div>
                </div>
                <div class="card-body">
                     <!-- Add Button -->
                    <div class="mb-3 text-end">
                        <button id="btnAddDept" class="btn btn-primary" data-toggle="modal" data-target="#addPersonnelModal">
                            <i class="fas fa-plus"></i> Add Personnel
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="RecomList" class="table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Department</th>
                                    <th>Contact Number</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
var empTable = $('#RecomList').DataTable({
    responsive: true,
    order: [[1, "asc"]],
    ajax: {
        url: '../api/api_users.php?RecomList',
        dataSrc: function (json) {
            console.log(json); 
            return json.data;
        }
    },
    columns: [
        { data: 'AcctName', title: 'Employee Name', className: 'align-middle' },
        { data: 'Dept', title: 'Department', className: 'align-middle' },
        { data: 'ContactNo', title: 'Contact Number', className: 'align-middle' },
    ]
});
 

    


</script>

