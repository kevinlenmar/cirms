<div class="sidebar-wrapper section-not-to-print" id="custom-sb-scollbar" style="height: 100%; padding-bottom: 150px">
    <ul id="sidebar_menu" class="sidebar-nav" style="width:500px;">
        <li class="sidebar-brand" id="sidebar-user">
            <a href="javascript:void(0)" class="media-avatar" id="profile" data-url="edit_user_profile" data-id="<?php echo $sess_id ?>">
                <span class="user-info">
                <b><?php echo $sess_user ?></b>
                    <img class="main-icon small-avatar" id="profile-avatar" src="<?php echo $sess_avatar; ?>" alt="me" />
                </span>
            </a>
        </li>
    </ul>
    <ul class="sidebar-nav content-sidebar" id="sidebar">
        <li>
            <a class="sidebar-item" href="dashboard" title="CIRMS Dashboard">
                Dashboard
                <span class="fa fa-dashboard fg-gold sub-icon"></span>
            </a>
        </li>
        <?php if($sess_access_rights !== 'view'): ?>
        <li>
            <a class="sidebar-item" href="new/service-order" title="Service Order">
                New Service Order
                <span class="fa fa-list-alt fg-gold sub-icon"></span>
            </a>
        </li>
        <?php endif; ?>
        <?php if($sess_access_rights !== 'add' && $sess_access_rights !== 'view'): ?>
        <li>
            <a class="sidebar-item" href="tasks" title="Tasks">
                Tasks
                <span class="fa fa-tasks fg-gold sub-icon"></span>
            </a>
        </li>
        <?php endif; ?>
        <!--<?php if( $sess_user_type === 'property custodian' || $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ): ?>
            <li>
                <a class="sidebar-item" href="ROF-pending" title="ROF Pending">
                    ROF Pending
                    <span class="fa fa-question-circle fa-lg fg-gold sub-icon"></span>
                </a>
            </li>
        <?php endif; ?>-->

        <?php if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ): ?>
        <li id="users">
            <a class="sidebar-item pop"
                 href="javascript:void(0)"
                 title="<b>User</b>"
                 rel="popover"
                 data-popover-content="#user-pop">Users
                    <span class="fa fa-users fg-gold sub-icon"></span>
            </a>
            <div id="user-pop" class="hidden">
                <ul>
                    <li>
                        <a href='new/user' title="New User">
                            <i class='fa fa-plus-square'></i>
                            New
                        </a>
                    </li>
                    <li style='margin-bottom: 5px;'>
                        <a href='manage/users' title="Manage Users">
                            <i class='fa fa-external-link'></i>
                            Manage
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li id="computers">
            <a class="sidebar-item"
                 href="javascript:void(0)"
                 title="<b>Computer</b>"
                 rel="popover"
                 data-popover-content="#computer-pop">Computers
                    <span class="fa fa-desktop fg-gold sub-icon"></span>
            </a>
            <div id="computer-pop" class="hidden">
                <ul>
                    <li>
                        <a href='new/computer' title="New Computer">
                            <i class='fa fa-plus-square'></i>
                            New
                        </a>
                    </li>
                    <li>
                        <a href='manage/computers' title="Manage Computers">
                            <i class='fa fa-external-link'></i>
                            Manage
                        </a>
                    </li>
                    <li class="disabled-title">INITIALIZATION</li>
                    <li class="popover-submenu">
                        <a href="javascript:void(0)" class="link-disabled"><i class="fa fa-desktop fa-fw"></i>&nbsp;&nbsp;Resources</a>
                        <ul class="dropdown-menu scrollable-menu" id="custom-ssb-scollbar" style="margin-top: -32px;">
                            <li class="disabled-title-child">RESOURCE</li>
                            <li>
                                <a href="new/resource"><i class="fa fa-plus-square"></i> New</a>
                            </li>
                            <li style='margin-bottom: 5px;'>
                               <a href="manage/resources"><i class="fa fa-external-link"></i> Manage</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </li>
        <?php endif; ?>
    </ul>
    <ul class="sidebar-nav hidden-lg ribbon-sidebar" id="sidebar">
        <li id="classrooms">
            <a class="sidebar-item"
                href="javascript:void(0)"
                title="<b>Classroom</b>"
                rel="popover"
                data-popover-content="#classroom-pop">Classrooms
                <span class="sub-icon fa fa-university fg-gold"></span>
            </a>
            <div id="classroom-pop" class="hidden">
                <ul>
                    <?php if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ): ?>
                    <li>
                        <a href="new/classroom"><i class="fa fa-plus-square fa-fw"></i> New</b></a>
                    </li>
                    <li>
                        <a href="manage/classrooms"><i class="fa fa-external-link fa-fw"></i> Manage</b></a>
                    </li>
                    <?php endif; ?>
                    <li class="disabled-title">WORKSTATIONS</li>
                    <li>
                        <a href="e-room"><i class="fa fa-book fa-fw"></i> E-Room</a>
                    </li>
                    <li class="popover-submenu">
                        <a href="javascript:void(0)" class="link-disabled"><i class="fa fa-flask fa-fw"></i> Laboratories</a>
                        <ul class="dropdown-menu scrollable-menu classroom-wrapper-sidebar" style="min-width: 100px; height: 120px;" id="custom-ssb-scollbar">
                            <?php
                                if($this->classroom->get_classroom_details_by_type('laboratory')) :
                                    foreach ($this->classroom->get_classroom_details_by_type('laboratory') as $row) :
                            ?>
                                    <li><a href="laboratory/<?php echo strtolower($row->room_no); ?>">
                                        <i class="fa fa-laptop fa-fw"></i>
                                        <?php echo $row->room_no; ?>
                                    </a></li>
                            <?php
                                    endforeach;
                                else :
                            ?>
                                <li class="disabled-li"><i class="fa fa-ban"></i>&nbsp;Empty</li>

                            <?php
                                endif
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </li>
        <li id="clusters">
           <a class="sidebar-item"
                href="javascript:void(0)"
                title="<b>Cluster</b>"
                rel="popover"
                data-popover-content="#cluster-pop">Clusters
                <span class="fa fa-sitemap fg-gold sub-icon"></span>
            </a>
            <div id="cluster-pop" class="hidden">
                <ul>
                    <?php if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ): ?>
                    <li>
                        <a href="new/cluster"><i class="fa fa-plus-square fa-fw"></i> New</a>
                    </li>
                    <li>
                        <a href="manage/clusters"><i class="fa fa-external-link fa-fw"></i> Manage</a>
                    </li>
                    <?php endif; ?>
                    <li class="disabled-title">WORKSTATIONS</li>
                    <li class="popover-submenu">
                        <a href="javascript:void(0)" class="link-disabled"><i class="fa fa-trello fa-fw"></i> Departments</a>
                        <ul class="dropdown-menu scrollable-menu cluster-wrapper-department" style="min-width: 100px; height: 120px;" id="custom-ssb-scollbar">
                            <?php
                                if($this->cluster->get_cluster_details_by_type('department')) :
                                    foreach ($this->cluster->get_cluster_details_by_type('department') as $row) :
                            ?>
                                    <li><a href="department/<?php echo strtolower($row->cluster_code); ?>">
                                        <i class="fa fa-laptop fa-fw"></i>
                                        <?php echo $row->cluster_code; ?>
                                    </a></li>
                            <?php
                                    endforeach;
                                else :
                            ?>
                                <li class="disabled-li"><i class="fa fa-ban"></i>&nbsp;EMPTY</li>
                            <?php
                                endif
                            ?>
                        </ul>
                    </li>
                    <li class="popover-submenu">
                        <a href="javascript:void(0)" class="link-disabled"><i class="fa fa-suitcase fa-fw"></i> Offices</a>
                        <ul class="dropdown-menu scrollable-menu cluster-wrapper-office" style="min-width: 100px; height: 120px;" id="custom-ssb-scollbar">
                            <?php
                                if($this->cluster->get_cluster_details_by_type('office')) :
                                    foreach ($this->cluster->get_cluster_details_by_type('office') as $row) :
                            ?>
                                    <li><a href="office/<?php echo strtolower($row->cluster_code); ?>">
                                        <i class="fa fa-laptop fa-fw"></i>
                                        <?php echo $row->cluster_code; ?>
                                    </a></li>
                            <?php
                                    endforeach;
                                else :
                            ?>
                                <li class="disabled-li"><i class="fa fa-ban"></i>&nbsp;Empty</li>
                            <?php
                                endif
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <?php if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control'): ?>
    <ul class="sidebar-nav content-sidebar" id="sidebar">
        <li id="reports">
            <a class="sidebar-item pop"
                 href="javascript:void(0)"
                 title="<b>Report</b>"
                 rel="popover"
                 data-popover-content="#report-pop">
                    Reports
                    <span class="fa fa-bar-chart-o fg-gold sub-icon"></span>
            </a>
            <div id="report-pop" class="hidden">
                <ul style="min-width: auto">
                    <li>
                        <a href="service-order-report" title="Service Order Report">
                            <i class='fa fa-list-alt'></i>
                            Service Order
                        </a>
                    </li>
                    <li>
                        <a href="hardware-reports" title="Hardware Report">
                            <span class="circle-o bg-black fg-white"><div class="init-complaint">H</div></span>
                            Hardware
                        </a>
                    </li>
                    <li>
                        <a href="software-reports" title="Software Report">
                            <span class="circle-o bg-black fg-white"><div class="init-complaint">S</div></span>
                            Software
                        </a>
                    </li>
                    <li>
                        <a href="classroom-report" title="Classroom Report">
                            <i class='fa fa-university'></i>
                            Classroom
                        </a>
                    </li>
                    <li>
                        <a href="cluster-report" title="Cluster Report">
                            <i class='fa fa-sitemap'></i>
                            Cluster
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    <?php endif; ?>
    <ul class="sidebar-nav toogle-sidebar" id="sidebar" style="padding-bottom: 80px;">
        <li style="width: 300px;">
            <a class="sidebar-item not-active" id="menu-toggle" href="javascript:void(0)" title="Toogle Sidebar">
                <span class="fa fa-angle-double-left fg-lightMaroon bottom-icon" style="font-size:21px"></span>
            </a>
        </li>
    </ul>
</div>
