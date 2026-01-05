<form id="viewupdateUserForm" action="../api/api_users.php" method="post">
  <input type="hidden" id="e_user_id" name="user_id" value="0">
  <div id="viewupdateUserModal" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">View/Update User Account</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <div class="col-12 text-right text-danger">
              <label><i>* Denotes Required Field</i></label>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_user_id_no">User ID Number<span class="h5 text-danger">*</span></label>
              <input type="text" name="user_id_no" id="e_user_id_no" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_first_name">First Name<span class="h5 text-danger">*</span></label>
              <input type="text" name="first_name" id="e_first_name" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_middlename">Middle Name<span class="h5 text-danger">*</span></label>
              <input type="text" name="middlename" id="e_middlename" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_lastname">Last Name<span class="h5 text-danger">*</span></label>
              <input type="text" name="lastname" id="e_lastname" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_name_extension">Name Extension</label>
              <input type="text" name="name_extension" id="e_name_extension" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()">
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_position">Position<span class="h5 text-danger">*</span></label>
              <input type="text" name="position" id="e_position" class="form-control" onkeyup="this.value = this.value.toUpperCase()" onpaste="this.value = this.value.toUpperCase()" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-6">
              <label for="e_office">Office/Division<span class="h5 text-danger">*</span></label>
              <select id="e_office" name="office" class="form-control" required>
                <optgroup label="NCR">
                  <option value="ORD">ORD</option>
                  <option value="OARD">OARD</option>
                  <option value="TSSD-EPWW">TSSD-EPWW</option>
                  <option value="TSSD-LRLS">TSSD-LRLS</option>
                  <option value="MALSU">MALSU</option>
                  <option value="IMSD">IMSD</option>
                  <option value="CFO">CFO</option>
                  <option value="MFO">MFO</option>
                  <option value="MPFO">MPFO</option>
                  <option value="MTPLFO">MTPLFO</option>
                  <option value="PFO">PFO</option>
                  <option value="QCFO">QCFO</option>
                </optgroup>
                <optgroup label="OTHER RO">
                  <option value="CAR">CAR</option>
                  <option value="CARAGA">CARAGA</option>
                  <option value="RO-I">RO-I</option>
                  <option value="RO-II">RO-II</option>
                  <option value="RO-III">RO-III</option>
                  <option value="RO-IVA">RO-IVA</option>
                  <option value="RO-IVB">RO-IVB</option>
                  <option value="RO-V">RO-V</option>
                  <option value="RO-VI">RO-VI</option>
                  <option value="RO-VII">RO-VII</option>
                  <option value="RO-VIII">RO-VIII</option>
                  <option value="RO-IX">RO-IX</option>
                  <option value="RO-X">RO-X</option>
                  <option value="RO-XI">RO-XI</option>
                  <option value="RO-XII">RO-XII</option>
                </optgroup>
                <optgroup label="DOLE BUREAUS">
                  <option value="BLE">BLE</option>
                  <option value="BLR">BLR</option>
                  <option value="BWC">BWC</option>
                  <option value="BWSC">BWSC</option>
                  <option value="ILAB">ILAB</option>
                </optgroup>
              </select>
            </div>

            <div class="col-6">
              <label for="e_access_level">Access Level<span class="h5 text-danger">*</span></label>
              <select id="e_access_level" name="access_level" class="form-control" required>
                <option value="">--Select Access Level--</option>
                <?php
                if ($access_level == 'System Administrator') { ?>
                <option value="System Administrator">System Administrator</option>
                <?php } else {}?>
                <option value="Official - RO">Official - RO</option>
                <option valuee"Official - FO">Official - FO</option>
                <option value="HR - Manager">HR - Manager</option>
                <option value="HR - Aide">HR - Aide</option>
                <option value="HR - Designate">HR - Designate</option>
                <option value="Employee">Employee</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_username">Username<span class="h5 text-danger">*</span></label>
              <input type="text" name="username" id="e_username" class="form-control" required>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-12">
              <label for="e_password">Password<span class="h5 text-danger">*</span></label>
              <input type="text" name="password" id="e_password" class="form-control" required>
            </div>
          </div>

        </div> <!--modal body-->

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</form>
