
<?php
session_start();
if(!isset($_SESSION['EmpID'])){
  header("location: ../index.php");
  exit;
}
require_once('../includes/initialize.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');

$query = $pdo->prepare("SELECT * FROM i WHERE EmpNo = ?");
$query->execute([$_SESSION["EmpID"]]);
$row = $query->fetch();
if ($row) {
    $empNoValue = $row['EmpNo'];
} else {
    $empNoValue = ''; // or some default value
}

$query1=$pdo->query("select * from ii where EmpNo = '" . $_SESSION["EmpID"] . "'");
$row1 = $query1->fetch();
$query11=$pdo->query("select * from ii where EmpNo = '" . $_SESSION["EmpID"] . "'");
$_SESSION['row11'] = $query11->rowCount();

$Equery=$pdo->query("select * from iii where EmpNo = '" . $_SESSION["EmpID"] . "' AND Level = 'ELEMENTARY'");
$Elem = $Equery->fetch();
$Squery=$pdo->query("select * from iii where EmpNo = '" . $_SESSION["EmpID"] . "' AND Level = 'SECONDARY'");
$Sec = $Squery->fetch();
$Vquery=$pdo->query("select * from iii where EmpNo = '" . $_SESSION["EmpID"] . "' AND Level = 'VOCATIONAL/TRADE COURSE'");
$Voc = $Vquery->fetch();
$Cquery=$pdo->query("select * from iii where EmpNo = '" . $_SESSION["EmpID"] . "' AND Level = 'COLLEGE'");
$Col = $Cquery->fetch();
$Gquery=$pdo->query("select * from iii where EmpNo = '" . $_SESSION["EmpID"] . "' AND Level = 'GRADUATE STUDIES'");
$Grad = $Gquery->fetch();

$query2=$pdo->query("select * from iii where EmpNo = '" . $_SESSION["EmpID"] . "'");
$_SESSION['row2'] = $query2->rowCount();

$childquery=$pdo->query("select * from children where EmpNo = '" . $_SESSION["EmpID"] . "'");
$a = 0;
while($child = $childquery->fetch())
{
    $a++;    
    $cname[$a] = $child['ChildName'];
    $cbirth[$a] = $child['ChildBirth'];
}
?>
<!-- navbar top and side -->
  <?php include("../includes/navbar.php"); ?>
<!-- end navbar top and side -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- change password -->
        <?php 
        if ($row['Password'] === 'password') {
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                Swal.fire({
                    title: "Change Password Required",
                    text: "Please change your password to continue.",
                    icon: "warning",
                    confirmButtonText: "OK"
                }).then(() => {
                    $("#modalPassword").modal("show");
                });
            });
        </script>
        <?php 
        }
        ?>


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
                <h1 class="page-header">Personal Data Sheet</h1>
                <!-- <form action="../includes/functions/saveinfo.php" method="post"> -->
                 <form id="savePage1" method="post">   
                    <!-- I. PERSONAL INFORMATION -->
