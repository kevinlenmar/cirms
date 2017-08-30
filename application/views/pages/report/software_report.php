<div class="row">
    <div class="col-lg-12">
        <h1 class="cirms-page-header">
            <i class="fa fa-bar-chart-o fa-fw"></i>
            <?php echo preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($controller)) ?>
        </h1>
    </div>
</div>
<div class="toolbar padding-bottom25">
    <div class="visible-lg">
        <div class="date-filter-label">&nbsp;</div>
        <div class="date-filter-label"><b><small>From:&nbsp;</small></b></div>
        <div class="inner-addon-list right-addon-list date-filter">
            <i class="fa fa-calendar"></i>
            <input type='text'
                   class="datepicker-here form-control"
                   id="date_from"
                   name="date_from"
                   readonly="readonly" />
        </div>
        <div class="date-filter-label" ><b><small>To:&nbsp;</small></b></div>
        <div class="inner-addon-list right-addon-list date-filter">
            <i class="fa fa-calendar"></i>
            <input type='text'
                   class="datepicker-here form-control"
                   id="date_to"
                   name="date_to"
                   data-dateFomat="mm/dd/yy"
                   readonly="readonly" />
        </div>
        <div class="date-filter-label">
            <button type="button" class="btn bg-darkCyan fg-white" data-role="date_filter">Go</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="software-table">
            <div class="panel-heading">
                <i class="fa fa-list-alt"></i> <b>Software Reports</b>
                <span class="pull-right visible-lg">
                    <button type="button" class="btn btn-xs btn-default fullscreen-sr" title="Toogle Fullscreen">
                        <span class="glyphicon glyphicon-resize-full" aria-hidden="true">
                    </button>
                </span>
            </div>
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-hover table-bordered width100 text-break display responsive nowrap" id="software-report">
                        <thead>
                            <tr>
                                <th data-priority="0">Resource ID</th>
                                <th data-priority="1">Resource Name</th>
                                <th data-priority="2">E-Room</th>
                                <th data-priority="3">Laboratory</th>
                                <th data-priority="4">Department</th>
                                <th data-priority="5">Office</th>
                                <th data-priority="6">Total</th>
                                <th data-priority="7"><i class="fa fa-cog fa-fw"></i></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
