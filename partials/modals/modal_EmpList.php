<!-- Update Department Modal -->
<div class="modal fade" id="ModalEmpList" tabindex="-1" role="dialog" aria-labelledby="EmpList" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addUserModalLabel">
          <i class="fas fa-user-plus"></i> Edit Department
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <form id="EditUserForm" action="../api/api_users.php?update_department=1" method="post">
        <input type="hidden" name="empNo" id="empNo">
        <div class="modal-body">

          <!-- Employee No -->
          <div class="form-group">
            <label for="txtEmpNum">Employee No.</label>
            <input type="text" class="form-control" id="txtEmpNum" name="txtEmpNum" readonly>
          </div>

          <!-- Name Fields -->
          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
                <label for="txtLname">Surname</label>
                <input type="text" class="form-control" id="txtLname" name="txtLname" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" readonly>
              </div>
              <div class="col-md-3">
                <label for="txtFname">First Name</label>
                <input type="text" class="form-control" id="txtFname" name="txtFname" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" readonly>
              </div>
              <div class="col-md-3">
                <label for="txtExt">Name Extension (JR, SR)</label>
                <input type="text" class="form-control" id="txtExt" name="txtExt" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" readonly>
              </div>
              <div class="col-md-3">
                <label for="txtMname">Middle Name</label>
                <input type="text" class="form-control" id="txtMname" name="txtMname" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" readonly>
              </div>
            </div>
          </div>

          <!-- Sex -->
          <div class="form-group">
            <label>Sex</label>
            <div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="rdMale" name="rdSex" value="Male" readonly>
                <label class="form-check-label" for="rdMale">Male</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="rdFemale" name="rdSex" value="Female" readonly>
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
                echo '<input class="form-check-input" type="radio" id="' . $id . '" name="rdHRCivil" value="' . $status . '" readonly>';
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
              <input type="date" class="form-control" id="txtHRDate" name="txtHRDate" readonly>
            </div>

            <!-- Department -->
            <div class="form-group col-md-6">
              <label for="ddDept">Department</label>
              <select class="form-control" name="dept" id="ddDept">
                <option value="City Human Resource Management Department">City Human Resource Management Department (CHRMD)</option>
                <option value="IMSD">City Mayor Office</option>
                <option value="Office of the Vice Mayor">Office of the Vice Mayor</option>
                <option value="City Administrator, Chief of Staff, and Secretary to the Mayor">City Administrator, Chief of Staff, and Secretary to the Mayor</option>
                <option value="City Budget Department">City Budget Department (CBD)</option>
                <option value="City Legal Department">City Legal Department (CLD)</option>
                <option value="City Education Department">City Education Department (CED)</option>
                <option value="City College of Calapan">City College of Calapan (CCC)</option>
                <option value="City Public Safety Department">City Public Safety Department (CPSD)</option>
                <option value="City Veterinary Services Department">City Veterinary Services Department (CVSD)</option>
                <option value="City Disaster Risk Reduction Management Department">City Disaster Risk Reduction Management Department (CDRRM)</option>
                <option value="City Nutrition Office">City Nutrition Office</option>
                <option value="City Population Development Office">City Population Development Office</option>
                <option value="City Health and Sanitation Department">City Health and Sanitation Department (CHSD)</option>
                <option value="City Treasury Department">City Treasury Department (CTD)</option>
                <option value="City Assessor Department">City Assessor Department (CAD)</option>
                <option value="City Economic Enterprise Department">City Economic Enterprise Department (CEED)</option>
                <option value="Business Permit and Licensing Office">Business Permit and Licensing Office (BPLO)</option>
                <option value="City Trade and Industry Department">City Trade and Industry Department (CTID)</option>
                <option value="City Socialized Medical Health Care Office">City Socialized Medical Health Care Office</option>
                <option value="City Accounting and Internal Audit Department">City Accounting and Internal Audit Department (CAIAD)</option>
                <option value="City General Services Department">City General Services Department (CGSO)</option>
                <option value="Bids and Awards Committee">Bids and Awards Committee (BAC)</option>
                <option value="City Social Welfare Development Department">City Social Welfare Development Department (CSWD)</option>
                <option value="City Public Employment Services Office">City Public Employment Services Office (CPESO)</option>
                <option value="Barangay Development Affairs Officer">Barangay Development Affairs Officer</option>
                <option value="City Socialized Medical Health Care Office">City Socialized Medical Health Care Office</option>
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
                <option value="Gender and Development">Gender and Development</option>
                <option value="Fisheries Management Office">Fisheries Management Office</option>
                <option value="City Veterinary Services Department">City Veterinary Services Department</option>
                <option value="City Health and Sanitation Department – City Plaza">City Health and Sanitation Department (CHSD) – City Plaza</option>
                <option value="Office for Senior Citizen Affairs">Office for Senior Citizen Affairs</option>
                <option value="SP Secretariat">SP Secretariat</option>
                <option value="City Public Library">City Public Library</option>
                <option value="Person with Disability Affairs Office">Person with Disability Affairs Office (PDAO)</option>
                <option value="Calapan City Convention Center">Calapan City Convention Center</option>
                <option value="City Information Office">City Information Office (CIO)</option>
                <option value="City Tourism, Culture and Arts Office">City Tourism, Culture and Arts Office (CTCAO)</option>
                <option value="Community Affairs Office​">Community Affairs Office​</option>
              </select>
            </div>
          </div>
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

<script>
$('#EditUserForm').on('submit', function(e){
    e.preventDefault();

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response){
            if(response.success){
                alert(response.message);                 
                $('#ModalEmpList').modal('hide');        
                empTable.ajax.reload(null, false);       
            } else {
                alert(response.error || response.message); 
            }
        },
        error: function(xhr){
            console.error(xhr.responseText);
            alert("AJAX error: could not update department");
        }
    });
});





</script>