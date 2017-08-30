<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="pull-right btn btn-default btn-danger btn-circle" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>&nbsp;Edit Cluster</h4>
    </div>
    <form method="post">
        <div class="modal-body form-horizontal">
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Room No.:<i class="fa fa-lock fg-gray pull-right padding-right5"></i></span>
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="room_no" id="room_no" placeholder="e.g. ST106" />
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Cluster Code:</span>
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="cluster_code" id="cluster_code" placeholder="Input Cluster code">
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Cluster Name:</span>
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="cluster_name" id="cluster_name" placeholder="Input Cluster Name">
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Cluster Type:</span>
                <div class="col-lg-12">
                    <select class="form-control" name="type" id="type">
                        <option value="">Select Cluster Type</option>
                        <option value="department">Department</option>
                        <option value="office">Office</option>
                    </select>
                </div>
            </div>
            <input type="hidden" name="room_id" id="room_id" />
        </div>
        <div class="modal-footer modal-custom-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>