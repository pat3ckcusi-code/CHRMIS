
<?php
session_start();
if(!isset($_SESSION['Status'])){
  header("location: ../index.php");
}else{
      $access_level = $_SESSION['Status'];
    }
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
 

?>
<!-- navbar top and side -->
 <?php include("../includes/navbar.php"); ?>
<!-- end navbar top and side -->
    <!-- start of body -->
        <div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
					<h1>Leave Application</h1>
					</div>
				</div>
				</div>
			</section>
		
    <!-- Content Wrapper. Contains page content -->
            <div class="content">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">                        
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <form action="LeaveInfo.php" method="post" enctype="multipart/form-data">      
                        <!-- paste dito -->
                         <!--  page-wrapper -->
							<div class="card">
							<div class="card-header bg-primary text-white">
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="dataTables-example" class="table table-striped table-bordered table-hover">
										<thead >
											<tr>
												<th>Name</th>
												<th>Department</th>
												<th>Type of Leave</th>												
												<th>From</th>
												<th>To</th>
												<th>Date Filed</th>
												<th>No. of Day(s)/Hour(s)</th>
												<th class="text-center">Approve</th>
												<th class="text-center">Disapprove</th>
												<!-- <?php if ($_SESSION['Dept'] == "City Human Resource Management Department") { ?>
													<th class="text-center">Print</th>
												<?php } ?> -->
											</tr>
										</thead>
										<tbody>
											<?php
											if ($_SESSION['Dept'] == "City Human Resource Management Department") {
												$Leavequery = $pdo->query("
													SELECT f.LeaveID, 
														CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name, 
														i.Dept, f.LeaveType, f.DateFiled, f.DateFrom, f.DateTo, 
														f.NumDays, f.Remarks, f.Reason 
													FROM filedleave f, i 
													WHERE f.EmpNo = i.EmpNo 
													AND f.Remarks = '". $_SESSION['Status'] ."' 
													ORDER BY f.DateFrom DESC
												");
											} else if ($_SESSION['Dept'] != "Office of the Mayor") {
												$Leavequery = $pdo->query("
													SELECT f.LeaveID, 
														CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name, 
														i.Dept, f.LeaveTypeCode, f.DateFiled, f.Purpose, f.DateFrom, 
														f.DateTo, f.TotalDays AS NumDays 
													FROM filedleave f, i 
													WHERE f.EmpNo = i.EmpNo 
													AND i.Dept = '". $_SESSION['Dept'] ."' 
													AND f.Status = '". $_SESSION['Status'] ."'
												");
											} else {
												$Leavequery = $pdo->query("
													SELECT f.LeaveID, 
														CONCAT(i.Fname, ' ', i.Mname, ' ', i.Lname) AS Name, 
														i.Dept, f.LeaveType, f.DateFiled, f.Purpose, 
														f.DateFrom, f.DateTo, f.NumDays 
													FROM filedleave f, i 
													WHERE f.EmpNo = i.EmpNo 
													AND f.Remarks = '". $_SESSION['Status'] ."'
												");
											}

											if ($Leavequery->rowCount() == 0) {
												echo "<tr><td colspan='9' class='text-center text-muted'>No records found</td></tr>";
											} else {
												while ($Leaverow = $Leavequery->fetch()) {
											?>
												<tr>
													<td><?php echo htmlspecialchars($Leaverow['Name']); ?></td>
													<td><?php echo htmlspecialchars($Leaverow['Dept']); ?></td>
													<td><?php echo htmlspecialchars($Leaverow['LeaveType']); ?></td>
													<td><?php echo htmlspecialchars($Leaverow['DateFrom']); ?></td>
													<td><?php echo htmlspecialchars($Leaverow['DateTo']); ?></td>
													<td><?php echo htmlspecialchars($Leaverow['DateFiled']); ?></td>
													<td><?php echo htmlspecialchars($Leaverow['NumDays']); ?></td>
													<td class="text-center">
														<a href="../includes/Approval.php?id=<?php echo $Leaverow['LeaveID']; ?>" 
														class="btn btn-success btn-sm rounded-circle" 
														data-toggle="tooltip" title="Approve">
															<i class="fa fa-check"></i>
														</a>
													</td>
													<td class="text-center">
														<a href="Disapproval.php?id=<?php echo $Leaverow['LeaveID']; ?>" 
														class="btn btn-danger btn-sm rounded-circle" 
														data-toggle="tooltip" title="Disapprove">
															<i class="fa fa-times"></i>
														</a>
													</td>
													<!-- <?php if ($_SESSION['Dept'] == "City Human Resource Management Department") { ?>
														<td class="text-center">
															<a href="APPLICATION_FOR_LEAVE.php?id=<?php echo $Leaverow['LeaveID']; ?>">
																<?php if ($Leaverow['Reason'] != 'Printed') { ?>
																	<button class="btn btn-info btn-sm rounded-circle" 
																			type="button" title="Print">
																		<i class="fa fa-print"></i>
																	</button>
																<?php } else { ?>
																	<button class="btn btn-primary btn-sm rounded-circle" 
																			type="button" title="Reprint">
																		<i class="fa fa-print"></i>
																	</button>
																<?php } ?>
															</a>
														</td>
													<?php } ?> -->
												</tr>
											<?php
												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
			        <!-- end page-wrapper -->
                        <!-- hanggahgn dito -->
                        </form>
                    </div>
                </section>
            </div>
		</div>
    <?php //include("modalpassword.php"); ?>
</div>
<!-- end of body -->

<?php
include_once('../partials/footer.php');
include_once('../partials/modals/modal_add_user.php');
?>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable( {
        "order": [[ 4, "desc" ]]
    } );
			//table.order([4, 'desc']).draw();
        });
    </script>
<script>  

</script>

            