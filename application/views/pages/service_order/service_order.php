<div class="row">
    <div class="col-lg-8 col-centered">
        <h1 class="cirms-page-header">
            <i class="fa fa-list-alt fa-fw"></i>
            <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($controller)) ?>
        </h1>
    </div>
</div>
<span class="hidden" id="user_name"><?php echo $sess_user; ?></span>
<div class="col-lg-8 col-centered bordered">
    <form class="horizontal-form" role="form" method="POST">
        <table class="cirms-table">
            <tr>
                <td><legend class="padding10 cirms-title">Part I - Originator Details</legend></td>
            </tr>
            <tr>
                <td>
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
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">College / Department</span>
                        <div class="col-lg-12">
                            <select class="form-control" name="cluster_id" id="cluster_id"></select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Position:</span>
                        <div class="col-lg-12">
                            <input type="text" class="form-control" name="position" id="position" placeholder="Input Employee Position">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Contact No.:</span>
                        <div class="col-lg-12">
                            <input type="number" min="0" class="form-control no-arrow" name="contact_no" id="contact_no" placeholder="Input Contact No.">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><legend class="padding10 cirms-title">Part II - Complaint Details</legend></td>
            </tr>
            <tr>
                <td>
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
                </td>
            </tr>
            <tr>
                <td>
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
                </td>
            </tr>
            <!-- <tr class="complaint">
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Complaint <i><small>(Resource)</small></i>:</span>
                        <div class="col-lg-12">
                            <select class="form-control" name="complaint_resource_id" id="new_complaint">
                            </select>
                        </div>

                    </div>
                </td>
            </tr> -->
            <tr class="complaint">
                <td>
                    <div class="form-group">
                        <span class="col-lg-12 cirms-label">Complaint:</span>
                        <div class="col-lg-12">
                            <div class="input-group">
                                <select class="form-control" name="complaint_resource_id[]" id="new_complaint" >
                                    <option value="">Not yet selected</option>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary add-button cirms-btn" type="button" title="Add field">
                                        +
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Complaint Details:</span>
                        <div class="col-lg-12">
                            <textarea class="form-control" rows="3" id="complaint_details" name="complaint_details" placeholder="Input Complaint Details"></textarea>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Received By:</span>
                        <div class="col-lg-12">
                            <input type="hidden" name="user_logged_in" value="<?php echo $sess_id ?>">
                            <select class="form-control" id="received_by" name="received_by"></select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-12 cirms-label">Assigned To:</span>
                        <div class="col-lg-12">
                            <select class="form-control" id="user_id" name="assigned_to"></select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <span class="col-sm-8 cirms-label">Date Reported:</span>
                        <span class="col-sm-4 cirms-label visible-lg">Time Reported:</span>
                        <div class="col-lg-8">
                            <div class="inner-addon right-addon">
                                <i class="fa fa-calendar"></i>
                                <input type='text'
                                       class="datepicker-here form-control"
                                       id="date_reported"
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
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <label class="checkbox-inline">
                                <input type="hidden" name="is_urgent" value="0">
                                <input type="checkbox" name="is_urgent" id="is_urgent" value="1">
                                <b>Mark as Urgent</b>
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary pull-right">Submit</button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
