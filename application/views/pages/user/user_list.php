<div class="row">
    <div class="col-lg-12">
        <h1 class="cirms-page-header">
            <i class="fa fa-users fa-fw"></i>
            <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($method)) ?>
        </h1>
    </div>
</div>
<span class="hidden" id="sess_user_type"><?php echo $sess_user_type; ?></span>
<span class="hidden" id="sess_access_rights"><?php echo $sess_access_rights; ?></span>
<div class="toogle">
        <span><b><small>User Type: </small></b></span>
        <span class="btn-group" role="group">
            <button type="button" class="btn btn-default active" data-role="user_type">All</button>
            <button type="button" class="btn btn-default" data-role="user_type">Superadmin</button>
            <button type="button" class="btn btn-default" data-role="user_type">Administrator</button>
            <button type="button" class="btn btn-default" data-role="user_type">Property Custodian</button>
            <button type="button" class="btn btn-default" data-role="user_type">Encoder</button>
            <button type="button" class="btn btn-default" data-role="user_type">Viewer</button>
        </span>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-hover table-bordered width100" id="user-list">
                        <thead>
                            <tr>
                                <th class="text-center" data-priority="1">ID</th>
                                <!-- <th class="text-center hidden-xs"><img src="assets/images/avatars/default_profile_v2.ico" class="user-list-img" /></th> -->
                                <th class="text-center hidden-xs"><i class="fa fa-photo"></i></th>
                                <th data-priority="3">Employee ID</th>
                                <th>Full Name</th>
                                <th>Department/Office</th>
                                <th>Contact No.</th>
                                <th>Status</th>
                                <th class="text-center">User Type</th>
                                <th class="text-center" data-priority="2"><i class="fa fa-wrench fa-fw"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
