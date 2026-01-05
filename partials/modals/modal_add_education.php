<!-- Educational Background Modal -->
<form id="addEducationForm" action="../api/add_education.php" method="post">
  <input type="hidden" name="userID" id="userID" value="<?php echo $currentuserID; ?>" />

  <div class="modal fade" id="addEducation" tabindex="-1" role="dialog" aria-labelledby="addEducationLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title" id="addEducationLabel">Educational Background</h4>
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

          <div class="form-group">
            <label for="level">Level of Education*</label>
            <select class="form-control" id="level" name="level" required>
              <option value="">-- Select level --</option>
              <option>Elementary</option>
              <option>Secondary</option>
              <option>Vocational/Trade Course</option>
              <option>College</option>
              <option>Graduate Studies</option>
            </select>
          </div>

          <div class="form-group">
            <label for="schoolName">Name of School*</label>
            <input type="text" class="form-control" id="schoolName" name="schoolName" placeholder="Ex: City College of Calapan" required>
          </div>

          <div class="form-group">
            <label for="degree">Degree / Course*</label>
            <input type="text" class="form-control" id="degree" name="degree" placeholder="Ex: BS in Information Systems" required>
          </div>

          <div class="form-group row">
            <div class="col-md-6">
              <label for="periodFrom">Period (From)</label>
              <input type="month" class="form-control" id="periodFrom" name="periodFrom">
            </div>
            <div class="col-md-6">
              <label for="periodTo">Period (To)</label>
              <input type="month" class="form-control" id="periodTo" name="periodTo">
            </div>
          </div>

          <div class="form-group">
            <label for="units">Highest Level / Units Earned</label>
            <input type="text" class="form-control" id="units" name="units" placeholder="Ex: 3rd Year, 72 Units">
          </div>

          <div class="form-group">
            <label for="yearGraduated">Year Graduated</label>
            <input type="number" class="form-control" id="yearGraduated" name="yearGraduated" placeholder="Ex: 2022" min="1900" max="<?php echo date('Y')+1; ?>">
          </div>

          <div class="form-group">
            <label for="honors">Scholarship / Honors Received</label>
            <textarea class="form-control" id="honors" name="honors" rows="2" placeholder="Ex: With Honors, Academic Scholar"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</form>
