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
                    <div class="table-responsive">
                        <table id="EmpList" class="table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Name</th>
                                    <th>Department</th>
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
include_once('../partials/modals/modal_EmpList.php');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var empTable = $('#EmpList').DataTable({
    responsive: true,
    order: [[1, "asc"]],
    ajax: {
        url: '../api/api_users.php?EmpList',
        dataSrc: function (json) {
            console.log(json); 
            return json.data;
        }
    },
    columns: [
        { data: 'EmpNo', title: 'Employee Number', className: 'align-middle' },
        { data: 'Name', title: 'Name', className: 'align-middle' },
        { 
            data: 'Dept', 
            title: 'Department', 
            className: 'align-middle',
            render: function (data, type, row) {
                return `<a href="#"
                            class="open-employee"
                            data-id="${row.EmpNo}">
                            ${data}
                        </a>`;
            }
        },
        {   
        data: null,
        title: 'Action',
        className: 'align-middle text-center',
        orderable: false,
        render: function (data, type, row) {
        return '<button class="btn btn-sm btn-warning reset-password" ' +
               'data-id="' + row.EmpNo + '" ' +
               'data-name="' + row.Name + '">' +
               'Reset Password</button>';
            }
        }
    ]
});
 

    $(document).on('click', '.open-employee', function(e) {
        e.preventDefault();

        // Get Employee Number 
        var empNo = $(this).data('id');
        console.log('Clicked Employee No:', empNo);

            
            $('#empNo').val(empNo);  
       
        $.ajax({
            url: '../api/api_users.php',
            type: 'GET',
            data: {
                get_employee: true,
                id: empNo
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    Swal.fire({
                        title: "Error",
                        text: response.error,
                        icon: "error",
                        confirmButtonColor: "#d33",
                        customClass: {
                            popup: "animated fadeInDown rounded-lg shadow-lg"
                        }
                    });
                } else {
                    // load modal fields
                    $('#ModalEmpList #txtEmpNum').val(response.EmpNo);
                    $('#ModalEmpList #txtLname').val(response.Lname);
                    $('#ModalEmpList #txtFname').val(response.Fname);
                    $('#ModalEmpList #txtMname').val(response.Mname);
                    $('#ModalEmpList #txtExt').val(response.Extension);

                    if (response.Gender === "Male") {
                        $('#ModalEmpList #rdMale').prop('checked', true);
                        $('#ModalEmpList #rdFemale').prop('checked', false);
                    } else {
                        $('#ModalEmpList #rdMale').prop('checked', false);
                        $('#ModalEmpList #rdFemale').prop('checked', true);
                    }

                    $('#ModalEmpList input[name="rdHRCivil"]').prop('checked', false);                    
                    $('#ModalEmpList input[name="rdHRCivil"][value="' + response.Civil + '"]').prop('checked', true);
                    $('#ModalEmpList #txtHRDate').val(response.BirthDate);
                    $('#ModalEmpList #ddDept').val(response.Dept);

                    // Show the modal
                    $('#ModalEmpList').modal('show');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Server Error",
                    text: "Unable to fetch employee details. Please try again later.",
                    icon: "error",
                    confirmButtonColor: "#d33",
                    customClass: {
                        popup: "animated fadeInDown rounded-lg shadow-lg"
                    }
                });
                console.error("AJAX Error:", status, error);
            }
        });
    });

    $(document).on('click', '.reset-password', function () {
    let empId = $(this).data('id');
    let empName = $(this).data('name');

    Swal.fire({
        title: "Are you sure?",
        text: "Do you really want to reset the password for " + empName + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, reset it!",
        cancelButtonText: "No, keep it"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../api/api_user_password.php",
                type: "PUT",
                data: { user_id: empId },
                success: function (response) {
                    Swal.fire("Success", "Password reset successfully!", "success");
                },
                error: function () {
                    Swal.fire("Error", "Failed to reset password.", "error");
                }
            });
        }
    });
});


</script>

