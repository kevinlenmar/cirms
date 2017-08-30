<div class="row">
    <div class="col-lg-8 col-centered">
        <h1 class="cirms-page-header">
                <i class="fa fa-desktop fa-fw"></i>
                <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($controller)) ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-centered bordered">
        <form class="horizontal-form" method="POST" id="add_new_user" role="form">
            <table class="cirms-table">
                <tr>
                    <td>
                <legend class="padding10 cirms-title">Computer Details</legend>
            </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <div class="col-sm-12 cirms-label">Computer Type: </div>
                        <div class="col-lg-12">
                            <select class="form-control" name="computer_type" id="computer_type">
                                <option value="">Select Computer Type</option>
                                <option value="branded">Branded</option>
                                <option value="cloned">Cloned</option>
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <div class="col-sm-12 cirms-label">Brand/Clone Name:</div>
                        <div class="col-lg-12">
                            <input type="text" class="form-control" name="brand_clone_name" id="brand_clone_name" placeholder="Input Brand/Clone Name">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <legend class="padding10">Designation Details</legend>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <div class="col-sm-5 cirms-label">Designation Type: </div>
                        <div class="col-sm-4 cirms-label visible-lg">Designation:</div>
                        <div class="col-sm-3 cirms-label visible-lg">Workstation No.:</div>
                        <div class="col-lg-5">
                            <select class="form-control" name="designation_type" id="designation_type">
                                <option value="">Select Designation Type</option>
                                <option value="e-room">E - Room</option>
                                <option value="laboratory">Laboratory</option>
                                <option value="department">Department</option>
                                <option value="office">Office</option>
                            </select>
                        </div>
                        <div class="col-lg-12 cirms-label-hidden-lg hidden-lg">Designation:</div>
                        <div class="col-lg-4">
                            <select class="form-control" name="designation" id="designation"></select>
                        </div>
                        <div class="col-lg-12 cirms-label-hidden-lg hidden-lg">Workstation No.:</div>
                        <div class="col-lg-3">
                            <span class="input-group-addon group-custom bg-darkCyan fg-white">WS</span>
                            <input type="number" min="0" class="form-control input-group-custom" name="workstation_no" id="workstation_no" placeholder="e.g. 23">
                        </div>
                    </div>
                        </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-12 cirms-label">Assigned to <i>(<small>Person Responsible</small>)</i>:</div>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="assigned_to" id="assigned_to" placeholder="Input Assigned To">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-12 cirms-label">Date Assigned:</div>
                        <div class="col-lg-12">
                            <div class="inner-addon right-addon">
                                <i class="fa fa-calendar"></i>
                                <input type='text'
                                       class="datepicker-here form-control"
                                       id="date_assigned"
                                       name="date_assigned"
                                       readonly="readonly" />
                            </div>
                        </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="cirms-label" id="set_hide">
                            <small><a href="javascript:void(0)" class="fg-red" id="set">Set manual computer name?</a></small>
                        </div>
                        <div class="form-group hidden" id="set_computer_name">
                            <div class="col-sm-12 cirms-label">Computer Name:</div>
                            <div class="col-sm-12">
                                <div class="inner-addon right-addon">
                                    <i class="fa fa-warning"></i>
                                    <input type="text" class="form-control" name="computer_name" id="computer_name" placeholder="Computer Name">
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary pull-right">Submit</button
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

