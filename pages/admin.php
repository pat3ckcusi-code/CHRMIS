<?php
require_once('../includes/initialize.php');
include_once( PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

//require_once('../includes/session_config.php');
session_start();
if(!isset($_SESSION['access_level'])){
  header("location: ../index.php");
}else{
      $access_level = $_SESSION['access_level'];
    }
?>

 <!-- navbar top and side -->
 <?php
				include("../includes/navbar.php");
			?>
<!-- end navbar top and side -->

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Checklist Register</h1>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Checklist Register</h3>
              <div class="card-tools">
                
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="users_table" class="table table-bordered table-striped table-hover" style="width: 100%;">
                <thead class="bg-info" height="40">
                </thead>
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
  //User Table
  var usersTable = $('#users_table').DataTable({
    responsive: true,
    //bStateSave: true,
    order: [[ 0, "asc" ]],
    ajax: {
      url: '../api/api_users.php?users_table',
      dataSrc: 'data'
    },
    columnDefs: [
		            { responsivePriority: 1, targets: 0 },
		             { responsivePriority: 2, targets: 3 }
		            ],
    columns: [
      { data: 'EmpNo', title: 'Employee Number', className: 'text-center' },
      { data: 'EmpName', title: 'Employee Name', className: 'align-middle' },
      { data: 'Gender', title: 'Gender', className: 'align-middle' },
      { data: 'Age', title: 'Age', className: 'align-middle' },
      { data: 'Position', title: 'Position', className: 'align-middle' },
      { data: 'office', title: 'Office', className: 'align-middle' }
        ],
      });

  // Add User Account
      $('#addUserForm').submit(function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Information',
          text: "Are you sure you want to add this Result?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, save it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: '../api/api_users.php?add_useradmin',
              type: 'post',
              processData: false,
              contentType: false,
              data: new FormData(this)
            }).then(function(response) {
              if(response.success){
                Swal.fire('Success!', response.message, 'success')
              }else{
                Swal.fire('Warning!', response.message, 'warning')
              }

            })
          }
        })
      });
</script>
