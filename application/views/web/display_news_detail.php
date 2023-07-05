<?php
/**
 * Created by PhpStorm.
 * User: JAVED
 * Date: 2020-10-19
 * Time: 1:49 PM
 */
?>

<div style="height:100px"></div>
<div class="container-fluid">
    <div class="row">
        <div class="card" style="margin-top: 20px;margin-bottom: 50px;min-height: 400px;">
            <div class="card-header card-header-primary text-center">
                <h3 class="card-title ">Important News / Latest Announcements</h3>
            </div>
            <div class="card-body" style='margin-top:0px'>
                <div class="row">
                    <div class="col-md-12">
        <div class="card promoting-card" style="background-color:white">

            <!-- Card content -->
            <div class="card-body d-flex flex-row">
                <div>
                    <!-- Title -->
                    <h4 class="card-title font-weight-bold mb-2"><?php echo $newsarray['NEWS_DETAIL']; ?></h4>
                    <!-- Subtitle -->
                    <p class="card-text"><i class="far fa-clock pr-2" style="text-align: right"></i><?php echo $newsarray['DATE']?></p>
                </div>
            </div>
        </div>
                    </div>
                </div>

            </div>
        </div>