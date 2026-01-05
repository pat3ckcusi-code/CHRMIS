<?php
// manage_announcements.php
?>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title"><i class="fas fa-bullhorn"></i> Manage Announcements</h3>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#announcementModal">
      <i class="fas fa-plus"></i> Add Announcement
    </button>
  </div>

  <div class="card-body table-responsive p-0">
    <table class="table table-hover text-nowrap">
      <thead>
        <tr>
          <th>Title</th>
          <th>Message</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Status</th>
          <th style="width: 100px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <!-- Static demo rows (replace with DB query later) -->
        <tr>
          <td>HR Advisory</td>
          <td>Update your 201 files before Oct 15.</td>
          <td>2025-10-01</td>
          <td>2025-10-15</td>
          <td><span class="badge badge-success">Active</span></td>
          <td>
            <button class="btn btn-sm btn-info edit-btn" data-id="1"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="1"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <tr>
          <td>Christmas Party</td>
          <td>Annual celebration on Dec 18 🎉</td>
          <td>2025-12-01</td>
          <td>2025-12-18</td>
          <td><span class="badge badge-warning">Upcoming</span></td>
          <td>
            <button class="btn btn-sm btn-info edit-btn" data-id="2"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="2"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Add/Edit Announcement -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="announcementForm">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-bullhorn"></i> New Announcement</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="announcementId">
          <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter title..." required>
          </div>
          <div class="form-group">
            <label>Message</label>
            <textarea name="message" class="form-control" rows="3" placeholder="Enter message..." required></textarea>
          </div>
          <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
          </div>
          <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="Active">Active</option>
              <option value="Upcoming">Upcoming</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  // Add / Update announcement
  $("#announcementForm").submit(function(e){
    e.preventDefault();
    Swal.fire("Saved!", "Announcement has been saved (static demo).", "success");
    $("#announcementModal").modal("hide");
  });

  // Edit button
  $(".edit-btn").click(function(){
    const id = $(this).data("id");
    // Load existing details into modal (for demo only)
    $("#announcementId").val(id);
    $("#announcementModal .modal-title").text("Edit Announcement");
    $("#announcementModal").modal("show");
  });

  // Delete button
  $(".delete-btn").click(function(){
    const id = $(this).data("id");
    Swal.fire({
      title: "Are you sure?",
      text: "This announcement will be deleted.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if(result.isConfirmed){
        Swal.fire("Deleted!", "Announcement has been deleted (static demo).", "success");
      }
    });
  });
});
</script>
