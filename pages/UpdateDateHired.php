<?php
session_start();
if (!isset($_SESSION['Status'])) {
    header("location: ../index.php");
    exit;
}
$access_level = $_SESSION['Status'];

require_once('../includes/initialize.php');
require_once('../includes/db_config.php');

include_once(PARTIALS_PATH . 'header.php');
date_default_timezone_set('Asia/Manila');
?>

<!-- navbar -->
<?php include("../includes/navbar.php"); ?>

<div class="content-wrapper">

    <!-- PAGE HEADER -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>Update Employee Date Hired</h1>
            <p class="text-muted">Double-click the Date Hired cell to edit.</p>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header bg-primary text-white"></div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="EmpList" class="table table-striped table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>EmpNo</th>
                                    <th>Name</th>
                                    <th>Date Hired</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // List all employees, no where clause
                                $stmt = $pdo->query("
                                    SELECT EmpNo,
                                           CONCAT(Lname, ', ', Fname, ' ', Mname) AS Name,
                                           date_hired
                                    FROM i
                                    ORDER BY Lname ASC
                                ");

                                if ($stmt->rowCount() === 0) {
                                    echo "<tr><td colspan='3' class='text-center text-muted'>No employees found.</td></tr>";
                                } else {
                                    while ($row = $stmt->fetch()) {
                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['EmpNo']) ?></td>
                                            <td><?= htmlspecialchars($row['Name']) ?></td>
                                            <td class="editable text-primary font-weight-bold"
                                                data-id="<?= $row['EmpNo'] ?>"
                                                style="cursor:pointer;">
                                                <?= ($row['date_hired'] === "0000-00-00" || empty($row['date_hired'])) ? "—" : $row['date_hired'] ?>
                                            </td>
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

        </div>
    </section>

</div>

<?php include_once('../partials/footer.php'); ?>

<!-- Plugins -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="../../plugins/toastr/toastr.min.js"></script>
<link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with pagination and search
    $('#EmpList').DataTable({
        "order": [[1, "asc"]],
        "pageLength": 25
    });
});

// ==============================
//  DOUBLE-CLICK EDIT HANDLING
// ==============================
// ==============================
//  DOUBLE-CLICK EDIT HANDLING
// ==============================
$(document).on("dblclick", ".editable", function () {

    let cell = $(this);
    if (cell.data("editing")) return;

    let empId = cell.data("id");
    let currentValue = cell.text().trim() === "—" ? "" : cell.text().trim();

    cell.data("editing", true);

    // Save original content in case of cancel
    let originalContent = cell.html();

    // Clear cell and add wrapper
    cell.html('<div style="display:inline-block; max-width:120px;"><input type="text" class="form-control form-control-sm"></div>');
    let input = cell.find("input");

    // Initialize flatpickr
    flatpickr(input[0], {
        dateFormat: "Y-m-d",
        maxDate: "today",
        defaultDate: currentValue || null,
        allowInput: true, // allow manual typing
        onClose: function(selectedDates, dateStr) {

            if (!dateStr) {
                cell.html(currentValue || "—");
                cell.data("editing", false);
                return;
            }

            // AJAX update
            $.ajax({
                url: "", // same page
                method: "POST",
                data: {
                    ajax_update: true,
                    EmpNo: empId,
                    date_hired: dateStr
                },
                success: function(res) {
                    try {
                        let r = JSON.parse(res);
                        if (r.status === "success") {
                            toastr.success("Date Hired updated!");
                            cell.html(dateStr);
                        } else {
                            toastr.error(r.message || "Update failed.");
                            cell.html(currentValue || "—");
                        }
                    } catch(e) {
                        toastr.error("Server response error.");
                        cell.html(currentValue || "—");
                    }
                },
                error: function() {
                    toastr.error("Server error.");
                    cell.html(currentValue || "—");
                },
                complete: function() {
                    cell.data("editing", false);
                }
            });
        }
    });

    input.focus();
});

</script>

<?php
// ==================================
// AJAX UPDATE HANDLER (same page)
// ==================================
if (isset($_POST['ajax_update'])) {

    $empNo = $_POST['EmpNo'];
    $date_hired = $_POST['date_hired'];

    try {

        $update = $pdo->prepare("
            UPDATE `i`
            SET `date_hired` = ?
            WHERE `EmpNo` = ?
        ");
        $update->execute([$date_hired, $empNo]);

        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }

    exit;
}
?>
