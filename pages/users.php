<?php
//session_start();
require_once('../includes/initialize.php');
include_once( PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
if(!isset($_SESSION['access_level'])){
  header("location: ../index.php");
}else{
      $access_level = $_SESSION['access_level'];
    }
?>




<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>COVID-19 Health Checklist</h1>
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
                <div class="col-3">
                  <label for="a_user_name">Last Name<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_Lname" id="a_user_Lname" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
                </div>
                <div class="col-3">
                  <label for="a_user_name">First Name<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_Fname" id="a_user_Fname" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
                </div>
                <div class="col-3">
                  <label for="a_user_name">Middle Name<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_Mname" id="a_user_Mname" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
                </div>
                <div class="col-3">
                  <label for="a_user_name">Extension<span class="h5 text-danger">*</span></label>
                  <input type="text" name="user_Ename" id="a_user_Ename" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()">
                </div>
                <div class="col-3">
                  <label for="a_user_sex">Sex<span class="h5 text-danger">*</span></label>
                  <select id="a_user_sex" name="user_sex" class="form-control" required>
                    <option value="">--Select Sex--</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                  </select>
                </div>
                <div class="col-3">
                  <label for="a_user_birthdate">Birthdate<span class="h5 text-danger">*</span></label>
                  <input type="date" name="user_birthdate" id="a_user_birthdate" class="form-control" onkeyup="this.value" required>
                </div>                
              </div>
              <div class="form-group row">
                <div class="col-6">
                  <label for="a_user_office">Office<span class="h5 text-danger">*</span></label>
                  <select id="a_user_office" name="user_office" class="form-control" required>
                    <option value="">--Select Office--</option>
                    <option value="TSSD">DOLE-TSSD</option>
                    <option value="IMSD">DOLE-IMSD</option>
                    <option value="OrMin">ORIENTAL MINDORO FIELD OFFICE</option>
                    <option value="Occi">OCCIDENTAL MINDORO FIELD OFFICE</option>
                    <option value="MARINDUQUE">MARINDUQUE FIELD OFFICE</option>
                    <option value="ROMBLON">ROMBLON FIELD OFFICE</option>
                    <option value="PALAWAN">PALAWAN FIELD OFFICE</option>
                  </select>
                </div>                          
              </div>  
              <tr>
                    <td colspan="3">
                      <button class="btn btn-primary btn-sm btn-rounded" type="submit">Submit form</button>
                    </td>
                  </tr>           
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
?>
<script type="text/javascript"></script>
<script>



      //Add User Account Modal
      $('[data-action="add_userinfo"]').click(function() {
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
              url: '../api/api_users.php?add_userinfo',
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
