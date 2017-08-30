<div class="hide-this">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="cirms-page-header">
                <i class="fa fa-tasks fa-fw"></i>
                <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($method)) ?>
            </h1>
        </div>
    </div>
    <span class="hidden" id="user_name"><?php echo $sess_user; ?></span>
    <div class="toolbar">
        <div class="right">
            <span><b><small>Status: </small></b></span>
            <span class="btn-group" role="group">
                <button type="button" class="btn btn-default active" data-role="status">Urgent</button>
                <button type="button" class="btn btn-default" data-role="status">Open</button>
                <button type="button" class="btn btn-default" data-role="status">Pending</button>
                <button type="button" class="btn btn-default" data-role="status">Replaced</button>
                <button type="button" class="btn btn-default" data-role="status">Close</button>
                <button type="button" class="btn btn-default" data-role="status">Void</button>
                <button type="button" class="btn btn-default" data-role="status">All</button>
            </span>
        </div>
    </div>
    <div class="hidden" id="user_type"><?php echo $sess_user_type; ?></div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-hover table-bordered width100 text-break" id="user-tasks">
                            <thead>
                                <tr>
                                    <th class="text-center" data-priority="1">Ref No</th>
                                    <th data-priority="3">Computer Name</th>
                                    <th>Complaint Type</th>
                                    <th>Complaint</th>
                                    <th>Complaint Details</th>
                                    <th>Received By</th>
                                    <th>Assigned To</th>
                                    <th>Date/Time Reported</th>
                                    <th class="text-center" data-priority="2"><i class="fa fa-wrench fa-fw"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Print Form -->
    <div class="width100 section-to-print hidden" id="service-order-form" style="font-size: 16px">
        <br>

        <table border="0">
            <tr>
                <td colspan="3">Service Order Form</td>
            </tr>
            <tr>
                <td colspan="3">Technical Support Group</td>
            </tr>
            <tr>
                <td colspan="3">Cebu Institute of Technology - University</td>
                <td class="text-right">Reference Number:&nbsp;<span class="underline-bottom padding-underline text-bold" id="ref_no"></span></td>
            </tr>
            <tr>
                <td class="underline-top-bottom" colspan="4">Part I - ORIGINATOR DETAILS</td>
            </tr>
            <tr>
                <td colspan="2"><span>Name:</span>&nbsp;<span class="text-bold" id="emp_name"></span></td>
                <td class="text-right"><span>Recommending Approval:</span>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><span>Employee ID:</span>&nbsp;<span class="text-bold" id="emp_id"></span></td>
            </tr>
            <tr>
                <td><span>Department/Office:</span>&nbsp;<span class="text-bold" id="cluster_name"></span></td>
            </tr>
            <tr>
                <td colspan="2"><span>Position:</span>&nbsp;<span class="text-bold" id="position"></span></td>
                <td class="text-right"><span class="underline-top">Department Head / Head</span>&nbsp;</td>
                <td class="text-center"><span class="underline-top padding-underline">Date</span></td>
            </tr>
            <tr>
                <td><span>Contact Number:</span>&nbsp;<span class="text-bold" id="contact_no"></span></td>
            </tr>
            <tr><td class="underline-bottom" colspan="4"></td></tr>

            <tr>
                <td colspan="3"><span class="text-bold">Complaint / Initial Problem Analysis:</span></td>
                <td style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;Date Reported:</span>&nbsp;<span class="text-bold" id="date_reported"></span></td>
            </tr>
            <tr style="height: 70px;">
                <td colspan="3" valign="top"><span id="complaint"></span>:&nbsp;<span id="complaint_details"></span></td>
                <td style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;Time Reported:</span>&nbsp;<span class="text-bold" id="time_reported"></span></td>
            </tr>
            <tr>
                <td class="underline-top-bottom" colspan="4">Part II - TSG USE ONLY</td>
            </tr>
            <tr>
                <td colspan="3"><span>Received by:</span>&nbsp;<span class="text-bold" id="view_received_by"></span></td>
                <td class="text-left"><span>Reviewed by:</span>&nbsp;<span class="underline-bottom" style="width: 252px; display: inline-block"></span></td>
            </tr>
            <tr>
                <td colspan="3"><span>Date / Time Received:</span>&nbsp;<span class="text-bold" id="date_reported"></span>/<span class="text-bold" id="time_reported"></span></td>
                <td class="text-left"><span>Date/Time Reviewed:</span>&nbsp;<span class="underline-bottom" style="width: 202px; display: inline-block"></span></td>
            </tr>
            <tr>
                <td colspan="3"><span>Assigned to:</span>&nbsp;<span class="text-bold" id="view_assigned_to"></span></td>
                <td class="text-left"><span>Computer Name:</span>&nbsp;<span class="underline-bottom" id="computer_name" style="width: 180px; display: inline-block"></span></td>
            </tr>
            <tr>
                <td class="underline-top" colspan="4"></td>
            </tr>
            <tr>
                <td colspan="3"><span class="text-bold">Action Taken / Current Status:</span></td>
                <td style="border-left: 1px solid #000; height: 30px">
                    &nbsp;&nbsp;<span style="width: 150px; display: inline-block">Item Pulled Out?</span>&nbsp;
                    <span>
                        <label class="radio-inline">
                            <input type="radio" value="1" id="if_pulled_out_yes" name="if_pulled_out"> Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="0" id="if_pulled_out_no" name="if_pulled_out"> No
                        </label>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3" valign="top"><span id="action_taken"></span></td>
                <td style="border-left: 1px solid #000;">
                    &nbsp;&nbsp;<span style="width: 153px; display: inline-block">Item Under Warranty?</span>
                    <span>
                        <label class="radio-inline">
                            <input type="radio" value="1" id="if_under_warranty_yes" name="if_under_warranty"> Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="0" id="if_under_warranty_no" name="if_under_warranty"> No
                        </label>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="underline-top" ></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;User Sign Off:</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center" style="border-left: 1px solid #000;"><span class="underline-top">&nbsp;&nbsp;Signature over Printed Name</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
                <td style="border-left: 1px solid #000;">&nbsp;&nbsp;Received by:</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center" style="border-left: 1px solid #000; "><span class="underline-top">&nbsp;&nbsp;Property Clerk / Date</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center" style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;Signature over Printed Name</td>
            </tr>
            <tr>
                <td class="underline-top" colspan="4"></td>
            </tr>
        </table>
        <br>
         <table border="0">
            <tr>
                <td colspan="3">Service Order Form</td>
            </tr>
            <tr>
                <td colspan="3">Technical Support Group</td>
            </tr>
            <tr>
                <td colspan="3">Cebu Institute of Technology - University</td>
                <td class="text-right">Reference Number:&nbsp;<span class="underline-bottom padding-underline text-bold" id="ref_no"></span></td>
            </tr>
            <tr>
                <td class="underline-top-bottom" colspan="4">Part I - ORIGINATOR DETAILS</td>
            </tr>
            <tr>
                <td colspan="2"><span>Name:</span>&nbsp;<span class="text-bold" id="emp_name"></span></td>
                <td class="text-right"><span>Recommending Approval:</span>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><span>Employee ID:</span>&nbsp;<span class="text-bold" id="emp_id"></span></td>
            </tr>
            <tr>
                <td><span>Department/Office:</span>&nbsp;<span class="text-bold" id="cluster_name"></span></td>
            </tr>
            <tr>
                <td colspan="2"><span>Position:</span>&nbsp;<span class="text-bold" id="position"></span></td>
                <td class="text-right"><span class="underline-top">Department Head / Head</span>&nbsp;</td>
                <td class="text-center"><span class="underline-top padding-underline">Date</span></td>
            </tr>
            <tr>
                <td><span>Contact Number:</span>&nbsp;<span class="text-bold" id="contact_no"></span></td>
            </tr>
            <tr><td class="underline-bottom" colspan="4"></td></tr>

            <tr>
                <td colspan="3"><span class="text-bold">Complaint / Initial Problem Analysis:</span></td>
                <td style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;Date Reported:</span>&nbsp;<span class="text-bold" id="date_reported"></span></td>
            </tr>
            <tr style="height: 70px;">
                <td colspan="3" valign="top"><span id="complaint"></span>:&nbsp;<span id="complaint_details"></span></td>
                <td style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;Time Reported:</span>&nbsp;<span class="text-bold" id="time_reported"></span></td>
            </tr>
            <tr>
                <td class="underline-top-bottom" colspan="4">Part II - TSG USE ONLY</td>
            </tr>
            <tr>
                <td colspan="3"><span>Received by:</span>&nbsp;<span class="text-bold" id="view_received_by"></span></td>
                <td class="text-left"><span>Reviewed by:</span>&nbsp;<span class="underline-bottom" style="width: 252px; display: inline-block"></span></td>
            </tr>
            <tr>
                <td colspan="3"><span>Date / Time Received:</span>&nbsp;<span class="text-bold" id="date_reported"></span>/<span class="text-bold" id="time_reported"></span></td>
                <td class="text-left"><span>Date/Time Reviewed:</span>&nbsp;<span class="underline-bottom" style="width: 202px; display: inline-block"></span></td>
            </tr>
            <tr>
                <td colspan="3"><span>Assigned to:</span>&nbsp;<span class="text-bold" id="view_assigned_to"></span></td>
                <td class="text-left"><span>Computer Name:</span>&nbsp;<span class="underline-bottom" id="computer_name" style="width: 180px; display: inline-block"></span></td>
            </tr>
            <tr>
                <td class="underline-top" colspan="4"></td>
            </tr>
            <tr>
                <td colspan="3"><span class="text-bold">Action Taken / Current Status:</span></td>
                <td style="border-left: 1px solid #000; height: 30px">
                    &nbsp;&nbsp;<span style="width: 150px; display: inline-block">Item Pulled Out?</span>&nbsp;
                    <span>
                        <label class="radio-inline">
                            <input type="radio" value="1" id="if_pulled_out_yes" name="if_pulled_out_again"> Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="0" id="if_pulled_out_no" name="if_pulled_out_again"> No
                        </label>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3" valign="top"><span id="action_taken"></span></td>
                <td style="border-left: 1px solid #000;">
                    &nbsp;&nbsp;<span style="width: 153px; display: inline-block">Item Under Warranty?</span>
                    <span>
                        <label class="radio-inline">
                            <input type="radio" value="1" id="if_under_warranty_yes" name="if_under_warranty_again"> Yes
                        </label>
                        <label class="radio-inline">
                            <input type="radio" value="0" id="if_under_warranty_no" name="if_under_warranty_again"> No
                        </label>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="underline-top" ></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;User Sign Off:</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center" style="border-left: 1px solid #000;"><span class="underline-top">&nbsp;&nbsp;Signature over Printed Name</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
                <td style="border-left: 1px solid #000;">&nbsp;&nbsp;Received by:</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center" style="border-left: 1px solid #000; "><span class="underline-top">&nbsp;&nbsp;Property Clerk / Date</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="text-center" style="border-left: 1px solid #000;"><span>&nbsp;&nbsp;Signature over Printed Name</td>
            </tr>
            <tr>
                <td class="underline-top" colspan="4"></td>
            </tr>
        </table>
    
</div>