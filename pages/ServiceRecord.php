
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

$EmpNoquery = $pdo->prepare("SELECT EmpNo, CONCAT(Fname, ' ',Mname, ' ', Lname) AS Name, Dept FROM i WHERE EmpNo = ?");
$EmpNoquery->execute([$_SESSION["EmpID"]]);
$EmpNoRow = $EmpNoquery->fetch();

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
                                    <form action="ServiceRec.php?id=<?php echo $EmpNoRow['EmpNo'];?>" method="post">                    
                                    
                                    <!-- paste dito -->
                                    <!--  page-wrapper -->
                    <div id="page-wrapper">

                        
                        <div class="row">
                            <!--  page header -->
                            <div class="col-lg-12">
                                <h1 class="page-header">Service Record</h1>
                            </div>
                            <!-- end  page header -->
                        </div>
                        <div class="row">
                <div class="col-lg-12">
                    <!-- Service Record Table -->
                    <div class="card">                       
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="card">
                                    <div class="card-header" style="color: #FFFFFF; background-color: #006666; font-weight: 600;">
                                        EMPLOYEE INFORMATION
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px;">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 20%; font-weight: 600;">Name</td>
                                                        <td style="width: 80%; font-weight: bold;">
                                                            <?php echo $EmpNoRow['Name']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 20%; font-weight: 600;">Department</td>
                                                        <td style="width: 80%; font-weight: bold;">
                                                            <?php echo $EmpNoRow['Dept']; ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-striped table-bordered table-hover" id="dataTablesVI">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="text-align: center; font-size: small;">
                                                <span class="style2">Service</span><br/>
                                                <span class="style4">(Inclusive Dates)</span>
                                            </td>
                                            <td colspan="3" style="text-align: center; font-size: small;">
                                                <span class="style2">Records of Appointment</span>
                                            </td>
                                            <td colspan="2" style="text-align: center; font-size: small;">
                                                <span class="style2">Office Entity/Division</span>
                                            </td>
                                            <td rowspan="2" style="text-align: center; font-size: small; width:7%">
                                                <span class="style2">Leave of Absence W/o pay</span>
                                            </td>
                                            <td colspan="2" style="text-align: center; font-size: small;">
                                                <span class="style2">Separation</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="style4" style="text-align: center; width:2%;">From</td>
                                            <td class="style4" style="text-align: center; width:2%;">To</td>
                                            <td class="style4" style="text-align: center; width:8%;">Designation</td>
                                            <td class="style4" style="text-align: center; width:7%;">Status</td>
                                            <td class="style4" style="text-align: center; width:7%;">Salary</td>
                                            <td class="style4" style="text-align: center; width:8%;">Station/Place of Assign</td>
                                            <td class="style4" style="text-align: center; width:5%;">Branch</td>
                                            <td class="style4" style="text-align: center; width:2%;">Date</td>
                                            <td class="style4" style="text-align: center; width:5%;">Cause</td>
                                        </tr>
                                        <?php 
                                        $query = $pdo->query("SELECT * FROM servicerecord WHERE EmpNo = '". $_SESSION["EmpID"] ."'");
                                        if($query->rowCount() == 0) {
                                        ?>
                                            <tr>
                                                <td><input name="ServiceFrom1" class="form-control" type="date" id="ServiceFrom1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="ServiceTo1" class="form-control" type="date" id="ServiceTo1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="Designation1" class="form-control" type="text" id="Designation1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="Status1" class="form-control" type="text" id="Status1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="Salary1" class="form-control" type="text" id="Salary1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="Place1" class="form-control" type="text" id="Place1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="Branch1" class="form-control" type="text" id="Branch1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="Absence1" class="form-control" type="text" id="Absence1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="SepaDate1" class="form-control" type="date" id="SepaDate1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="SepaCause1" class="form-control" type="text" id="SepaCause1" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                            </tr>
                                        <?php 
                                        } else {
                                            $i = 0;
                                            while($row = $query->fetch()) {
                                                $i++;
                                        ?>
                                            <tr>
                                                <td><input name="<?php echo 'ServiceFrom'.$i;?>" class="form-control" type="date" value="<?php echo $row['ServiceFrom'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'ServiceTo'.$i;?>" class="form-control" type="date" value="<?php echo $row['ServiceTo'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'Designation'.$i;?>" class="form-control" type="text" value="<?php echo $row['Designation'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'Status'.$i;?>" class="form-control" type="text" value="<?php echo $row['Status'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'Salary'.$i;?>" class="form-control" type="text" value="<?php echo $row['Salary'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'Place'.$i;?>" class="form-control" type="text" value="<?php echo $row['AssignStation'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'Branch'.$i;?>" class="form-control" type="text" value="<?php echo $row['Branch'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'Absence'.$i;?>" class="form-control" type="text" value="<?php echo $row['LeaveAbsence'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'SepaDate'.$i;?>" class="form-control" type="date" value="<?php echo $row['SepaDate'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                                <td><input name="<?php echo 'SepaCause'.$i;?>" class="form-control" type="text" value="<?php echo $row['SepaCause'];?>" <?php if($_SESSION['Access'] != 'Admin') echo "disabled";?>/></td>
                                            </tr>
                                        <?php 
                                            }
                                        }
                                        ?>
                                        <input name="numtext" type="hidden" id="numtext" value="<?php if ($query->rowCount() != 0) echo $query->rowCount(); else echo '1'?>">
                                    </tbody>
                                </table>

                                <?php if(isset($_SESSION['Dept']) && $_SESSION['Dept'] == "City Human Resource Management Department") { ?>
                                    <div align="center">
                                        <input class="btn btn-primary" type="button" value="Add Service Record" id="addtext" name="addtext" style="height:35px;width:300px;font-family: 'Century Gothic'; font-weight: 700; text-align: center;" />
                                    </div>    
                                <?php } ?>               
                            </div>                            
                        </div>
                    </div>
                    <!--End Service Record Table. -->

                    <?php if(isset($_SESSION['Dept']) && $_SESSION['Dept'] == "Human Resource Department") { ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTablesIV">
                                <tbody>
                                    <div align="center">
                                        <input class="btn btn-success" type="submit" value="SAVE" id="Save" name="Save" style="height:50px;width:300px;font-family: 'Century Gothic'; font-weight: 700; text-align: center;" />
                                    </div>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>

		</div>
                
            </div>
        </section>
    </div>
    <?php //include("modalpassword.php"); ?>
</div>
<!-- end of body -->

<?php
include_once('../partials/footer.php');
?>

<script>  
     $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
</script>

<script type="text/javascript">
    	document.getElementById("addtext").onclick = function() 
    {
		var numtext = parseInt(document.getElementById("numtext").value) + 1;
		
		var table = document.getElementById("dataTablesVI");
		var row = table.insertRow(numtext + 1);

		var Cell1 = row.insertCell(0);
		var Cell2 = row.insertCell(1);
		var Cell3 = row.insertCell(2);
		var Cell4 = row.insertCell(3);
		var Cell5 = row.insertCell(4);
		var Cell6 = row.insertCell(5);
		var Cell7 = row.insertCell(6);
		var Cell8 = row.insertCell(7);
		var Cell9 = row.insertCell(8);
		var Cell10 = row.insertCell(9);
		
        var input = document.createElement("input");
        input.type = "date";
		input.name = "ServiceFrom" + numtext;
		input.className = "form-control";
		Cell1.appendChild(input);
		
		var input1 = document.createElement("input");
        input1.type = "date";
		input1.name = "ServiceTo" + numtext;
		input1.className = "form-control";
		Cell2.appendChild(input1);
		
		var input2 = document.createElement("input");
        input2.type = "text";
		input2.name = "Designation" + numtext;
		input2.className = "form-control";
		Cell3.appendChild(input2);
		
		var input3 = document.createElement("input");
        input3.type = "text";
		input3.name = "Status" + numtext;
		input3.className = "form-control";
		Cell4.appendChild(input3);
		
		var input4 = document.createElement("input");
        input4.type = "text";
		input4.name = "Salary" + numtext;
		input4.className = "form-control";
		Cell5.appendChild(input4);
		
		var input5 = document.createElement("input");
        input5.type = "text";
		input5.name = "Place" + numtext;
		input5.className = "form-control";
		Cell6.appendChild(input5);
		
		var input6 = document.createElement("input");
        input6.type = "text";
		input6.name = "Branch" + numtext;
		input6.className = "form-control";
		Cell7.appendChild(input6);
		
		var input7 = document.createElement("input");
        input7.type = "text";
		input7.name = "Absence" + numtext;
		input7.className = "form-control";
		Cell8.appendChild(input7);
		
		var input8 = document.createElement("input");
        input8.type = "date";
		input8.name = "SepaDate" + numtext;
		input8.className = "form-control";
		Cell9.appendChild(input8);
		
		var input9 = document.createElement("input");
        input9.type = "text";
		input9.name = "SepaCause" + numtext;
		input9.className = "form-control";
		Cell10.appendChild(input9);

		document.getElementById("numtext").value = numtext;
    }
    </script>
</form>
            