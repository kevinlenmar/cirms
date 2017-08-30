<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="pull-right btn btn-default btn-danger btn-circle" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>&nbsp;Edit User</h4>
    </div>
    <form method="post">
        <div class="modal-body form-horizontal">
            <div class="form-group">
                <div class="col-sm-12 cirms-label">Employee ID:<i class="fa fa-lock fg-gray pull-right padding-right5"></i></div>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="emp_id" id="emp_id" readonly="readonly" placeholder="e.g. 143">
                </div>

            </div>
            <div class="form-group">
                <div class="col-sm-6 cirms-label">First Name:</div>
                <div class="col-sm-6 cirms-label visible-lg">Last Name:</div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Input First Name">
                </div>
                <div class="col-lg-12 cirms-label-hidden-lg hidden-lg">Last Name:</div>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Input Last Name">
                </div>
            </div>
            <div class="form-group"  id="user_type_div">
                <div class="col-sm-12 cirms-label">User Type:</div>
                <div class="col-lg-12">
                    <select class="form-control" name="user_type" id="user_type">
                        <option value="">Select User Type</option>
                        <option value="administrator">Administrator</option>
                        <option value="encoder">Encoder</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Department:</span>
                <div class="col-lg-12">
                    <select name="cluster_id" id="cluster_id" class="form-control"></select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 cirms-label">Contact No.:</div>
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="Input Contact No.">
                </div>
            </div>
            <input type="hidden" name="id" id="id" />
        </div>
        <div class="modal-footer modal-custom-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
