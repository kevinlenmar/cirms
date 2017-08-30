<div class="row section-not-to-print">
    <div class="col-lg-12">
        <div class="row ribbon" style="z-index: 10000">
            <span class="breadcrumbs">
                <i class="fa fa-home fa-fw"></i>
                <?php
                    if($controller != 'cirms')
                        echo ' / ' . preg_replace('/[^a-zA-Z0-9]/', ' ', $controller);
                    if($this->uri->segment(1) == $controller)
                        echo ' / ' . preg_replace('/[^a-zA-Z0-9]/', ' ', $this->uri->segment(2));
                    else
                        echo ' / ' . preg_replace('/[^a-zA-Z0-9]/', ' ', $this->uri->segment(1));
                ?>
            </span>
            <nav class="navbar navbar-default navbar-ribbon pull-right">
                <ul class="nav navbar-ribbon-links visible-lg">
                    <li class="dropdown ribbon-li-rooms">
                        <a href="javascript:void(0)" class="dropdown-toggle ribbon-item" data-toggle="dropdown">
                            &nbsp;Classrooms&nbsp;<span class="fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu pull-right"  style="min-width: auto;">
                            <?php if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ): ?>
                            <li>
                                <a href="new/classroom" title="New Classroom"><i class="fa fa-plus-square fa-fw"></i> New</b></a>
                            </li>
                            <?php
                                endif;
                                if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ):
                            ?>
                            <li>
                                <a href="manage/classrooms" title="Mange Classrooms"><i class="fa fa-external-link fa-fw"></i> Manage</b></a>
                            </li>
                            <li class="disabled-title">WORKSTATIONS</li>
                            <?php else : ?>
                            <li class="disabled-title-o">WORKSTATIONS</li>
                            <?php endif; ?>
                            <li>
                                <a href="e-room" title="Lecture Room"><i class="fa fa-book fa-fw"></i> E - Room</a>
                            </li>
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)" class="link-disabled"><i class="fa fa-flask fa-fw"></i> Laboratories</a>
                                <ul class="dropdown-menu classroom-wrapper-ribbon-classroom" style="min-width: 120px;" id="custom-ssb-scollbar">
                                    <?php
                                        if($this->classroom->get_classroom_details_by_type('laboratory')) :
                                            foreach ($this->classroom->get_classroom_details_by_type('laboratory') as $row) :
                                    ?>
                                            <li>
                                                <a href="laboratory/<?php echo strtolower($row->room_no); ?>">
                                                <i class="fa fa-laptop fa-fw"></i> <?php echo $row->room_no; ?>
                                                </a>
                                            </li>
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
                    </li>
                    <li class="dropdown ribbon-li-offices">
                        <a href="javascript:void(0)" class="dropdown-toggle ribbon-item" data-toggle="dropdown">
                            &nbsp;Clusters&nbsp;<span class="fa fa-angle-down"></span>
                        </a>
                        <ul class="dropdown-menu pull-right" style="min-width: auto;">
                            <?php if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ): ?>
                            <li>
                                <a href="new/cluster" title="New Cluster"><i class="fa fa-plus-square fa-fw"></i> New</a>
                            </li>
                            <?php
                                endif;
                                if( $sess_access_rights === 'full_control' || $sess_access_rights === 'ultimate_control' ):
                            ?>
                            <li>
                                <a href="manage/clusters" title="Manage Clusters"><i class="fa fa-external-link fa-fw"></i> Manage</a>
                            </li>
                            <li class="disabled-title">WORKSTATIONS</li>
                            <?php else : ?>
                            <li class="disabled-title-o">WORKSTATIONS</li>
                            <?php endif; ?>
                            <li class="dropdown-submenu" id="cluster-dd-load">
                                <a href="javascript:void(0)" class="link-disabled"><i class="fa fa-trello fa-fw"></i> Departments</a>
                                <ul class="dropdown-menu dd-left cluster-wrapper-ribbon-cluster" id="custom-ssb-scollbar">
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
                                        <li class="disabled-li"><i class="fa fa-ban"></i>&nbsp;Empty</li>
                                    <?php
                                        endif;
                                    ?>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a href="javascript:void(0)" class="link-disabled"><i class="fa fa-file-text fa-fw"></i> Offices</a>
                                <ul class="dropdown-menu dd-left cluster-wrapper" id="custom-ssb-scollbar">
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
                    </li>
                    <li class="dropdown ribbon-li-caret">
                        <a href="javascript:void(0)" class="dropdown-toggle ribbon-item" data-toggle="dropdown">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li id="settings-lg">
                                <a href="settings" title="Settings"><i class="fa fa-cog fa-fw"></i> Settings</a>
                            </li>
                            <li>
                                <a href="about" title="About"><i class="fa fa-info-circle fa-fw"></i> About</a>
                            </li>
                            <li id="help-li">
                                <a href="help" title="Help"><i class="fa fa-question-circle fa-fw"></i> Help</a>
                            </li>
                            <li>
                                <a href="terms" title="Terms of Use"><i class="fa fa-file-text fa-fw"></i> Terms</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="signout" class="fg-gray" title="Sign out"><i class="fa fa-sign-out fa-fw"></i> <b><small>SIGN OUT</small></b></a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-ribbon-links hidden-lg">
                    <li class="dropdown ribbon-li">
                        <a href="javascript:void(0)" class="dropdown-toggle ribbon-item" data-toggle="dropdown">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu pull-right" style="min-width: auto;">
                            <li id="settings-sm">
                                <a href="settings"><i class="fa fa-cog fa-fw"></i> Settings</a>
                            </li>
                            <li>
                                <a href="about"><i class="fa fa-info-circle fa-fw"></i> About</a>
                            </li>
                            <li>
                                <a href="help"><i class="fa fa-question-circle fa-fw"></i> Help</a>
                            </li>
                            <li>
                                <a href="terms"><i class="fa fa-file-text fa-fw"></i> Terms</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="signout" class="fg-gray"><i class="fa fa-sign-out fa-fw"></i> <b><small>SIGN OUT</small></b></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>