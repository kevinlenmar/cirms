<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="pull-right btn btn-default btn-danger btn-circle" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>&nbsp;Edit Classroom</h4>
    </div>
    <form method="post">
        <div class="modal-body form-horizontal">
            <input type="hidden" class="form-control" name="resource_id" id="resource_id">
            <div class="form-group">
				<span class="col-sm-12 cirms-label">Resource Name:</span>
				<div class="col-lg-12">
					<input type="text" class="form-control" name="resource_name" id="resource_name" placeholder="Input Resource Name">
				</div>
		    </div>
            <div class="form-group">
				<span class="col-sm-12 cirms-label">Resource Type:</span>
				<div class="col-lg-12">
                    <select class="form-control" name="resource_type" id="resource_type">
                        <option value="">Select Resource Type</option>
                        <option value="hardware">Hardware</option>
                        <option value="software">Software</option>
                    </select>
				</div>
			</div>
        </div>
        <div class="modal-footer modal-custom-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
