
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

$Lquery=$pdo->query("SELECT EmpNo, CONCAT(Fname, ' ',Mname, ' ', Lname) AS Name, Dept FROM i WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");
$Lrow = $Lquery->fetch();


$Cquery = $pdo->query("SELECT * FROM leavecredits WHERE EmpNo = '" . $_SESSION["EmpID"] . "'");
$Crow = $Cquery->fetch();

$VL = ($Crow && isset($Crow['VL'])) ? $Crow['VL'] : 'N/A';
$ctoValue = ($Crow && isset($Crow['CTO'])) ? $Crow['CTO'] : 'N/A';
$SL = ($Crow && isset($Crow['SL'])) ? $Crow['SL'] : 'N/A';
$SPL = ($Crow && isset($Crow['VL'])) ? $Crow['VL'] : 'N/A';
$CL = ($Crow && isset($Crow['VL'])) ? $Crow['VL'] : 'N/A';
?>
<!-- navbar top and side -->
 <?php include("../includes/navbar.php"); ?>
<!-- end navbar top and side -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <!-- start of body -->
        <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">                            
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- <form action="../includes/LeaveInfo.php" method="post">                     -->
                        <form id ='leave-application' method="post">
                        <!-- paste dito -->
                         <!--  wrapper -->
                    <div id="wrapper">
                        <!--  page-wrapper -->
                        <div id="page-wrapper">
                            <div class="row">
                                <!--  page header -->
                                <div class="col-lg-12">
                                    <h1 class="page-header">File Leave Application</h1>
                                </div>
                                <!-- end  page header -->
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- Table IV -->
                                    <div class="card card-primary">                                        
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="dataTablesIV">
                                                    <tbody>
                                                        <tr class="text-center">
                                                            <td>&nbsp;
                                                                </td>
                                                            <td>&nbsp;
                                                                </td>
                                                            <td colspan="4" style="text-align: center; font-size: large; color: #FFFFFF; background-color: #006666" class="style2">LEAVE CREDITS</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold" >ID Number</td>
                                                            <td>
                                                                <input name="txtID" type="text" id="txtID" 
                                                                    class="form-control" 
                                                                    value="<?php echo $Lrow['EmpNo'];?>" disabled>
                                                            </td>
                                                            <td class="font-weight-bold" style="width:15%;">Vacation Leave</td>
                                                            <td style="width:15%;">
                                                                <input name="txtVL" type="text" id="txtVL" 
                                                                    class="form-control" 
                                                                    value="<?php echo htmlspecialchars($VL); ?>" disabled>
                                                            </td>
                                                            <td class="font-weight-bold" style="width:20%;">Compensatory Time Off Leave</td>
                                                            <td style="width:15%;">
                                                                <input name="txtCTO" type="text" id="txtCTO" 
                                                                    class="form-control" 
                                                                    value="<?php echo htmlspecialchars($ctoValue); ?>" disabled>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold">Name</td>
                                                            <td>
                                                                <input name="txtName" type="text" id="txtName" 
                                                                    class="form-control" 
                                                                    value="<?php echo $Lrow['Name'];?>" disabled>
                                                            </td>
                                                            <td class="font-weight-bold">Sick Leave</td>
                                                            <td>
                                                                <input name="txtSL" type="text" id="txtSL" 
                                                                    class="form-control" 
                                                                    value="<?php echo htmlspecialchars($SL); ?>" disabled>
                                                            </td>
                                                            <td class="font-weight-bold">Special Privilege Leave</td>
                                                            <td>
                                                                <input name="txtSPL" type="text" id="txtSPL" 
                                                                    class="form-control" 
                                                                    value="<?php echo htmlspecialchars($SPL); ?>" disabled>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="font-weight-bold">Department</td>
                                                            <td>
                                                                <input name="txtDept" type="text" id="txtDept" 
                                                                    class="form-control" 
                                                                    value="<?php echo $Lrow['Dept'];?>" disabled>
                                                            </td>
                                                            <!-- <td class="font-weight-bold">Compassionate Time-Off Leave</td>
                                                            <td>
                                                                <input name="txtCTO2" type="text" id="txtCTO2" 
                                                                    class="form-control" 
                                                                    value="<?php echo htmlspecialchars($CL); ?>" disabled>
                                                            </td> -->
                                                            <!-- <td></td> -->
                                                            <!-- <td></td> -->
                                                        </tr>
                                                    </tbody>
                                                </table>                
                                            </div>                            
                                        </div>
                                    </div>
                                    <div class="card card-success">
                                        <div class="card-header" style="color: #FFFFFF; background-color: #006666">
                                            <h3 class="card-title">FILE LEAVE</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover"  id="dataTablesIV">
                                                <thead style="color: #FFFFFF; background-color: #006666" >
                                                <tr>
                                                    <th style="font-size: medium;">Leave Type</th>
                                                    <th style="font-size: x-small; width: 2%;">1/2<br>Day</th>
                                                    <th style="font-size: medium; width: 20%;">From</th>
                                                    <th style="font-size: medium; width: 20%;">To</th>
                                                    <th style="font-size: medium;">No. of Working Day(s)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
											<td id="Filetext1">
											<select class="form-control" name="ddLeaveType" id="ddLeaveType" onchange="getindex()">
												<option value="Vacation Leave">Vacation Leave</option>
												<option value="Mandatory / Forced Leave">Mandatory / Forced Leave</option>
												<option value="Sick Leave">Sick Leave</option>
												<option value="Maternity Leave">Maternity Leave</option>
												<option value="Paternity Leave">Paternity Leave</option>
												<option value="Special Privilege Leave">Special Privilege Leave</option>
												<option value="Solo Parent Leave">Solo Parent Leave</option>
												<option value="Study Leave">Study Leave</option>
												<option value="10 Day VAWC Leave">10 Day VAWC Leave</option>
												<option value="Rehabilitation Privilege">Rehabilitation Privilege</option>
												<option value="Special Leave Benefits for Women">Special Leave Benefits for Women</option>
												<option value="Special Emergency Leave">Special Emergency Leave</option>
												<option value="Adoption Leave">Adoption Leave</option>
												<option value="Compensatory Time Off Leave">Compensatory Time Off Leave</option>
												<option value="Others">Others</option>
											</select>
											<input type="hidden" id = "sel" name="sel" value="0">
											</td>
											<td id="Filetext2" style="text-align: center;">
												<input id="cbHalfDay" type="checkbox" name="cbHalfDay" class="checked"/>
											</td>
											<td id="Filetext3">
												<input name="txtFrom" type="date" id="txtFrom" class="form-control" onblur = "checkdate()" required="required"/>
												<input type="hidden" name="txttoday" id="txttoday" value="<?php date_default_timezone_set('Asia/Manila'); echo date('Y-m-d');?>">
											</td>
											<td id="Filetext4">
												<input name="txtTo" type="date" id="txtTo" class="form-control" onblur = "checkdate()" required="required"/>
											</td>
											<td id="Filetext5">
												<input name="txtWorkDays" type="text" id="txtWorkDays" class="form-control" onclick = "getNum(this.attributes['name'].value)" onkeyup = "getNum(this.attributes['name'].value)" required="required"/>
											</td>
										</tr>                                                
                                        </tbody>
                                    </table>
                                        <div id="leave-extra-fields" class="d-none">
                                            <!-- File Upload -->
                                            <div class="form-group">
                                                <label for="fileToUpload">Please upload your Medical Certificate:</label>
                                                <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
                                            </div>

                                            <!-- Reason Select -->
                                            <div class="form-group">
                                                <label for="ddReason">Reason of Leave:</label>
                                                <select class="form-control" name="ddReason" id="ddReason">
                                                <option value="1">Filial obligations to parents and siblings...</option>
                                                <option value="2">Family Reunions</option>
                                                <option value="3">Death Anniversary of immediate family...</option>
                                                <option value="4">Ante-mortem activities involving...</option>
                                                <option value="5">Attendance in court hearings...</option>
                                                </select>
                                            </div>
                                        </div>								
                                <div align="center">
                    	        	<!-- <input class="btn btn-success" type="submit" value="File" id="btnFile" name="btnFile" style="height:35px;width:300px;font-family: 'Century Gothic'; font-weight: 700; text-align: center;" /> -->
									<!-- <button type="submit" name="btnFile" class="btn btn-primary">File Leave</button> -->
									 <input type="hidden" name="btnFile" value="1">
									 <input class="btn btn-success" type="submit" value="File" id="btnFile" style="height:35px;width:300px;font-family: 'Century Gothic'; font-weight: 700; text-align: center;" />
								</div>
                                </div>
                                </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- end page-wrapper -->
                    </div>
                    <!-- end wrapper -->

                        <!-- hanggang dito -->
                        </form>
                    </div>
                </section>
    </div>
    <?php //include("modalpassword.php"); ?>
