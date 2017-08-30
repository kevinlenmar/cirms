<div class="row">
    <div class="col-lg-12">
        <h1 class="cirms-page-header">
            <i class="fa fa-bar-chart-o fa-fw"></i>
            <?php echo preg_replace('/[^a-zA-Z0-9]/', ' ', ucwords($controller)) ?>
        </h1>
    </div>
</div>
<div class="toolbar">
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
    <div class="right">
        <span><b><small>Designation Type: </small></b></span>
        <span class="btn-group" role="group">
            <button type="button" class="btn btn-default active" data-role="type">All</button>
            <button type="button" class="btn btn-default" data-role="type">Lecture</button>
            <button type="button" class="btn btn-default" data-role="type">Laboratory</button>
            <button type="button" class="btn btn-default" data-role="type">Department</button>
            <button type="button" class="btn btn-default" data-role="type">Office</button>
        </span>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="service-order-table">
            <div class="panel-heading">
                <i class="fa fa-list-alt"></i> <b>Service Order Report</b>
                <span class="pull-right visible-lg">
                    <button type="button" class="btn btn-xs btn-default fullscreen-sot" title="Toogle Fullscreen">
                        <span class="glyphicon glyphicon-resize-full" aria-hidden="true">
                    </button>
                </span>
            </div>
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-hover table-bordered width100 text-break display responsive nowrap" id="service-order-report">
                        <thead>
                            <tr>
                                <th data-priority="1">Ref No</th>
                                <th data-priority="3">Name</th>
                                <th data-priority="4">Originator Cluster</th>
                                <th data-priority="2">Computer Name</th>
                                <th data-priority="5">Computer Brand/Clone Name</th>
                                <th data-priority="6">Complaint Type</th>
                                <th data-priority="7">Complaint Details</th>
                                <th data-priority="8">Date / Time Reported</th>
                                <th data-priority="11">Received by Tsg Personnel</th>
                                <th data-priority="10">Assigned to Tsg Staff </th>
                                <th data-priority="12">Date / Time Finished</th>
                                <th data-priority="9">Action Taken</th>
                                <th data-priority="13">Returned To</th>
                                <th data-priority="14">Property Clerk</th>
                                <th data-priority="15">Date Received by Property</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
