<div class="modal fade" id="modalLate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Late Deduction</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="lateForm" action="../includes/functions/Late.php" method="post">
                <div class="modal-body text-center">
                    <input type="hidden" name="EmpNo" id="empNoField">
                    <label>Enter Number of Minutes Late</label>
                    <input class="form-control" name="numMin" id="numMin" placeholder="Number of Minutes" required>
                </div>
                <div class="modal-footer text-center">
                    <button type="submit" name="btnDeduct" class="btn btn-success" style="width:200px;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