</div>
<!-- end of body -->

<?php
include_once('../partials/footer.php');
include("../partials/modals/modalpassword.php");
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script type="text/javascript">
	
	function getindex()
	{
		var sel = document.getElementById('ddLeaveType');
		document.getElementById('sel').value = sel.selectedIndex;
		if(sel.selectedIndex == 14)
		{
			$('#divreason').show();
			document.getElementById("ddReason").required = true;
		}
		else
		{
			$('#divreason').hide();
			document.getElementById("ddReason").required = false;
		}
	}
	
	function checkdate()
	{
		getindex();
		var sel = document.getElementById('sel').value;
		if(sel != 2 && sel != 5)
		{
			var datefrom = new Date(document.getElementById("txtFrom").value);
			var dateto = new Date(document.getElementById("txttoday").value);
			//var timeDiff = Math.round(((datefrom-dateto)/(1000*60*60*24))+1);
			//var timeDiff = calcBusinessDays(dateto,datefrom) - 1 ;
			var timeDiff = Math.round(((datefrom-dateto)/(1000*60*60*24))+1);
			//alert(timeDiff);
			if(timeDiff <= 5)
			{
				alert("You should file this leave five (5) days before the actual day.");
				location.reload();
			}
		}
		else if(sel == 2)
		{
			var datefrom1 = new Date(document.getElementById("txtFrom").value);
			var datefrom = new Date(document.getElementById("txtTo").value);
			var dateto = new Date(document.getElementById("txttoday").value);
			//var timeDiff = Math.round(((dateto-datefrom)/(1000*60*60*24))+1);
			var timeDiff = calcBusinessDays(datefrom, dateto) - 1;
			//var timeDiff = Math.round(((datefrom-dateto)/(1000*60*60*24))+1);
			if(timeDiff > 1)
			{
				alert("You should file this leave immediately after your return.");
				location.reload();
			}

			var timeDiff2 = calcBusinessDays(datefrom1, datefrom);

			if(timeDiff2 != 'NaN')
			{
				if(timeDiff2 >= 5)
				{
					$('#attachment').show();
					document.getElementById("fileToUpload").required = true;
				}
				else
				{
					$('#attachment').hide();
					document.getElementById("fileToUpload").required = false;
				}
			}
			
		}
	}
	
	function calcBusinessDays(dDate1, dDate2) 
	{ // input given as Date objects
		var iWeeks, iDateDiff, iAdjust = 0;
		if (dDate2 < dDate1) return -1; // error code if dates transposed
		var iWeekday1 = dDate1.getDay(); // day of week
		var iWeekday2 = dDate2.getDay();
		iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1; // change Sunday from 0 to 7
		iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
		if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1; // adjustment if both days on weekend
		iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1; // only count weekdays
		iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;

		// calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
		iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)

		if (iWeekday1 <= iWeekday2) {
		  iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
		} else {
		  iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
		}

		iDateDiff -= iAdjust // take into account both days on weekend

		return (iDateDiff + 1); // add 1 because dates are inclusive
	}
	
	function getNum(controlname)
	{		
		var datefrom = new Date(document.getElementById("txtFrom").value);
		var dateto = new Date(document.getElementById("txtTo").value);

		/*var sel = document.getElementById('sel').value;
		if(sel != 1)
			var timeDiff = Math.round(((dateto-datefrom)/(1000*60*60*24))+1);
		else*/
		var timeDiff = calcBusinessDays(datefrom,dateto);
		
		var boolCheck = document.getElementById("cbHalfDay").checked;
		
		if (timeDiff<1)
		{
			alert("Invalid date");
			document.getElementById("txtWorkDays").value = "";
		}
		else if(timeDiff==1)
		{
			if (boolCheck == true){
				document.getElementById("txtWorkDays").value = .5;
			}
			else{
				document.getElementById("txtWorkDays").value = timeDiff;
			}
		}
		else if(timeDiff>1)
		{
			if (boolCheck == true){
				alert("Ang CTO ay kailangang i-file tatlong bago ang nais na araw ng leave.");
			}
			else{
				document.getElementById("txtWorkDays").value = timeDiff;
			}
		}
		else
		{
			document.getElementById("txtWorkDays").value = timeDiff;
		}
		
		var sel = document.getElementById('ddLeaveType');
			if(sel.selectedIndex == 0)
			{
				if(document.getElementById("txtVL").value < timeDiff)
				{
					alert('Insufficient Leave Credits! It will be Leave Without PAY and will be deducted to your salary');
					document.getElementById("txtWorkDays").value = "";
					document.getElementById("txtTo").focus();
				}
			}
			else if(sel.selectedIndex == 2)
			{
				if(document.getElementById("txtSL").value < timeDiff)
				{
					
						alert('Insufficient Leave Credits!. It will be Leave Without PAY and will be deducted to your salary');
					document.getElementById("txtWorkDays").value = "";
					document.getElementById("txtTo").focus();
										
				}
			}
			else if(sel.selectedIndex == 13)
			{
				if(document.getElementById("txtCTO").value < timeDiff)
				{
					alert('Insufficient Leave Credits!');
					document.getElementById("txtWorkDays").value = "";
					document.getElementById("txtTo").focus();
				}
			}
			else if(sel.selectedIndex == 14)
			{
				if(document.getElementById("txtCTO2").value < timeDiff)
				{
					alert('Insufficient Leave Credits!');
					document.getElementById("txtWorkDays").value = "";
					document.getElementById("txtTo").focus();
				}
			}
			else if(sel.selectedIndex == 5)
			{
				if(document.getElementById("txtSPL").value < timeDiff)
				{
					alert('Insufficient Leave Credits!');
					document.getElementById("txtWorkDays").value = "";
					document.getElementById("txtTo").focus();
				}
			}
		
		if(document.getElementById("txtWorkDays").value == 'NaN')
		{
			document.getElementById("txtWorkDays").value = "";
		}
	}
    

	// file leave	


$(document).ready(function () {
  $('#leave-application').on('submit', function (e) {
    e.preventDefault(); // Stop default form submit

    Swal.fire({
      title: "Are you sure?",
      text: "Do you really want to file this leave application?",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes!",
      cancelButtonText: "No."
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
  url: "../includes/LeaveInfo.php",
  type: "POST",
  data: $('#leave-application').serialize() + "&btnFile=1", // ✅ force include
  success: function (response) {
    console.log("Server Response:", response);
  if (response.trim() === "success") {
    Swal.fire({
      icon: 'success',
      title: 'Leave Application Submitted',
      html: 'Please wait for approval.<br>Thank you!',
      confirmButtonColor: '#3085d6',
      timer: 3000,
      timerProgressBar: true
    });
    $('#leave-application')[0].reset();
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Submission Failed',
      html: response, // show exact error message from PHP
      confirmButtonColor: '#d33'
    });
  }
}
,
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Server Error',
              text: 'Could not connect to server.'
            });
          }
        });
      }
    });
  });
});

</script>


            