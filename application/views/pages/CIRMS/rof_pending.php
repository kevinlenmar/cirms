<div class="hide-this">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="cirms-page-header">
                <i class="fa fa-question-circle fa-fw"></i>
                <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($method)) ?>
            </h1>
        </div>
    </div>
    <span class="hidden" id="sess_user_type"><?php echo $sess_user_type; ?></span>
    <span class="hidden" id="sess_user_name"><?php echo $sess_user; ?></span>
    <div class="toolbar">
        <div class="right">
            <span><b><small>Status: </small></b></span>
            <span class="btn-group" role="group">
                <button type="button" class="btn btn-default active" data-role="status">Pending</button>
                <button type="button" class="btn btn-default" data-role="status">Ordering</button>
                <button type="button" class="btn btn-default" data-role="status">Replaced</button>
                <button type="button" class="btn btn-default" data-role="status">All</button>
            </span>
        </div>
    </div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-hover table-bordered width100 text-break" id="rof_pending">
                        <thead>
                            <tr>
                                <th class="text-center" data-priority="1">Ref No</th>
                                <th data-priority="3">Computer Name</th>
                                <th>Complaint Type</th>
                                <th>Complaint</th>
                                <th>Complaint Details</th>
                                <th>Date/Time Reported</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
 