
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

$Lquery = $pdo->prepare("SELECT EmpNo, CONCAT(Fname, ' ',Mname, ' ', Lname) AS Name, Dept FROM i WHERE EmpNo = ?");
$Lquery->execute([$_SESSION["EmpID"]]);
$Lrow = $Lquery->fetch();


$Cquery = $pdo->prepare("SELECT * FROM leavecredits WHERE EmpNo = ?");
$Cquery->execute([$_SESSION["EmpID"]]);
$Crow = $Cquery->fetch();

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
                        <form action="saveinfo.php" method="post">                    
                        
                        <!-- paste dito -->
                         <!--  page-wrapper -->
        <div id="page-wrapper">            
            <div class="row">
                 <!--  page header -->
                <div class="col-lg-12">
                    <h1 class="page-header">Filed Leave</h1>
                </div>
                 <!-- end  page header -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Table IV -->
                    <div class="card card-teal">
						<div class="card">
					<div class="card-body">
						<input name="numtext" type="hidden" id="numtext" value="1">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover" id="dataTablesLeaveCredits">
								<thead>
								</thead>
								<tbody>
									<tr>
										<td>&nbsp;</td>
										<td colspan="1" style="text-align: center; font-size: small; color: #FFFFFF; background-color: #006666" class="style2">
											EMPLOYEE DETAILS
										</td>
										<td colspan="4" style="text-align: center; font-size: small; color: #FFFFFF; background-color: #006666" class="style2">
											LEAVE CREDITS
										</td>
									</tr>
									<tr>
										<td class="style2">ID Number</td>
										<td>
											<input name="txtID" type="text" id="txtID" class="form-control" 
												value="<?php echo $Lrow['EmpNo'];?>" disabled="true"/>
										</td>
										<td class="style2" style="width:10%;">Vacation Leave</td>
										<td style="width:10%;">
											<input name="txtVL" type="text" id="txtVL" class="form-control" 
												value="<?php echo isset($Crow['VL']) ? $Crow['VL'] : ''; ?>" disabled="true"/>
										</td>
										<td class="style2" style="width:10%;">Compensatory Time Off Leave</td>
										<td style="width:10%;">
											<input name="txtCTO" type="text" id="txtCTO" class="form-control" 
												value="<?php echo isset($Crow['CTO']) ? ($Crow['CTO'] * 8) . ' hours' : '0 hours'; ?>" disabled="true"/>
										</td>
									</tr>
									<tr>
										<td class="style2">Name</td>
										<td>
											<input name="txtName" type="text" id="txtName" class="form-control" 
												value="<?php echo $Lrow['Name'];?>" disabled="true"/>
										</td>
										<td class="style2" style="width:10%;">Sick Leave</td>
										<td style="width:10%;">
											<input name="txtSL" type="text" id="txtSL" class="form-control" 
												value="<?php echo isset($Crow['SL']) ? $Crow['SL'] : ''; ?>" disabled="true"/>
										</td>
										<td class="style2" style="width:10%;">Special Purpose Leave</td>
										<td style="width:10%;">
											<input name="txtSPL" type="text" id="txtSPL" class="form-control" 
												value="<?php echo isset($Crow['SPL']) ? $Crow['SPL'] : ''; ?>" disabled="true"/>
										</td>
									</tr>
									<tr>
										<td class="style2">Department</td>
										<td>
											<input name="txtDept" type="text" id="txtDept" class="form-control" 
												value="<?php echo $Lrow['Dept'];?>" disabled="true"/>
										</td>
										<td style="width:10%;"></td>
										<td>&nbsp;</td>
										<td style="width:10%;"></td>
										<td>&nbsp;</td>
									</tr>
								</tbody>
							</table>                
						</div>                            
					</div>
				</div>

					<div class="card">
						<div class="card-header" style="color: #FFFFFF; background-color: #006666">
							FILED LEAVE
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTablesFiledLeave">
									<thead>
										<tr>
											<th style="width:15%;">Leave Type</th>
											<th style="width:10%;">Date From</th>
											<th style="width:10%;">Date To</th>
											<th style="width:10%;">No. of Day(s)</th>
											<th style="width:15%;">Remarks</th>
											<th style="width:20%;">Status of Approval</th>
											<th style="width:5%;">Action</th>
											<!-- <?php if(!($_SESSION['Access'] == 'Admin')) { ?>
												<th style="width:5%;"></th>
											<?php } ?> -->
										</tr>
									</thead>
									<tbody>
										<?php 
										$Filedquery = $pdo->query("SELECT * FROM filedleave WHERE EmpNo = '". $_SESSION["EmpID"] ."'");
										if(!($Filedquery->rowCount() == 0)) {
											while($Filedrow = $Filedquery->fetch()) {
												echo "<tr>";
													echo "<td>{$Filedrow['LeaveType']}</td>";
													echo "<td>{$Filedrow['DateFrom']}</td>";
													echo "<td>{$Filedrow['DateTo']}</td>";
													echo "<td>{$Filedrow['NumDays']}</td>";
													echo "<td>{$Filedrow['Remarks']}</td>";
													echo "<td>{$Filedrow['Reason']}</td>";

													if(!($_SESSION['Access'] == 'Admin')) {
													echo "<td>";
													if ($Filedrow['Remarks'] == "FOR RECOMMENDATION") {
														echo "<button type='button' class='btn btn-danger btn-sm cancel-leave'
																data-id='{$Filedrow['LeaveID']}'
																style='height:35px;width:100px;
																	font-family: Century Gothic;
																	font-weight: 700;
																	text-align: center;'>
																Cancel
															</button>";															
													}if ($Filedrow['Remarks'] == "APPROVED") {
														echo "
															<button class='btn btn-primary btn-sm rounded-circle print-leave' 
																data-id='{$Filedrow['LeaveID']}' 
																type='button' 
																title='Print Leave Form'
																style='margin-left: 10px;'>
																<i class='fa fa-print'></i>
															</button>
														";
													}


													// if ($Filedrow['Remarks'] == "APPROVED") {
													// //print
													// 		echo "<a href='../includes/APPLICATION_FOR_LEAVE.php?id={$Filedrow['LeaveID']}'>
													// 			<button class='btn btn-primary btn-sm rounded-circle' type='button' title='Print Leave Form'
													// 				style='margin-left: 10px;'>
													// 				<i class='fa fa-print'></i>
													// 			</button>
													// 		</a>";
													// }
														echo "</td>";
													}
												echo "</tr>";
											}
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="card">
						<div class="card-header" style="color: #FFFFFF; background-color: #006666">
							ADDED COMPENSATORY TIME OFF LEAVE HISTORY
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTablesCTOHistory">
									<thead>
										<tr>
											<th style="width:10%;">Total Number of Hours Worked</th>
											<th style="width:10%;">Date</th>
											<th style="width:20%;">Reason</th>
											<th style="width:20%;">Type of Holiday</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$CTOquery = $pdo->query("SELECT * FROM ctohistory WHERE EmpNo = '". $_SESSION["EmpID"] ."'");
										if(!($CTOquery->rowCount() == 0)) {
											while($CTOrow = $CTOquery->fetch()) {
												echo "<tr>";
													echo "<td>{$CTOrow['NumHours']}</td>";
													echo "<td>{$CTOrow['Date']}</td>";
													echo "<td>{$CTOrow['Reason']}</td>";
													echo "<td>{$CTOrow['HolidayType']}</td>";
												echo "</tr>";
											}
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

                </div>
			</div>
		</div>
        <!-- end page-wrapper -->

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
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(document).ready(function () {
		$('#dataTablesFiledLeave').dataTable();
		$('#dataTablesCTOHistory').dataTable();
	}); 
	
	
    //cancel
		$(document).on('click', '.cancel-leave', function () {
		let leaveId = $(this).data('id');

		Swal.fire({
			title: "Are you sure?",
			text: "Do you really want to cancel this leave application?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#d33",
			cancelButtonColor: "#3085d6",
			confirmButtonText: "Yes, cancel it!",
			cancelButtonText: "No, keep it"
		}).then((result) => {
			if (result.isConfirmed) {
			window.location.href = "../includes/functions/Cancel.php?id=" + leaveId;
			}
		});
		});

	//print
	document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".print-leave").forEach(function(btn) {
        btn.addEventListener("click", function() {
            let leaveId = this.getAttribute("data-id");

            // Show SweetAlert spinner
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we generate your Leave Form.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Open the loader tab
            window.open('../includes/pdf_loader.php?id=' + leaveId, '_blank');
        });
    });
});

</script>

            