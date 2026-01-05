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
          <h1>Employee Leave Credits</h1>
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
                        <table id="EmpLeave" class="table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Name</th>
                                    <th>VL</th>
                                    <th>SL</th>
                                    <th>SPL</th>
                                    <th>CTO</th>
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
include_once('../partials/modals/modallatededuct.php');	
include_once('../partials/modals/modalAddCTO.php');
include_once('../partials/modals/modal_add_user.php');
?>
<script>
$(function () {    
    // Initialize DataTable
    var empTable = $('#EmpLeave').DataTable({
        responsive: true,
        order: [[1, "asc"]],
        ajax: {
            url: '../api/api_leave.php?empleave_table',
            data: function (d) {
                d.dept = $('#filterDept').val(); 
            },
            dataSrc: 'data'
        },
        columns: [
            { data: 'EmpNo', title: 'Employee Number', className: 'align-middle' },
            { data: 'Name', title: 'Name', className: 'align-middle' },

           // Make VL clickable
            { 
                data: 'VL', 
                title: 'VL', 
                className: 'align-middle',
                render: function (data, type, row) {
                    return `<a href="#" 
                                class="open-late" 
                                data-id="${row.EmpNo}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalLate">
                                ${data}
                            </a>`;
                }
            },

            { data: 'SL', title: 'SL', className: 'align-middle' },
            { data: 'SPL', title: 'SPL', className: 'align-middle' },

            // Make CTO clickable
            {
                data: 'CTO',
                title: 'CTO',
                className: 'align-middle',
                render: function (data, type, row) {
                    return `<a href="#modalAddCTO" data-bs-toggle="modal" class="open-cto" data-id="${row.EmpNo}">${data}</a>`;
                }
            }
        ]
    });

    // // Reload table when filter changes
    // $('#filterDept').on('change', function () {
    //     empTable.ajax.reload();
    // });

   $(document).on('click', '.open-late', function () {
        let empNo = $(this).data('id');
        $('#empNoField').val(empNo);
        $('#modalLate').modal('show');
    });



    $(document).on('click', '.open-cto', function () {
        let empNo = $(this).data('id');
        $('#ctoEmpNo').val(empNo);
        $('#modalAddCTO').modal('show');
    });

    // Enable/disable Holiday Type dynamically
    $('#ctoForm input[name="ctoHours"]').change(function() {
        if ($(this).val() == "1") {
            $('#ctoHoliType').prop('disabled', true);
        } else {
            $('#ctoHoliType').prop('disabled', false);
        }
    });


});
</script>

