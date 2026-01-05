<?php
session_start();
require_once('../includes/initialize.php');
include_once( PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
?>

<!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="login.php" class="brand-link">
      <img src="../dist/img/dole_ncr_logo_round.png" alt="Dole Logo" class="brand-image elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Log In</span>
    </a>
  </aside>

    

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>COVID-19 Health Checklist </h1>
          
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <form id="addUserForm" action="../api/api_users.php" method="post">
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">COVID-19 Health Checklist</h3>
              <div class="card-tools">
            <button type="button" class="btn btn-tool text-left" a href="#addEmpModal" class="text-center" data-toggle="modal"><h6><i class="fas fa-plus"></i>&nbsp Register Here</h6>
            </button> 
              </div>
          </div>
          
          <div class="card-body">
            <div>
              <div class="form-group row">
                <div class="col-3">
                  <label for="a_user_id_no">ID Number<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_id_no" id="a_user_id_no" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">
                  <label for="a_user_name">Name<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_name" id="a_user_name" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required readonly>
                </div>
                <div class="col-3">
                  <label for="a_user_sex">Sex<span class="h5 text-danger">*</span></label>
                  <select id="a_user_sex" name="user_sex" class="form-control" required readonly>
                    <option value="">--Select Sex--</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                  </select>
                </div>
                <div class="col-3">
                  <label for="a_user_age">Age<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_age" id="a_user_age" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-4">
                  <label for="a_user_office">Office<span class="h5 text-danger">*</span></label>
                  <input type="numeric" name="user_office" id="a_user_office" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required readonly>
                </div>
                 <div class="col-4">
                  <label for="a_user_date">Date Checked<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_date" id="a_user_date" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" value="<?php echo date('Y-m-d h:i:s A');?>" required readonly>
                </div>
                <div class="col-4">
                  <label for="a_user_temp">Temperature<span class="h5 text-danger">*</span></label>
                  <input type="numeric" name="user_temp" id="a_user_temp" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
                </div> 
              </div>              
              <div class="form-group row">
                <div class="col-12">
                  <table width="100%" class="table">
                    <tr>
                      <td rowspan="5" valign="center">Are your experiencing? <br> (nakakaranas ka ba ng?)</td>
                        <td>Sore throat (pananakit ng lalamunan / masakit lumunok)</td>
                        <td>
                          <div class="form-check">
                            <input type="radio" class="form-check-input" id="item1" name="item1" value="Yes" required="required">
                            <label class="form-check-label" for="item1">Yes</label>
                          </div>
                        </td>
                        <td>
                          <div class="form-check">
                            <input type="radio" class="form-check-input" id="item1a" name="item1" value="No" required="required">
                            <label class="form-check-label" for="item1a">No</label>
                          </div>
                        </td>
                    </tr>
                    <tr>
                    <td>Body pains (pananakit ng katawan)</td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item2" name="item2" value="Yes" required="required">
                        <label class="form-check-label" for="item2">Yes</label>
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item2a" name="item2" value="No" required="required">
                        <label class="form-check-label" for="item2a">No</label>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Headache (pananakit ng ulo)</td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item3" name="item3" value="Yes" required="required">
                        <label class="form-check-label" for="item3">Yes</label>
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item3a" name="item3" value="No" required="required">
                        <label class="form-check-label" for="item3a">No</label>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Fever for the past few days (lagnat sa nakalipas na mga araw)</td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item4" name="item4" value="Yes" required="required">
                        <label class="form-check-label" for="item4">Yes</label>
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item4a" name="item4" value="No" required="required">
                        <label class="form-check-label" for="item4a">No</label>
                      </div>
                    </td>
                  </tr>
                  </table>
                  <table class="table">
                  <tr>
                    <td>Did you have any close contact with a confirmed COVID-19 case? <br>(Ikaw ba ay may nakasalamuhang tao na kumpirmadong may COVID-19?)</td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item5" name="item5" value="Yes" required="required">
                        <label class="form-check-label" for="item5">Yes</label>
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item5a" name="item5" value="No" required="required">
                        <label class="form-check-label" for="item5a">No</label>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Did you have contact with anyone with fever, cough, cold and sore throat in the past 3 days? <br>(Mayroon ka bang nakasama na may lagnat, ubo, sipon o sakit ng lalamunan sa nakalipas na tatlong (3) araw?)</td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item6" name="item6" value="Yes" required="required">
                        <label class="form-check-label" for="item6">Yes</label>
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item6a" name="item6" value="No" required="required">
                        <label class="form-check-label" for="item6a">No</label>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Did you travelled outside the Province in the last 5 days? <br>(Ikaw ba ay nagbyahe sa labas ng Probinsya sa nakalipas na 5 na araw?)</td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item7" name="item7" value="Yes" required="required">
                        <label class="form-check-label" for="item7">Yes</label>
                      </div>
                    </td>
                    <td>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="item7a" name="item7" value="No" required="required">
                        <label class="form-check-label" for="item7a">No</label>
                      </div>
                    </td>
                  </tr>                  
                  <tr>
                    <td colspan="3">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" id="item9" name="item9" required> <label class="form-check-label" for="item9">I hereby authorized the Department of Labor and Employment to collect and process the data indicated herein for the purpose of effective control of COVID-19 Infection. I understand that my personal information is protected by RA 10173, Data Privacy Act of 2012, and that I am required by RA 11469, Bayanihan to Heal as One Act to provide truthful information. </label>
                        </div>
                    </td> 
                  </tr>
                  <tr>
                    <td colspan="3">
                      <button class="btn btn-primary btn-sm btn-rounded" type="submit">Submit form</button>
                      <input type = "reset" value="Cancel">
                    </td>
                  </tr>
                </table>
                </div>
              </div>
            </div>
          </div>
          </table>
        </div>
      </div>
    </div>
  </section>
</form>
</div>

<?php
include_once('../partials/footer.php');
include_once('../partials/modals/modal_add_emp.php');
?>
<script type="text/javascript"></script>
<script>
$(document).ready(function() {

  let selectedData = null;

  //User Table
  var usersTable = $('#users_table').DataTable({
    responsive: true,
    //bStateSave: true,
    order: [[ 0, "asc" ]],
    ajax: {
      url: '../api/api_users.php',
      dataSrc: 'data'
    },
    columnDefs: [
		            { responsivePriority: 1, targets: 0 },
		             { responsivePriority: 2, targets: 3 }
		            ],
    columns: [
      { data: 'user_id_no', title: 'Employee Number', className: 'text-center' },
      { data: 'first_name', title: 'Firstname', className: 'align-middle' },
      { data: 'middle_name', title: 'Middlename', className: 'align-middle' },
      { data: 'last_name', title: 'Last Name', className: 'align-middle' },
      { data: 'position', title: 'Position', className: 'align-middle' },
      { data: 'office', title: 'Office/Division', className: 'align-middle' },
      { data: 'access_level', title: 'Access Level', className: 'align-middle' }
        ],
      });

      //Add User Account Modal
      $('[data-action="add_user"]').click(function() {
        document.getElementById('addUserForm').reset();
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
              url: '../api/api_users.php?add_user',
              type: 'post',
              processData: false,
              contentType: false,
              data: new FormData(this)
            }).then(function(response) {
              console.log(response);
              if(response.success){
                $('#addUserForm')[0].reset();
                if(usersTable) {
                  usersTable.ajax.reload(null, false);
                }
                Swal.fire('Success!', response.message, 'success')
                window.close();
              }else{
                Swal.fire('Warning!', response.message, 'warning')
              }

            })
          }
        })
      });

      // Update User Account Modal
      $('#a_user_id_no').on('blur', function() {
        //alert($('#a_user_id_no').val());
        var id = $('#a_user_id_no').val();
        if ($('#a_user_id_no').val()) {
              $.ajax({
                url: '../api/api_users.php',
                type: 'GET',
                data: { user_id: id }
              }).then(function(response) {
                console.log(response);
                if(response.data){
                  var empname = response.data['Fname'] + " " + response.data['Lname'];
                  var ageDifMs = Date.now() - new Date(response.data['BirthDate']);
                  var sex = response.data['Gender'];
                  var dept = response.data['Office'];
                  var ageDate = new Date(ageDifMs); // miliseconds from epoch
                  var age = Math.abs(ageDate.getUTCFullYear() - 1970);
                  document.getElementById("a_user_name").value = empname;
                  document.getElementById("a_user_age").value = age;
                  document.getElementById("a_user_office").value = dept;
                  document.getElementById("a_user_sex").value = sex;
                  Swal.fire('Welcome ' + empname + '!', response.message, 'success')
                }else
                {
                  Swal.fire('Please Register first!', response.message, 'warning')
                  document.getElementById('addUserForm').reset()
                }
                
              })
            }
      });

  });

// Add User record
    $('#addEmpForm').submit(function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Information',
          text: "Are you sure you want to add this records?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, save it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: '../api/api_users.php?add_userinfo',
              type: 'post',
              processData: false,
              contentType: false,
              data: new FormData(this)
            }).then(function(response) {
              if(response.success){
                Swal.fire('Success!', response.message, 'success')
                 $('#addEmpForm')[0].reset();
                 $('#addEmpModal').modal('hide');
                window.close();
              }else{
                Swal.fire('Warning!', response.message, 'warning')
              }

            })
          }
        })
      });
</script>
