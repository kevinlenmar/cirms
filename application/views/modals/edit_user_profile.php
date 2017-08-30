<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="pull-right btn btn-default btn-danger btn-circle" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>&nbsp;User Profile</h4>
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="modal-body form-horizontal">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <div class="avatar-wrapper text-center">
                                <input id="avatar" name="avatar" type="file" class="file-loading" />
                            </div>
                            <div class="avatar-errors center-block"></div>
                        </div>
                        <div class="col-xs-12 col-md-8">
                            <div>
                                <span style="font-size: 20px"><span id="firstname"></span> <span id="lastname"></span></span>
                                <span class="pull-right cirms-link">
                                    <a href="settings" title="Edit your information">
                                        <i class="fa fa-pencil fg-gray"></i> <small>Edit Info</small>
                                    </a>
                                </span>
                                <h4 class="divider-modal" style="margin-top: -10px;"></h4>
                            </div>
                            <div>
                                <input type="hidden" name="id" id="id">
                                Employee ID: </i><span id="emp_id"></span>
                            </div>
                            <div>
                                Department: <span id="cluster_code"></span>
                            </div>
                            <div>
                                User Type: <span id="user_type" class="text-bold"></span>
                            </div>
                            <div>
                                Contact No: <span id="contact_no"></span>
                            </div>
                            <div>
                                Date Added: <span id="date_added"></span>
                            </div>
                            <div>
                                Last Login: <span id="last_login"></span>
                            </div>
                        </div>
                    </div>
                    <span id="error"></span>
                    <button type="submit" class="btn btn-primary pull-right footer-cirms-btn">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
