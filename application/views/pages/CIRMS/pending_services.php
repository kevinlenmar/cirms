<div class="row">
    <div class="col-lg-12">
        <h1 class="cirms-page-header">
            <i class="fa fa-download fa-fw"></i>
            <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($method)) ?>
        </h1>
    </div>
</div>
<div class="hidden" id="user_type"><?php echo $sess_user_type; ?></div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-hover table-bordered width100 text-break" id="pending_service">
                        <thead>
                            <tr>
                                <th class="text-center" data-priority="1">Ref No</th>
                                <th data-priority="3">Computer Name</th>
                                <th>Complaint Type</th>
                                <th>Complaint</th>
                                <th>Complaint Details</th>
                                <th>Unit Status</th>
                                <th>Date/Time Reported</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>