<div class="card form-section">
  <div class="card-header text-white" style="background-color: #006666; font-family: 'Century Gothic';">
    <h3 class="card-title">I. PERSONAL INFORMATION</h3>
  </div>
  <div class="card-body">

    <!-- Basic Name Info -->
    <table class="table table-bordered table-hover">
      <tbody>
        <tr>
          <td class="text-center align-middle fw-bold">EMPLOYEE NO.</td>
          <td colspan="3">
            <input class="form-control form-control-sm" name="txtEmpNo" id="txtEmpNo"
                   value="<?php echo htmlspecialchars($empNoValue); ?>" required readonly>
          </td>
        </tr>
        <tr>
          <td class="text-center align-middle fw-bold">SURNAME</td>
          <td colspan="3">
            <input class="form-control form-control-sm" name="txtLname" id="txtLname"
                   value="<?php echo htmlspecialchars($row['Lname']); ?>" required>
          </td>
        </tr>
        <tr>
          <td class="text-center align-middle fw-bold">FIRST NAME</td>
          <td style="width:50%;">
            <input class="form-control form-control-sm" name="txtFname" id="txtFname"
                   value="<?php echo htmlspecialchars($row['Fname']); ?>" required>
          </td>
          <td class="text-center align-middle fw-bold">NAME EXTENSION (JR, SR)</td>
          <td>
            <input class="form-control form-control-sm" name="txtExt" id="txtExt"
                   value="<?php echo htmlspecialchars($row['Extension']); ?>">
          </td>
        </tr>
        <tr>
          <td class="text-center align-middle fw-bold">MIDDLE NAME</td>
          <td colspan="3">
            <input class="form-control form-control-sm" name="txtMname" id="txtMname"
                   value="<?php echo htmlspecialchars($row['Mname']); ?>">
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Birth / Citizenship / Address / Contact -->
    <table class="table table-bordered table-hover">
      <tbody>
        <tr class="bg-light">
          <td class="text-center align-middle fw-bold" style="width:17%;">DATE OF BIRTH<br><small>(yyyy-mm-dd)</small></td>
          <td class="text-center">
            <input class="form-control form-control-sm" type="date" name="txtDate" id="txtDate"
                   value="<?php echo htmlspecialchars($row['BirthDate']); ?>" required>
          </td>

          <!-- Nested Info Block -->
          <td rowspan="13" class="align-top">
            <table class="table table-bordered mb-0">
              <tbody>
                <!-- Citizenship -->
                <tr class="bg-light">
                  <td rowspan="2" class="text-center align-middle fw-bold">CITIZENSHIP<br>
                    <small>If dual, indicate details</small>
                  </td>
                  <td>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" id="rdFil" type="radio" name="rdCitizenship" value="Filipino"
                        <?php if($row['Citizenship']=='Filipino') echo 'checked'; ?>>
                      <label class="form-check-label" for="rdFil">Filipino</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" id="rdDual" type="radio" name="rdCitizenship" value="Dual Citizenship"
                        <?php if($row['Citizenship']=='Dual Citizenship') echo 'checked'; ?>>
                      <label class="form-check-label" for="rdDual">Dual Citizenship</label>
                    </div>
                    <div class="form-check form-check-inline mt-1">
                      <input class="form-check-input" id="rdBirth" type="radio" name="rdCitizenship" value="by Birth"
                        <?php if($row['Citizenship']=='by Birth') echo 'checked'; ?>>
                      <label class="form-check-label" for="rdBirth">By Birth</label>
                    </div>
                    <div class="form-check form-check-inline mt-1">
                      <input class="form-check-input" id="rdNatural" type="radio" name="rdCitizenship" value="by Naturalization"
                        <?php if($row['Citizenship']=='by Naturalization') echo 'checked'; ?>>
                      <label class="form-check-label" for="rdNatural">By Naturalization</label>
                    </div>
                    <p class="text-center mt-2 mb-1"><small>Please indicate country:</small></p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input class="form-control form-control-sm" name="ddCountry" id="ddCountry"
                           value="<?php echo htmlspecialchars($row['Country']); ?>">
                  </td>
                </tr>

                <!-- Residential Address -->
                <tr class="bg-light">
                  <td rowspan="6" style="text-align: center; font-size: small;">
                    <span class="style2">RESIDENTAL ADDRESS</span></td>
                    <td style="text-align: center; font-size: small;">
                        <input name="txtHouse" type="text" id="txtHouse" value="<?php echo $row['HouseNo'];?>" style="width:48%;" />&nbsp;
                        <input name="txtStreet" type="text" id="txtStreet" value="<?php echo $row['Street'];?>" style="width:48%;" />
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: small;" class="style2">
                        House/Block/Lot No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Street &nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: small;">
                        <input name="txtSubd" type="text" id="txtSubd" value="<?php echo $row['Subd'];?>" style="width:48%;" />&nbsp;
                        <input name="txtBrgy" type="text" id="txtBrgy" value="<?php echo $row['Brgy'];?>" style="width:48%;" required = "true"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: small;" class="style2">
                        Subdivision/Village&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Barangay &nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: small;">
                        <input name="txtCity" type="text" id="txtCity" value="<?php echo $row['City'];?>" style="width:48%;" required = "true"/>&nbsp;
                        <input name="txtProvince" type="text" id="txtProvince" value="<?php echo $row['Province'];?>" style="width:48%;" required = "true"/>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: small;"  class="style2">
                        City/Municipality&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Province &nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: small;" class="style2">
                        ZIP CODE</td>
                    <td >
                        <input class="form-control" name="txtZip1" type="text" id="txtZip1" value="<?php echo $row['Zip'];?>" style="width:100%;" required = "true"/>
                    </td>
                </tr>
            </tr>

                <!-- Permanent Address -->
                <tr class="bg-light">
                  <td style="text-align: center; font-size: small;"class="style2" rowspan="6">
                    PERMANENT ADDRESS</td>
                        <td >
                            <input name="txtHouse1" type="text" id="txtHouse1" value="<?php echo $row['Perm_House'];?>" style="width:48%;" />&nbsp;
                            <input name="txtStreet1" type="text" id="txtStreet1" value="<?php echo $row['Perm_Street'];?>" style="width:48%;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: small;" class="style2">
                            House/Block/Lot No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Street &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td >
                            <input name="txtSubd1" type="text" id="txtSubd1" value="<?php echo $row['Perm_Subd'];?>" style="width:48%;" />&nbsp;
                            <input name="txtBrgy1" type="text" id="txtBrgy1" value="<?php echo $row['Perm_Brgy'];?>" style="width:48%;" required = "true"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: small;" class="style2">
                            Subdivision/Village&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Barangay &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: small;">
                            <input name="txtCity1" type="text" id="txtCity" value="<?php echo $row['Perm_City'];?>" style="width:48%;" required = "true"/>&nbsp;
                            <input name="txtProvince1" type="text" id="txtProvince" value="<?php echo $row['Perm_Province'];?>" style="width:48%;" required = "true"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: small;"  class="style2">
                            City/Municipality&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Province &nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-size: small;"class="style2">
                            ZIP CODE</td>
                        <td >
                            <input class="form-control" name="txtZip2" type="text" id="txtZip2" value="<?php echo $row['Perm_Zip'];?>" style="width:100%;" required = "true"/>
                    </td>
                </tr>

                <!-- Contact Info -->
                <tr class="bg-light">
                  <td class="text-center align-middle fw-bold">CONTACT INFORMATION</td>
                  <td>
                    <input class="form-control form-control-sm mb-1" placeholder="Telephone No."
                           name="txtTel" value="<?php echo htmlspecialchars($row['TelNo']); ?>">
                    <input class="form-control form-control-sm mb-1" placeholder="Mobile No."
                           name="txtMobile" value="<?php echo htmlspecialchars($row['MobileNo']); ?>">
                    <input class="form-control form-control-sm" placeholder="Email Address"
                           name="txtEmail" value="<?php echo isset($row['EMail']) ? htmlspecialchars($row['EMail']) : ''; ?>" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>

        <!-- Place of Birth -->
                <tr>
                <td class="text-center align-middle fw-bold">PLACE OF BIRTH</td>
                <td>
                    <input class="form-control form-control-sm" name="txtPlace" id="txtPlace"
                            value="<?php echo isset($row['PlaceBirth']) ? htmlspecialchars($row['PlaceBirth']) : ''; ?>">
                </td>
                </tr>

                <!-- Sex -->
                <tr>
                <td class="text-center align-middle fw-bold">SEX</td>
                <td>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="txtSex" id="male" value="MALE" 
                        <?php if($row['Gender']=='MALE') echo 'checked'; ?>>
                        <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="txtSex" id="female" value="MALE" 
                        <?php if($row['Gender']=='FEMALE') echo 'checked'; ?>>        
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </td>
                </tr>

                <!-- Civil Status -->
                <tr>
                <td class="text-center align-middle fw-bold">CIVIL STATUS</td>
                <td>
                    <?php 
                    $civilStatus = $row['Civil'];
                    $statuses = ['SINGLE','MARRIED','WIDOWED','SEPARATED','OTHERS'];
                    foreach ($statuses as $s) {
                        $checked = ($civilStatus==$s)?'checked':'';
                        echo '<div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rdCivil" value="'.strtoupper($s).'" '.$checked.'>
                                <label class="form-check-label">'.$s.'</label>
                            </div>';
                    }
                    ?>              
                </td>
                </tr>

                <!-- Height & Weight -->
                <tr>
                <td class="text-center align-middle fw-bold">HEIGHT (m)</td>
                <td><input class="form-control form-control-sm" name="txtHeight" value="<?php echo htmlspecialchars($row['Height']); ?>"></td>
                </tr>
                <tr>
                <td class="text-center align-middle fw-bold">WEIGHT (kg)</td>
                <td><input class="form-control form-control-sm" name="txtWeight" value="<?php echo htmlspecialchars($row['Weight']); ?>"></td>
                </tr>

                <!-- Blood Type -->
                <tr>
                <td class="text-center align-middle fw-bold">BLOOD TYPE</td>
                <td><input class="form-control form-control-sm" name="txtBlood" value="<?php echo htmlspecialchars($row['BloodType']); ?>"></td>
                </tr>

                <!-- GSIS, Pag-IBIG, PhilHealth, SSS -->
                <tr>
                <td class="text-center align-middle fw-bold">GSIS ID NO.</td>
                <td><input class="form-control form-control-sm" name="txtGSIS" value="<?php echo htmlspecialchars($row['GSIS']); ?>"></td>
                </tr>
                <tr>
                <td class="text-center align-middle fw-bold">PAG-IBIG ID NO.</td>
                <td><input class="form-control form-control-sm" name="txtPagibig" value="<?php echo htmlspecialchars($row['Pagibig']); ?>"></td>
                </tr>
                <tr>
                <td class="text-center align-middle fw-bold">PHILHEALTH NO.</td>
                <td><input class="form-control form-control-sm" name="txtPhilhealth"  value="<?php echo isset($row['PHealth']) ? htmlspecialchars($row['PHealth']) : ''; ?>" />
                </tr>
                <tr>
                <td class="text-center align-middle fw-bold">SSS NO.</td>
                <td><input class="form-control form-control-sm" name="txtSSS" value="<?php echo htmlspecialchars($row['SSS']); ?>"></td>
                </tr>
                <tr>
                <td class="text-center align-middle fw-bold">TIN NO.</td>
                <td><input class="form-control form-control-sm" name="txtTIN" value="<?php echo htmlspecialchars($row['Tin']); ?>"></td>
                </tr>
                <tr>
                <td class="text-center align-middle fw-bold">AGENCY EMPLOYEE NO.</td>
                <td><input class="form-control form-control-sm" name="txtAgency" value="<?php echo htmlspecialchars($row['AgencyEmpNo']); ?>"></td>
                </tr>
            </tbody>
            </table>

        </div>
        </div>


                    <!-- II. FAMILY BACKGROUND -->
                    <div class="card form-section">
                        <div class="card-header text-white" style="background-color: #006666; font-family: 'Century Gothic';">
                            <h3 class="card-title">II. FAMILY BACKGROUND</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <td style="text-align: center; width:17%;" class="style2">
                                        SPOUSE'S SURNAME<br />
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtSLname" type="text" id="txtSLname" value="<?php echo (is_array($row1) && isset($row1['SLname'])) ? htmlspecialchars($row1['SLname']) : 'N/A'; ?>" />
                                    </td>
                                    <td class="style2" style="text-align: center;">
                                        NAME OF CHILDREN <br/><span class="style4">(Write full name and list all)</span>
                                    </td>
                                    <td class="style2" style="text-align: center;">
                                        DATE OF BIRTH<br /><span class="style4">(mm/dd/yyyy)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        FIRST NAME
                                    </td>
                                    <td style="width:20%;">
                                        <input class="form-control" name="txtSFname" type="text" id="txtSFname" value="<?php echo (is_array($row1) && isset($row1['SFname'])) ? htmlspecialchars($row1['SFname']) : 'N/A'; ?>" />
                                    </td>
                                    <td class="style2" style="text-align: center;">
                                        NAME EXTENSION<br />(JR, SR)
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtExt1" type="text" id="txtExt1" value="<?php echo (is_array($row1) && isset($row1['SExtension'])) ? htmlspecialchars($row1['SExtension']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild1" type="text" id="txtChild1" value="<?php if($a > 0) echo $cname[1]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth1" type="date" id="txtChildBirth1" value="<?php if($a > 0) echo $cbirth[1]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        MIDDLE NAME
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtSMname" type="text" id="txtSMname" value="<?php echo (is_array($row1) && isset($row1['SMname'])) ? htmlspecialchars($row1['SMname']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild2" type="text" id="txtChild2" value="<?php if($a > 1) echo $cname[2]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth2" type="date" id="txtChildBirth2" value="<?php if($a > 1) echo $cbirth[2]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        OCCUPATION
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtSoccu" type="text" id="txtSoccu" value="<?php echo (is_array($row1) && isset($row1['SOccupation'])) ? htmlspecialchars($row1['SOccupation']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild3" type="text" id="txtChild3" value="<?php if($a > 2) echo $cname[3]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth3" type="date" id="txtChildBirth3" value="<?php if($a > 2) echo $cbirth[3]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        EMPLOYER/BUSSINESS NAME
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtEmpBusName" type="text" id="txtEmpBusName" value="<?php echo (is_array($row1) && isset($row1['EmpBusName'])) ? htmlspecialchars($row1['EmpBusName']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild4" type="text" id="txtChild4" value="<?php if($a > 3) echo $cname[4]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth4" type="date" id="txtChildBirth4" value="<?php if($a > 3) echo $cbirth[4]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        BUSSINESS ADDRESS
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtBussAdd" type="text" id="txtBussAdd" value="<?php echo (is_array($row1) && isset($row1['BussAdd'])) ? htmlspecialchars($row1['BussAdd']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild5" type="text" id="txtChild5" value="<?php if($a > 4) echo $cname[5]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth5" type="date" id="txtChildBirth5" value="<?php if($a > 4) echo $cbirth[5]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        TELEPHONE NO.
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtTel1" type="text" id="txtTel1" value="<?php echo (is_array($row1) && isset($row1['TelNo'])) ? htmlspecialchars($row1['TelNo']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild6" type="text" id="txtChild6" value="<?php if($a > 5) echo $cname[6]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth6" type="date" id="txtChildBirth6" value="<?php if($a > 5) echo $cbirth[6]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        FATHER'S SURNAME<br />
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtFLname" type="text" id="txtFLname" value="<?php echo (is_array($row1) && isset($row1['FLname'])) ? htmlspecialchars($row1['FLname']) : 'N/A'; ?>" required />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild7" type="text" id="txtChild7" value="<?php if($a > 6) echo $cname[7]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth7" type="date" id="txtChildBirth7" value="<?php if($a > 6) echo $cbirth[7]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        FIRST NAME
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtFFname" type="text" id="txtFFname" value="<?php echo (is_array($row1) && isset($row1['FFname'])) ? htmlspecialchars($row1['FFname']) : 'N/A'; ?>" required />
                                    </td>
                                    <td class="style2" style="text-align: center;">
                                        NAME EXTENSION<br />(JR, SR)
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtExt2" type="text" id="txtExt2" value="<?php echo (is_array($row1) && isset($row1['FExtension'])) ? htmlspecialchars($row1['FExtension']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild8" type="text" id="txtChild8" value="<?php if($a > 7) echo $cname[8]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth8" type="date" id="txtChildBirth8" value="<?php if($a > 7) echo $cbirth[8]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        MIDDLE NAME
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtFMname" type="text" id="txtFMname" value="<?php echo (is_array($row1) && isset($row1['FMname'])) ? htmlspecialchars($row1['FMname']) : 'N/A'; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChild9" type="text" id="txtChild9" value="<?php if($a > 8) echo $cname[9]; ?>" />
                                    </td>
                                    <td>
                                        <input class="form-control" name="txtChildBirth9" type="date" id="txtChildBirth9" value="<?php if($a > 8) echo $cbirth[9]; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        MOTHER'S MAIDEN NAME<br />
                                    </td>
                                    <td colspan="3">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        SURNAME
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtMLname" type="text" id="txtMLname" value="<?php echo (is_array($row1) && isset($row1['MLname'])) ? htmlspecialchars($row1['MLname']) : 'N/A'; ?>" required />
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        FIRST NAME
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtMFname" type="text" id="txtMFname" value="<?php echo (is_array($row1) && isset($row1['MFname'])) ? htmlspecialchars($row1['MFname']) : 'N/A'; ?>" required />
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        MIDDLE NAME
                                    </td>
                                    <td colspan="3">
                                        <input class="form-control" name="txtMMname" type="text" id="txtMMname" value="<?php echo (is_array($row1) && isset($row1['MMname'])) ? htmlspecialchars($row1['MMname']) : 'N/A'; ?>" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- III. EDUCATIONAL BACKGROUND -->
                    <div class="card form-section">
                        <div class="card-header text-white" style="background-color: #006666; font-family: 'Century Gothic';">
                            <h3 class="card-title">III. EDUCATIONAL BACKGROUND</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <td rowspan="2" class="style2" style="text-align: center; width:17%;">
                                        LEVEL
                                    </td>
                                    <td rowspan="2" style="text-align: center;">
                                        <span class="style2">NAME OF SCHOOL</span>
                                    </td>
                                    <td rowspan="2" style="text-align: center;">
                                        <span class="style2">BASIC EDUCATION/ DEGREE/COURSE</span><br />
                                        <span class="style4">(Write in full)</span>
                                    </td>
                                    <td colspan="2" class="style2" style="text-align: center;">
                                        PERIOD OF ATTENDANCE
                                    </td>
                                    <td rowspan="2" style="text-align: center;">
                                        <span class="style2">HIGHEST LEVEL/UNITS EARNED</span><br class="style3" />
                                        <span class="style4">(if not graduated)</span>
                                    </td>
                                    <td rowspan="2" class="style4" style="text-align: center; width:10%;">
                                        YEAR<br />
                                        GRADUATED
                                    </td>
                                    <td rowspan="2" class="style4" style="text-align: center;">
                                        SCHOLARSHIP/<br />
                                        ACADEMIC<br />
                                        HONORS<br />
                                        RECEIVED
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style4" style="text-align: center; width:7%;">
                                        From
                                    </td>
                                    <td class="style4" style="text-align: center; width:7%;">
                                        <span>To</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        ELEMENTARY
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtElem" type="text" id="txtElem" value="<?php echo (is_array($Elem) && isset($Elem['SchoolName'])) ? htmlspecialchars($Elem['SchoolName']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtElemEduc" type="text" id="txtElemEduc" value="<?php echo (is_array($Elem) && isset($Elem['Course'])) ? htmlspecialchars($Elem['Course']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtElemFrom" type="text" id="txtFrom1" value="<?php echo (is_array($Elem) && isset($Elem['PeriodFrom'])) ? htmlspecialchars($Elem['PeriodFrom']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtElemTo" type="text" id="txtTo1" value="<?php echo (is_array($Elem) && isset($Elem['PeriodTo'])) ? htmlspecialchars($Elem['PeriodTo']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtElemUnits" type="text" id="txtUnits1" value="<?php echo (is_array($Elem) && isset($Elem['Units'])) ? htmlspecialchars($Elem['Units']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtElemYearGrad" type="text" id="txtYearGrad1" value="<?php echo (is_array($Elem) && isset($Elem['YearGrad'])) ? htmlspecialchars($Elem['YearGrad']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtElemHonor" type="text" id="txtHonor1" value="<?php echo (is_array($Elem) && isset($Elem['Honors'])) ? htmlspecialchars($Elem['Honors']) : ''; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        SECONDARY
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtSecondary" type="text" id="txtSecondary" value="<?php echo (is_array($Sec) && isset($Sec['SchoolName'])) ? htmlspecialchars($Sec['SchoolName']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtSecondEduc" type="text" id="txtSecondEduc" value="<?php echo (is_array($Sec) && isset($Sec['Course'])) ? htmlspecialchars($Sec['Course']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtSecondFrom" type="text" id="txtFrom2" value="<?php echo (is_array($Sec) && isset($Sec['PeriodFrom'])) ? htmlspecialchars($Sec['PeriodFrom']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtSecondTo" type="text" id="txtTo2" value="<?php echo (is_array($Sec) && isset($Sec['PeriodTo'])) ? htmlspecialchars($Sec['PeriodTo']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtSecondUnits" type="text" id="txtUnits2" value="<?php echo (is_array($Sec) && isset($Sec['Units'])) ? htmlspecialchars($Sec['Units']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtSecondYearGrad" type="text" id="txtYearGrad2" value="<?php echo (is_array($Sec) && isset($Sec['YearGrad'])) ? htmlspecialchars($Sec['YearGrad']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtSecondHonor" type="text" id="txtHonor2" value="<?php echo (is_array($Sec) && isset($Sec['Honors'])) ? htmlspecialchars($Sec['Honors']) : ''; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        VOCATIONAL/TRADE COURSE
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtVocational" type="text" id="txtVocational" value="<?php echo (is_array($Voc) && isset($Voc['SchoolName'])) ? htmlspecialchars($Voc['SchoolName']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtVocDeg" type="text" id="txtVocDeg" value="<?php echo (is_array($Voc) && isset($Voc['Course'])) ? htmlspecialchars($Voc['Course']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtVocFrom" type="text" id="txtFrom3" value="<?php echo (is_array($Voc) && isset($Voc['PeriodFrom'])) ? htmlspecialchars($Voc['PeriodFrom']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtVocTo" type="text" id="txtTo3" value="<?php echo (is_array($Voc) && isset($Voc['PeriodTo'])) ? htmlspecialchars($Voc['PeriodTo']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtVocUnits" type="text" id="txtUnits3" value="<?php echo (is_array($Voc) && isset($Voc['Units'])) ? htmlspecialchars($Voc['Units']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtVocYearGrad" type="text" id="txtYearGrad3" value="<?php echo (is_array($Voc) && isset($Voc['YearGrad'])) ? htmlspecialchars($Voc['YearGrad']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtVocHonor" type="text" id="txtHonor3" value="<?php echo (is_array($Voc) && isset($Voc['Honors'])) ? htmlspecialchars($Voc['Honors']) : ''; ?>" />
                                    </td>
                                </tr>                                
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        COLLEGE
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtCollege" type="text" id="txtCollege" value="<?php echo (is_array($Col) && isset($Col['SchoolName'])) ? htmlspecialchars($Col['SchoolName']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtCollegeDeg" type="text" id="txtCollegeDeg" value="<?php echo (is_array($Col) && isset($Col['Course'])) ? htmlspecialchars($Col['Course']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtCollegeFrom" type="text" id="txtFrom4" value="<?php echo (is_array($Col) && isset($Col['PeriodFrom'])) ? htmlspecialchars($Col['PeriodFrom']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtCollegeTo" type="text" id="txtTo4" value="<?php echo (is_array($Col) && isset($Col['PeriodTo'])) ? htmlspecialchars($Col['PeriodTo']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtCollegeUnits" type="text" id="txtUnits4" value="<?php echo (is_array($Col) && isset($Col['Units'])) ? htmlspecialchars($Col['Units']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtCollegeYearGrad" type="text" id="txtYearGrad4" value="<?php echo (is_array($Col) && isset($Col['YearGrad'])) ? htmlspecialchars($Col['YearGrad']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtCollegeHonor" type="text" id="txtHonor4" value="<?php echo (is_array($Col) && isset($Col['Honors'])) ? htmlspecialchars($Col['Honors']) : ''; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="style2" style="text-align: center;">
                                        GRADUATE STUDIES
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtGradStudies" type="text" id="txtGradStudies" value="<?php echo (is_array($Grad) && isset($Grad['SchoolName'])) ? htmlspecialchars($Grad['SchoolName']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtGradDeg" type="text" id="txtGradDeg" value="<?php echo (is_array($Grad) && isset($Grad['Course'])) ? htmlspecialchars($Grad['Course']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtGradFrom" type="text" id="txtFrom5" value="<?php echo (is_array($Grad) && isset($Grad['PeriodFrom'])) ? htmlspecialchars($Grad['PeriodFrom']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtGradTo" type="text" id="txtTo5" value="<?php echo (is_array($Grad) && isset($Grad['PeriodTo'])) ? htmlspecialchars($Grad['PeriodTo']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtGradUnits" type="text" id="txtUnits5" value="<?php echo (is_array($Grad) && isset($Grad['Units'])) ? htmlspecialchars($Grad['Units']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtGradYearGrad" type="text" id="txtYearGrad5" value="<?php echo (is_array($Grad) && isset($Grad['YearGrad'])) ? htmlspecialchars($Grad['YearGrad']) : ''; ?>" />
                                    </td>
                                    <td style="text-align: center">
                                        <input class="form-control" name="txtGradHonor" type="text" id="txtHonor5" value="<?php echo (is_array($Grad) && isset($Grad['Honors'])) ? htmlspecialchars($Grad['Honors']) : ''; ?>" />
                                    </td>                                                                        
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: center; vertical-align: middle;">
                                        (Continue on separate sheet if necessary)
                                        <div class="mt-2">
                                            <button type="button"
                                                    class="btn btn-success btn-sm"
                                                    id="Continue"
                                                    name="Continue"
                                                    data-toggle="modal"
                                                    data-target="#addEducation"
                                                    style="font-family: 'Century Gothic'; font-weight: 500;">
                                            Continue
                                            </button>
                                        </div>
                                        </td>

                                        <script>
                                        // Reset modal fields every time it's opened
                                        $('#addEducation').on('show.bs.modal', function () {
                                            $(this).find('form')[0].reset();
                                        });
                                        </script>
                                </tr>

                            </table>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success btn-lg" id="Save" name="Save" style="font-family: 'Century Gothic'; font-weight: 700; width: 300px; height: 50px;">
                                SAVE
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<!-- end of body -->

<?php
include_once('../partials/footer.php');
//include("../partials/modals/modalpassword.php");
include("../partials/modals/modal_add_education.php");
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>  
$(document).ready(function () {
  $("#savePage1").submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: "../includes/functions/saveinfo.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "text", 
      success: function (response) {
        try {
          let res = JSON.parse(response); 

          Swal.fire({
            title: res.title,
            text: res.message,
            icon: res.icon,
            confirmButtonText: "OK"
          }).then(() => {
            if (res.success) {
              location.reload();
            }
          });
        } catch (e) {
          console.error("Invalid JSON:", response);
          Swal.fire("Error", "Invalid server response", "error");
        }
      },
      error: function (xhr) {
        Swal.fire("Error", "Something went wrong. Try again.", "error");
      }
    });
  });
});




</script>

            