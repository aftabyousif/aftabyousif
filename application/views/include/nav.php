<?php
	if(!isset($side_bar_values) || !is_array($side_bar_values))
						{
$side_bar_values = array();

							  array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Dashboard',
                            'link' => "form/dashboard",
                            'class' =>'educate-icon educate-home icon-wrap'));
                        array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Application Form',
                            'link' => 'form/upload_application_challan',
                            'class' =>'educate-icon educate-professor icon-wrap'));
                    array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Download Admissoin Challan',
                            'link' => "CandidateSelection",
                            'class' =>'educate-icon educate-data-table icon-wrap'));
                        array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Admission Announcements',
                            'link' => "form/announcement",
                            'class' =>'educate-icon educate-data-table icon-wrap'));
                            array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Download Application Challan',
                            'link' => "form/application_list",
                            'class' =>'educate-icon educate-data-table icon-wrap'));
                             array_push($side_bar_values,array('is_submenu' => 0,
                            'is_tab_base'=>'N',
                            'value' => 'Download Hostel Form',
                            'link' => "assets/hostel_form.pdf",
                            'class' =>'educate-icon educate-data-table icon-wrap'));
						}
array_push($side_bar_values,array('is_submenu' => 0,
    'is_tab_base'=>'N',
    'value' => 'Logout',
    'link' => "logout",
    'class' =>'educate-icon educate-pages icon-wrap'));
?>

<div class="all-content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="logo-pro">
                    <a href="#"><img class="main-logo" src="<?=base_url()?>dash_assets/img/logo/logo1.png" alt="" /></a>
                </div>
            </div>
        </div>
    </div>
    <div class="header-advance-area" >
        <div class="header-top-area" >
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="header-top-wraper">
                            <div class="row">
                                <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12">
                                    <div class="menu-switcher-pro">
                                        <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                                            <i class="educate-icon educate-nav"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                    <div class="header-top-menu tabl-d-n">
                                        <ul class="nav navbar-nav mai-top-nav">

                                            <?php
                                            $nav_values = array();
                                            foreach($nav_values as $nav_value){
                                                if($nav_value['is_submenu']==0){
                                                    ?>
                                                    <li class="nav-item"><a href="<?=$nav_value['link'];?>" class="nav-link"><?=$nav_value['value'];?></a>
                                                    <?php
                                                }else if($nav_value['is_submenu']==1){
                                                    ?>

                                                    <li class="nav-item dropdown res-dis-nn">
                                                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><?=$nav_value['value'];?> <span class="angle-down-topmenu"><i class="fa fa-angle-down"></i></span></a>
                                                        <div role="menu" class="dropdown-menu animated zoomIn">

                                                            <?php
                                                            foreach($side_bar_value['sub_menu'] as $sub_menu){
                                                                ?>
                                                                <a href="<?=base_url().$sub_menu['link'];?>" class="dropdown-item"><?=$sub_menu['value'];?></a>


                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>


                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                    <div class="header-right-info">
                                        <ul class="nav navbar-nav mai-top-nav header-right-menu">

                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                                    <i class="educate-icon educate-bell" aria-hidden="true"></i><span class="indicator-nt"></span>
                                                </a>
                                                <div role="menu" class="notification-author dropdown-menu animated zoomIn">
                                                    <div class="notification-single-top">
                                                        <h1>Notifications</h1>
                                                    </div>
                                                    <ul class="notification-menu">

                                                    </ul>
                                                    <div class="notification-view">
                                                        <a href="notification.php">View All Notification</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                                    <?php
                                                    if(isset($user['PROFILE_IMAGE'])){
                                                        $v =itsc_url().PROFILE_IMAGE_PATH;
                                                        echo " <img src='$v{$user['PROFILE_IMAGE']}' alt=''  >";
                                                    }else {
                                                        $image_path_default = base_url() . "dash_assets/img/avatar/default-avatar.png";
                                                        echo " <img src='$image_path_default' alt=''  >";
                                                    }
                                                    ?>

                                                    <span class="admin-name"><?=isset($user['FIRST_NAME'])?$user['FIRST_NAME']:false;?></span>
                                                    <i class="fa fa-angle-down edu-icon edu-down-arrow"></i>
                                                </a>
                                                <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">

                                                    <li><a href="<?=base_url().$profile_url?>"><span class="edu-icon edu-user-rounded author-log-ic"></span>Application Form</a>
                                                    </li>
                                                    <li><a href="<?=base_url()?>changePassword"><span class="edu-icon edu-user-rounded author-log-ic"></span>Change Password</a>
                                                    </li>

                                                    <li><a href="<?=base_url()?>logout"><span class="edu-icon edu-locked author-log-ic"></span>Log Out</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="nav-item">

                                                &nbsp;&nbsp;&nbsp;&nbsp;

                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu start -->
        <div class="mobile-menu-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="mobile-menu">
                            <nav id="dropdown">
                                <ul class="mobile-menu-nav">
                                    <?php

                                    foreach($side_bar_values as $side_bar_value){
                                        if($side_bar_value['is_submenu']==0){
                                            ?>
                                            <li><a href="<?=base_url().$side_bar_value['link'];?>"><?=$side_bar_value['value'];?></a></li>
                                            <?php
                                        }else if($side_bar_value['is_submenu']==1){
                                            ?>
                                            <li><a data-toggle="collapse" data-target="#Charts" href="#"><?=$side_bar_value['value'];?> <span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                                                <ul class="collapse dropdown-header-top">


                                                    <?php
                                                    foreach($side_bar_value['sub_menu'] as $sub_menu){
                                                        ?>
                                                        <li><a href="<?=$sub_menu['link'];?>"><?=$sub_menu['value'];?></a></li>

                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu end -->

        <!--
                    <div class="breadcome-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="breadcome-list">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="breadcome-heading">
                                                    <form role="search" class="sr-input-func">
                                                        <input type="text" placeholder="Search..." class="search-int form-control">
                                                        <a href="#"><i class="fa fa-search"></i></a>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <ul class="breadcome-menu">
                                                    <li><a href="#">Home</a> <span class="bread-slash">/</span>
                                                    </li>
                                                    <li><span class="bread-blod">Dashboard V.1</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        -->

    </div>
