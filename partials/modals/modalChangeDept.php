<div class="modal fade" id="modalChangeDept<?php echo $ChangeDept['EmpNo'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Change Department</h4>
            </div>
            <form action="ChangeDept.php?id=<?php echo $ChangeDept['EmpNo'];?>" method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label>EMPLOYEE NO.</label><br>
                    <input class="form-control" name="txtEmpNum" id="txtEmpNum" value = "<?php echo $ChangeDept['EmpNo']; ?>" disabled />
                </div>
				<div class="form-group">
					<table class="table table-striped table-bordered table-hover" id="dataTablesI">
                        <thead>
						</thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center; font-size: small;">
                                    <span class="style2">Surname</span><br />
                                </td>
                                <td colspan="3" style="text-align: center; font-size: small;">
                                    <input class="form-control" name="txtHRLname" type="text" id="txtLname" value = "<?php echo $ChangeDept['Lname']; ?>" disabled />
                                </td>
                            </tr>
							<tr>
								<td style="text-align: center; font-size: small; width:20%;">
									<span class="style2">First Name</span>
								</td>
								<td style="text-align: center; font-size: small;">
									<input class="form-control" name="txtHRFname" type="text" id="txtFname" value = "<?php echo $ChangeDept['Fname']; ?>" disabled />
								</td>
								<td style="text-align: center; font-size: small; width:10%;">
									<span class="style2">Name Extension (JR, SR)</span>
								</td>
								<td style="text-align: center; font-size: small; width:20%;">
									<input class="form-control" name="txtHRExt" type="text" id="txtExt" value = "<?php echo $ChangeDept['Extension']; ?>" disabled />
								</td>
							</tr>
							<tr>
								<td style="text-align: center; font-size: small;">
									<span class="style2">Middle Name</span>
								</td>
								<td colspan="3" style="text-align: center; font-size: small;">
									<input class="form-control" name="txtHRMname" type="text" id="txtMname" value = "<?php echo $ChangeDept['Mname']; ?>" disabled />
								</td>
							</tr>
						</tbody>
                    </table>
                </div>
				<div class="form-group">
					<label>Sex</label><br>
						<input id="rdHRMale" type="radio" name="rdHRSex" value="Male" <?php if($ChangeDept['Gender'] == 'Male') echo 'checked';?> disabled /><label for="rdMale" >&nbsp;Male</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input id="rdHRFemale" type="radio" name="rdHRSex" value="Female" <?php if($ChangeDept['Gender'] == 'Female') echo 'checked';?> disabled /><label for="rdFemale">&nbsp;Female</label>
				</div>
				<div class="form-group">
					<label>Civil Status</label><br>
						<input id="rdHRSingle" type="radio" name="rdHRCivil" value="Single" <?php if($ChangeDept['Civil'] == 'Single') echo 'checked';?> disabled /><label for="rdSingle">&nbsp;Single</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input id="rdHRMarried" type="radio" name="rdHRCivil" value="Married" <?php if($ChangeDept['Civil'] == 'Married') echo 'checked';?> disabled /><label for="rdMarried">&nbsp;Married</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input id="rdHRWidowed" type="radio" name="rdHRCivil" value="Widowed" <?php if($ChangeDept['Civil'] == 'Widowed') echo 'checked';?> disabled /><label for="rdWidowed">&nbsp;Widowed</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input id="rdHRSeparated" type="radio" name="rdHRCivil" value="Separated" <?php if($ChangeDept['Civil'] == 'Separated') echo 'checked';?> disabled /><label for="rdSeparated">&nbsp;Separated</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input id="rdHROthers" type="radio" name="rdHRCivil" value="Others" <?php if($ChangeDept['Civil'] == 'Others') echo 'checked';?> disabled /><label for="rdOthers">&nbsp;Other/s</label>
                </div>
				<div class="form-group">
					<label>Date of Birth</label><br>
						<input class="form-control" name="txtHRDate" type="date" id="txtHRDate" style="width:100%;" value="<?php echo $ChangeDept['BirthDate'];?>" disabled />
				</div>
				<div class="form-group">
					<label>Department</label><br>
						<select class="form-control" name="ddDept" id="ddDept">
							<option value="Director Management Staff" <?php if($ChangeDept['Dept'] == 'Director Management Staff') echo 'selected = "selected"'; ?>>Director Management Staff</option>
							<option value="Office of the Regional Director" <?php if($ChangeDept['Dept'] == 'Office of the Regional Director') echo 'selected = "selected"'; ?>>Office of the Regional Director</option>
							<option value="TSSD" <?php if($ChangeDept['Dept'] == 'TSSD') echo 'selected = "selected"'; ?>>TSSD</option>
							<option value="IMSD" <?php if($ChangeDept['Dept'] == 'IMSD') echo 'selected = "selected"'; ?>>IMSD</option>
							<option value="Oriental Mindoro Field Office" <?php if($ChangeDept['Dept'] == 'Oriental Mindoro Field Office') echo 'selected = "selected"'; ?>>Oriental Mindoro Field Office</option>
							<option value="Occidental Mindoro Field Office" <?php if($ChangeDept['Dept'] == 'Occidental Mindoro Field Office') echo 'selected = "selected"'; ?>>Occidental Mindoro Field Office</option>
							<option value="Romblon Field Office" <?php if($ChangeDept['Dept'] == 'Romblon Field Office') echo 'selected = "selected"'; ?>>Romblon Field Office</option>
							<option value="Palawan Field Office" <?php if($ChangeDept['Dept'] == 'Palawan Field Office') echo 'selected = "selected"'; ?>>Palawan Field Office</option>
							<option value="Marinduque Field Office" <?php if($ChangeDept['Dept'] == 'Marinduque Field Office') echo 'selected = "selected"'; ?>>Marinduque Field Office</option>
						</select>
				</div>
            </div>
            <div style="padding: 19px 20px 20px; margin-top: 15px; text-align: center; border-top: 1px solid #e5e5e5;">
                <input type="submit" class="btn btn-success" value="Save" id="btnChange" name="btnChange" style="height:50px;width:200px;font-family: 'Century Gothic'; font-weight: 700; text-align: center;"/>
            </div>
            </form>
        </div>
    </div>
</div>