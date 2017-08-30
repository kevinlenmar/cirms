<div class="row">
    <div class="col-lg-12">
        <h1 class="cirms-page-header">
            <i class="fa fa-desktop fa-fw"></i>
            <?php echo  preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($method)) ?>
        </h1>
    </div>
</div>
<div class="toogle">
        <span><b><small>Resource Type: </small></b></span>
        <span class="btn-group" role="group">
            <button type="button" class="btn btn-default active" data-role="type">All</button>
            <button type="button" class="btn btn-default" data-role="type">Software</button>
            <button type="button" class="btn btn-default" data-role="type">Hardware</button>
        </span>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-hover table-bordered width100" id="computer-resource-list">
                        <thead>
                            <tr>
                                <th class="text-center" data-priority="1">ID</th>
                                <th data-priority="3">Resource Name</th>
                                <th>Resource Type</th>
                                <th class="text-center" data-priority="2"><i class="fa fa-wrench fa-fw"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
