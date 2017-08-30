<div id="all-graphs">
    <div class="row" style="position: relative; top: 15px; z-index: 1">
        <div class="col-lg-3">&nbsp;</div>
        <div class="col-lg-3"></div>
        <div class="col-lg-3">
            <div class="not-printable pull-right">
                <small><b>Select Year:&nbsp;</b></small>
                <select id="classroom_report_year" style="border-radius: 2px;">
                    <?php for($x=date('Y'); $x>=1946; $x--) { ?>
                        <option value='<?php echo $x ?>'>
                                <?php echo $x ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="not-printable pull-right">
                <small><b>Select Year:&nbsp;</b></small>
                <select id="cluster_report_year" style="border-radius: 2px;">
                    <?php for($x=date('Y'); $x>=1946; $x--) { ?>
                        <option value='<?php echo $x ?>'>
                                <?php echo $x ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row" style="position: relative; top: 28px; z-index: 1;">
        <div class="col-lg-3 col-md-3 col-xs-3">
            <span style="margin-left: 10px">
                <i class="fa fa-bar-chart-o"></i> <b>Reports</b>
            </span>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3">
            <span style="margin-left: 10px">
                <i class="fa fa-desktop"></i> <b>Complaint</b>
            </span>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3">
            <span style="margin-left: 10px">
                <i class="fa fa-university"></i> <b>E-Room & Lab</b>
            </span>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3">
            <span style="margin-left: 10px">
                <i class="fa fa-sitemap"></i> <b>Dept & Offices</b>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xs-3" style="padding-left: 5px; padding-right: 5px">
            <div class="panel panel-default width100 print-this" id="report-graph">

                <div class="panel-body fullscreen-rs">
                    <div id="report-area-chart" style="height: 200px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3" style="padding-left: 5px; padding-right: 5px">
            <div class="panel panel-default width100" id="complaint-graph">

                <div class="panel-body fullscreen-hsc">
                    <div id="hardware-software-line-chart" style="height: 200px;"></div>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3" style="padding-left: 5px; padding-right: 5px">
            <div class="panel panel-default width100" id="classroom-graph">

                <div class="panel-body fullscreen-cl">
                    <div id="classroom-bar-chart" style="height: 200px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-3" style="padding-left: 5px; padding-right: 5px">
            <div class="panel panel-default width100" id="cluster-graph">

                <div class="panel-body fullscreen-clr">
                    <div id="cluster-bar-chart" style="height: 200px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <h1 class="cirms-page-header">
            <i class="fa fa-download fa-fw"></i>
            <?php echo  "Pending Services" ?>
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
