<div class="row">
    <div class="col-lg-8 col-centered">
        <h1 class="cirms-page-header">
            <i class="fa fa-cog fa-fw"></i>
                <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($method)) ?>
        </h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-centered">
        <ul class="nav nav-tabs pull-right">
            <li class="active" id="user-info-li"><a href="#user-info-tab" data-toggle="tab"> <i class="fa fa-user"></i> <span class="hidden-xs">User Info</span></a></li>
            <li id="password-li"><a href="#password-tab" data-toggle="tab"><i class="fa fa-lock"></i>  <span class="hidden-xs">Password</span></a></li>
            <?php if( $sess_access_rights === 'ultimate_control' || $sess_access_rights === 'full_control' ): ?>
                <li id="logs-li"><a href="#activity-logs-tab" data-toggle="tab"><i class="fa fa-history"></i>  <span class="hidden-xs">Activity Logs</span></a></li>
            <?php endif; ?>
            <?php if($sess_access_rights === 'ultimate_control'): ?>
                <!-- <li id="access-li"><a href="#access-rights-tab" data-toggle="tab"><i class="fa fa-cog fa-spin"></i> Access Rights</a></li> -->
                <li id="access-li"><a href="#access-rights-tab" data-toggle="tab"><i class="fa fa-key"></i> <span class="hidden-xs">Access Rights</span></a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="tab-content">
<!-- User tab -->
    <div class="tab-pane active" id="user-info-tab">
        <div class="row">
            <div class="col-lg-8 col-centered bordered">
                <form class="horizontal-form" method="POST" role="form">
                    <table class="cirms-table">
                        <input type="hidden" name="id" value="<?php echo $sess_id ?>">
                        <tr>
                            <td><legend class="padding10 cirms-title">User Information</legend></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-12 cirms-label">First Name:</div>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $sess_firstname ?>" placeholder="First Name">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-12 cirms-label">Last Name:</div>
                                    <div class="col-lg-12">
                                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $sess_lastname ?>" placeholder="Last Name">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-12 cirms-label">Contact No.:</div>
                                    <div class="col-lg-12">
                                        <input type="number" min="0" class="form-control no-arrow" name="contact_no" id="contact_no" value="<?php echo $sess_contact_no ?>" placeholder="Contact No.">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
<!-- Password tab -->
    <div class="tab-pane" id="password-tab">
        <div class="row">
            <div class="col-lg-8 col-centered bordered">
                <form class="horizontal-form" method="POST" role="form">
                    <table class="cirms-table">
                        <input type="hidden" name="id" value="<?php echo $sess_id ?>">
                        <tr>
                            <td><legend class="padding10 cirms-title">Password Settings</legend></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-12 cirms-label">Current Password:</div>
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Current Password">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-12 cirms-label">New Password:</div>
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-12 cirms-label">Confirm Password:</div>
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
<?php if( $sess_access_rights === 'ultimate_control' || $sess_access_rights === 'full_control' ): ?>
    <!-- Activity Logs tab -->
    <div class="tab-pane" id="activity-logs-tab">
        <div class="row">
            <div class="col-lg-8 col-centered bordered">
                <form class="horizontal-form" method="POST" role="form">
                    <table class="cirms-table">
                        <tr>
                            <td><legend class="padding10 cirms-title">Activity Logs</legend></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="dataTable_wrapper">
                                                    <table class="table table-hover table-bordered width100" id="activity_logs">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" data-priority="1">Ref No</th>
                                                                <th data-priority="3">Computer Name</th>
                                                                <th>Activities</th>
                                                                <th>Date / Time</th>
                                                                <!-- <th class="text-center" data-priority="2"><i class="fa fa-wrench fa-fw"></i></th> -->
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span><button type="button" class="btn btn-danger pull-right" id="clear">Clear</button></span>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>   
<?php if($sess_access_rights === 'ultimate_control'): ?>
    <!-- Access Rights tab -->
    <div class="tab-pane" id="access-rights-tab">
        <div class="row">
            <div class="col-lg-8 col-centered bordered">
                <form class="horizontal-form" method="POST" role="form">
                    <table class="cirms-table">
                        <tr>
                            <td><legend class="padding10 cirms-title">Access Rights Settings</legend></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                <div class="col-sm-12 cirms-label">Select User:</div>
                                    <div class="col-lg-12">
                                        <select class="form-control" name="user_id" id="user_id"></select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-sm-12 cirms-label">Privileges:</div>
                                    <div class="col-lg-12">
                                        <select class="form-control" name="access_rights" id="access_rights"></select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

</div>
