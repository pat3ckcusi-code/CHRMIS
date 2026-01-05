<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addUserModalLabel">
          <i class="fas fa-user-plus"></i> Add User
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <form id="addUserForm" action="../api/api_users.php" method="post">
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <!-- Employee No. -->
              <div class="col-md-4">
                <label for="txtEmpNum">Employee No.</label>
                <input type="text" class="form-control" id="txtEmpNum" name="txtEmpNum" required>
              </div>

              <!-- Employment Status -->
              <div class="col-md-4">
                <label for="txtEmpStatus">Employment Status</label>
                <select class="form-control" id="txtEmpStatus" name="txtEmpStatus" required>
                  <option value="">-- Select Status --</option>
                  <option value="Permanent">Permanent</option>
                  <option value="Job Order">Job Order</option>
                  <option value="Contract of Service">Contract of Service</option>
                  <option value="Co-Terminus">Co-Terminus</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="txtDateHired">Date Hired:</label>
                <input type="Date" class="form-control" id="txtDateHired" name="txtDateHired" required>
              </div>
            </div>
          </div>


          <!-- Name Fields -->
          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
                <label for="txtLname">Surname</label>
                <input type="text" class="form-control" id="txtLname" name="txtLname" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
              </div>
              <div class="col-md-3">
                <label for="txtFname">First Name</label>
                <input type="text" class="form-control" id="txtFname" name="txtFname" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required>
              </div>
              <div class="col-md-3">
                <label for="txtExt">Name Extension (JR, SR)</label>
                <input type="text" class="form-control" id="txtExt" name="txtExt" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
              </div>
              <div class="col-md-3">
                <label for="txtMname">Middle Name</label>
                <input type="text" class="form-control" id="txtMname" name="txtMname" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();">
              </div>
            </div>
          </div>

          <!-- Sex -->
          <div class="form-group">
            <label>Sex</label>
            <div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="rdMale" name="rdSex" value="Male" required>
                <label class="form-check-label" for="rdMale">Male</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="rdFemale" name="rdSex" value="Female">
                <label class="form-check-label" for="rdFemale">Female</label>
              </div>
            </div>
          </div>

          <!-- Civil Status -->
          <div class="form-group">
            <label>Civil Status</label>
            <div>
              <?php 
              $civilStatuses = ['Single', 'Married', 'Widowed', 'Separated', 'Others'];
              foreach ($civilStatuses as $status) {
                $id = 'rdHR' . str_replace(' ', '', $status);
                echo '<div class="form-check form-check-inline">';
                echo '<input class="form-check-input" type="radio" id="' . $id . '" name="rdHRCivil" value="' . $status . '" required>';
                echo '<label class="form-check-label" for="' . $id . '">' . $status . '</label>';
                echo '</div>';
              }
              ?>
            </div>
          </div>

          <div class="form-row">
            <!-- Date of Birth -->
            <div class="form-group col-md-6">
              <label for="txtHRDate">Date of Birth</label>
              <input type="date" class="form-control" id="txtHRDate" name="txtHRDate" required>
            </div>

            <!-- Department -->
            <div class="form-group col-md-6">
              <label for="ddDept">Department</label>
              <select class="form-control" name="ddDept" id="ddDept">
                <option value="City Human Resource Management Department">City Human Resource Management Department (CHRMD)</option>
                <option value="City Mayor Office">City Mayor Office (CMO)</option>
                <option value="Office of the Vice Mayor">Office of the Vice Mayor (CVMO)</option>
                <option value="City Administrator, Chief of Staff, and Secretary to the Mayor">City Administrator, Chief of Staff, and Secretary to the Mayor (CAO)</option>
                <option value="City Budget Department">City Budget Department (CBD)</option>
                <option value="City Legal Department">City Legal Department (CLD)</option>
                <option value="City Education Department">City Education Department (CED)</option>
                <option value="City College of Calapan">City College of Calapan (CCC)</option>
                <option value="City Public Safety Department">City Public Safety Department (CPSD)</option>
                <option value="City Veterinary Services Department">City Veterinary Services Department (CVSD)</option>
                <option value="City Disaster Risk Reduction Management Department">City Disaster Risk Reduction Management Department (CDRRMD)</option>
                <!-- <option value="City Nutrition Office">City Nutrition Office</option>
                <option value="City Population Development Office">City Population Development Office</option> -->
                <option value="City Health and Sanitation Department">City Health and Sanitation Department (CHSD)</option>
                <option value="City Treasury Department">City Treasury Department (CTD)</option>
                <option value="City Assessor Department">City Assessor Department (CAD)</option>
                <option value="City Economic Enterprise Department">City Economic Enterprise Department (CEED)</option>
                <option value="Business Permit and Licensing Office">Business Permit and Licensing Office (BPLO)</option>
                <option value="City Trade and Industry Department">City Trade and Industry Department (CTID)</option>
                <option value="City Accounting and Internal Audit Department">City Accounting and Internal Audit Department (CAIAD)</option>
                <option value="City General Services Department">City General Services Department (CGSD)</option>
                <option value="Bids and Awards Committee">Bids and Awards Committee (BAC)</option>
                <option value="City Social Welfare Development Department">City Social Welfare Development Department (CSWDD)</option>
                <option value="City Public Employment Services Office">City Public Employment Services Office (CPESO)</option>
                <!-- <option value="Barangay Development Affairs Officer">Barangay Development Affairs Officer</option> -->
                <option value="City Civil Registry Department">City Civil Registry Department (CCRD)</option>
                <option value="City Youth and Sports Development Department">City Youth and Sports Development Department (CYSDD)</option>
                <option value="City Agricultural Services Department">City Agricultural Services Department (CASD)</option>
                <option value="City Cooperative Development Office">City Cooperative Development Office (CCDO)</option>
                <option value="City Housing and Urban Settlements Department">City Housing and Urban Settlements Department (CHUSD)</option>
                <option value="City Architectural Planning and Design Department">City Architectural Planning and Design Department (CAPDD)</option>
                <option value="City Environment and Natural Resources Department">City Environment and Natural Resources Department (CENRD)</option>
                <option value="City Engineering and Public Works Department">City Engineering and Public Works Department (CEPWD)</option>
                <option value="Urban Planning and Development Department">Urban Planning and Development Department (UPDD)</option>
                <option value="Management Information System Office">Management Information System Office (MISO)</option>
                <option value="Gender and Development">Gender and Development (GAD)</option>
                <option value="City Fishery and Aquatic Resources Department">City Fishery and Aquatic Resources Department (CFMO)</option>
                <option value="Office for Senior Citizen Affairs">Office for Senior Citizen Affairs (OSCA)</option>
                <!-- <option value="SP Secretariat">SP Secretariat</option> -->
                <option value="City Library Office">City Library Office (CLO)</option>
                <option value="Person with Disability Affairs Office">Person with Disability Affairs Office (PDAO)</option>
                <!-- <option value="Calapan City Convention Center">Calapan City Convention Center</option> -->
                <option value="City Information Office">City Information Office (CIO)</option>
                <option value="City Tourism, Culture and Arts Department">City Tourism, Culture and Arts Department (CTCAD)</option>
                <option value="Barangay and Community Affairs Office​">Barangay and Community Affairs Office (BCAO)​</option>
                <option value="City Market Section​">City Market Section (MKT)​</option>
                <option value="City Slaugtherhouse Section​">City Slaugtherhouse Section (SLT)​</option>     
                <option value="City Cemetery  Section​">City Cemetery  Section​ (CMT)​</option>    
                <option value="City Zoological and Recreational Section​">City Zoological and Recreational Section (ZOO)​</option>       
              </select>
            </div>
          </div>
<!-- 
          <div class="form-group col-md-6">
            <label for="exampleInputFile">Upload Signature</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="exampleInputFile">
                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
              </div>              
            </div>
          </div> -->

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="fas fa-save"></i> Save
          </button>
        </div>

      </form>
    </div>
  </div>
</div>


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
                document.getElementById('addUserForm').reset();
              }else{
                Swal.fire('Warning!', response.message, 'warning')
              }
            })
          }
        })
      });
</script>
