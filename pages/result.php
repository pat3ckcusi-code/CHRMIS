<?php
session_start();
require_once('../includes/initialize.php');
include_once( PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
$access_level = $_SESSION['access_level'];
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
          <h1>Checklist</h1>
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
            <h3 class="card-title">Checklist</h3>
              <div class="card-tools">
                
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="Checklist" class="table table-bordered table-striped table-hover" style="width: 100%;">
                <thead class="bg-info" height="40">
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
     <table width="100%" class="table">
                    <tr>
                      <td rowspan="5" valign="center">Are your experiencing? <br> (nakakaranas ka ba ng?)</td>
                        <td><b>(ITEM 1)</b>Sore throat (pananakit ng lalamunan / masakit lumunok)</td>                        
                    </tr>
                    <tr>
                    <td><b>(ITEM 2)</b>Body pains (pananakit ng katawan)</td>                    
                  </tr>
                  <tr>
                    <td><b>(ITEM 3)</b>Headache (pananakit ng ulo)</td>                    
                  </tr>
                  <tr>
                    <td><b>(ITEM 4)</b>Fever for the past few days (lagnat sa nakalipas na mga araw)</td>                    
                  </tr>
                  </table>
                  <table class="table">
                  <tr>
                    <td><b>(ITEM 5)</b>Have you worked together or stayed in the same close environment of a confirmed COVID-19 case? <br>(May nakasama ka ba o nakatrabahong tao na kumpirmadong may COVID-19?)</td>        
                  </tr>
                  <tr>
                    <td><b>(ITEM 6)</b>Have you have contact with anyone with fever, cough, cold and sore throat in the past 2 weeks? <br>(Mayroon ka bang nakasama na may lagnat, ubo, sipon o sakit ng lalamunan sa nakalipas na dalawang (2) linggo?)</td>                    
                  </tr>
                  <tr>
                    <td><b>(ITEM 7)</b>Have you travelled outside of the Philippines in the last 14 days? <br>(Ikaw ba ay nagbyahe sa labas ng Pilipinas sa nakalipas na 14 na araw?)</td>                    
                  </tr>
                  <tr>
                    <td><b>(ITEM 8)</b>Have you travelled to any area in NCR aside from your place of residence? <br>(Ikaw ba ay nagpunta sa iba pang parte ng NCR o Metro Manila bukod sa iyong bahay?)</td>                  
                  </tr>                                  
                </table>
                </div>
              </div>
            </div>
          </div>
          </table>
  </section>
</div>
<?php
include_once('../partials/footer.php');
include_once('../partials/modals/modal_add_user.php');
?>
<script>

  //$("#userdiv").hide();
  //$("#passdiv").hide();

  //User Table
  var Checklist = $('#Checklist').DataTable({
    responsive: true,
    //bStateSave: true,
    order: [[ 0, "asc" ]],
    processing: true,
    dom: 'Bfrtip',
    buttons: [
        {
          extend: 'csv',
          className: 'btn btn-warning',
          filename: 'summary_of_health_checklist',
          exportOptions: {
              columns: "thead th:not(.noExport)",
              orthogonal: 'export'
          }
        },
        {
          extend: 'excel',
          className: 'btn btn-warning',
          exportOptions: {
              columns: "thead th:not(.noExport)",
              orthogonal: 'export'
          }
        },
        {
          extend: 'pdf',
          className: 'btn btn-warning'
        },
        {
          extend: 'print',
          className: 'btn btn-warning',
          exportOptions: {
              columns: "thead th:not(.noExport)"
          }
        }
    ],
    ajax: {
      url: '../api/api_users.php?res&a=<?php echo $access_level;?>', 
      type: 'GET',
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
      { data: 'Office', title: 'Office', className: 'align-middle' },
      { data: 'Temperature', title: 'Temperature', className: 'align-middle' },
      { data: 'DateChecked', title: 'Date Checked', className: 'align-middle' },
      { data: 'Item1', title: 'Item1', className: 'align-middle' },
      { data: 'Item2', title: 'Item2', className: 'align-middle' },
      { data: 'Item3', title: 'Item3', className: 'align-middle' },
      { data: 'Item4', title: 'Item4', className: 'align-middle' },
      { data: 'Item5', title: 'Item5', className: 'align-middle' },
      { data: 'Item6', title: 'Item6', className: 'align-middle' },
      { data: 'Item7', title: 'Item7', className: 'align-middle' },
        ],
      });

       /*$("#a_access_level").on("change", function(){
        if (this.selectedIndex == 6){
          $("#userdiv").hide();
          $("#passdiv").hide();
          
        }else{
          $("#userdiv").show();
          $("#passdiv").show();
        }
       });*/

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
