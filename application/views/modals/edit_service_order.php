<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="pull-right btn btn-default btn-danger btn-circle" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>&nbsp;Edit Service Order</h4>
    </div>
    <form method="post">
        <div class="modal-body form-horizontal padding20">
            <div class="subtext" >
                Part I - Originator Details
            </div>
            <div class="form-group">
                <span class="col-lg-4 cirms-label">Employee ID:</span>
                <span class="col-lg-8 cirms-label visible-lg">Employee Name:</span>
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="Input Employee ID">
                </div>
                <div class="col-lg-12 cirms-label-hidden-lg hidden-lg">Employee Name:</div>
                <div class="col-lg-8">
                    <input type="text" class="form-control" name="emp_name" id="emp_name" placeholder="Input Employee Name">
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Cluster:</span>
                <div class="col-lg-12">
                    <select class="form-control" name="cluster_id" id="cluster_id"></select>
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Position:</span>
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="position" id="position" placeholder="Input Employee Position">
                </div>
            </div>
           <div class="form-group">
                <span class="col-sm-12 cirms-label">Contact No.:</span>
                <div class="col-lg-12">
                    <input type="number" min="0" class="form-control no-arrow" name="contact_no" id="contact_no" placeholder="Input Contact No.">
                </div>
            </div>
            <div class="subtext" >
                Part II - Complaint Details
            </div>
            <div class="form-group">
                <span class="col-lg-8 cirms-label">Computer Name:</span>
                <span class="col-lg-4 cirms-label visible-lg">Item Pulled Out?</span>
                <div class="col-lg-8">
                    <select class="form-control" name="computer_name" id="computer_name"></select>
                </div>
                <div class="col-lg-12 cirms-label-hidden-lg hidden-lg">Item Pulled Out?</div>
                <div class="col-lg-4">
                    <span>
                        <label class="radio-inline">
                            <input type="radio" value="1" name="if_pulled_out" checked> Yes
                        </label>
                    </span>
                    <span>
                        <label class="radio-inline">
                            <input type="radio" value="0" name="if_pulled_out"> No
                        </label>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Complaint Type:</span>
                <div class="col-lg-12">
                    <select class="form-control" name="complaint_type" id="complaint_type">
                            <option value="">Select Complaint Type</option>
                            <option value="hardware">Hardware</option>
                            <option value="software">Software</option>
                    </select>
                </div>

            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Complaint <i><small>(Resource)</small></i>:</span>
                <div class="col-lg-12">
                    <select class="form-control" name="complaint_resource_id" id="edit_complaint"></select>
                </div>

            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Complaint Details:</span>
                <div class="col-lg-12">
                    <textarea class="form-control" rows="3" name="complaint_details" placeholder="Input Complaint Details"></textarea>
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-8 cirms-label">Date Reported:</span>
                <span class="col-sm-4 cirms-label visible-lg">Time Reported:</span>
                <div class="col-lg-8">
                    <div class="inner-addon right-addon">
                        <i class="fa fa-calendar"></i>
                        <input type='text'
                               class="datepicker-here form-control"
                               id="date"
                               name="date_reported"
                               readonly="readonly" />
                    </div>
                </div>
                <div class="col-lg-12 cirms-label-hidden-lg hidden-lg">Time Reported:</div>
                <div class="col-lg-4">
                    <div class="bootstrap-timepicker">
                        <div class="inner-addon right-addon">
                            <i class="fa fa-clock-o"></i>
                            <input id="time_reported"
                                   type="text"
                                   name="time_reported"
                                   class="form-control"
                                   />
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <span class="col-sm-12 cirms-label">Received By:</span>
                <div class="col-lg-12">
                    <select class="form-control" name="received_by" id="received_ae" ></select>
                </div>
            </div>
            <div class="form-group">
                <span class="col-lg-12 cirms-label">Assigned to:</span>
                <div class="col-lg-12">
                    <select class="form-control" name="assigned_to" id="user_id"></select>
                </div>
            </div>
            <input type="hidden" name="ref_no" id="ref_no"/>
        </div>
        <div class="modal-footer modal-custom-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
