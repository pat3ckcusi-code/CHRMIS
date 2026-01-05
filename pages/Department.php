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
          <h1>Department List</h1>
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
                        <button id="btnAddDept" class="btn btn-primary" data-toggle="modal" data-target="#addDeptModal">
                            <i class="fas fa-plus"></i> Add Department
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="DeptList" class="table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Department Code</th>
                                    <th>Department Name</th>
                                    <th>Department Head</th>
                                    <th>Designation</th>
                                    <th>Action</th>
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
include_once('../partials/modals/modal_edit_dept.php');
include_once('../partials/modals/modal_add_dept.php');

?>
<script>
var empTable = $('#DeptList').DataTable({
    responsive: true,
    order: [[1, "asc"]],
    ajax: {
        url: '../api/api_department.php?DeptList',
        dataSrc: function (json) {
            console.log(json); 
            return json.data;
        }
    },
    columns: [
        { data: 'DeptCode', title: 'Department Code', className: 'align-middle' },
        { data: 'Dept_name', title: 'Department Name', className: 'align-middle' },
        { data: 'Department_head', title: 'Department Head', className: 'align-middle' },
        { data: 'Designation', title: 'Designation', className: 'align-middle' },
        { 
            data: null, 
            title: 'Action',
            className: 'text-center align-middle',
            orderable: false,
            render: function (data, type, row) {
                return `
                    <button class="btn btn-sm btn-primary editDept" data-id="${row.Dept_id}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                `;
            }
        }
        
    ]
});

$(document).ready(function() {

    $(document).on('click', '#DeptList .editDept', function() {
        var Dept_id = $(this).data('id');

        $.ajax({
            url: '../api/api_department.php?EditDept',
            type: 'GET',
            data: { id: Dept_id },
            dataType: 'json',
            success: function(response) {
                console.log("AJAX Response:", response); 

                if (response && !response.error) {
                    // Populate modal fields
                    $('#txtDeptCode').val(response.DeptCode);
                    $('#txtDeptName').val(response.Dept_name);
                    $('#txtDeptHead').val(response.Department_head);
                    $('#txtDesignation').val(response.Designation);
                    $('#deptId').val(response.Dept_id);

                    // Show modal
                    $('#ModalDept').modal('show');
                } else {
                    Swal.fire("Error", response.error || "Department not found", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error, xhr.responseText);
                Swal.fire("Error", "Could not fetch department data.", "error");
            }
        });
    });


    $('#saveDeptChanges').on('click', function() {
        var formData = $('#DeptEditForm').serialize(); 

        $.ajax({
            url: '../api/api_department.php?UpdateDept', 
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire("Success", "Department updated successfully", "success");
                    $('#ModalDept').modal('hide');
                    $('#DeptList').DataTable().ajax.reload(); 
                } else {
                    Swal.fire("Error", response.error || "Update failed", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("Update AJAX error:", error);
            }
        });
    });
});





</script>

