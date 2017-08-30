<div class="modal-content">
    <div class="modal-header">
        <!-- <button type="button" class="pull-right btn btn-default btn-danger btn-circle" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> -->
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-hand-o-right"></i>&nbsp;Designate</h4>
    </div>
    <form method="post">
        <div class="modal-body form-horizontal">
            <input type="hidden" name="ref_no" id="ref_no" />
            <input type="hidden" name="computer_name" id="computer_name" />
            <input type="hidden" name="designate" id="designate" />
            <div class="form-group"  id="user_type_div">
                <div class="col-sm-12 cirms-label">Designate to:</div>
                <div class="col-lg-12">
                    <select class="form-control" name="designate_to" id="user_id"></select>
                </div>
            </div>
        </div>
        <div class="modal-footer modal-custom-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
