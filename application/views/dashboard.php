<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 4:22 PM
 */
//prePrint($user_application_list);
?>

<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="white-box">
                            <div class="panel-group edu-custon-design" id="accordion">
                                <?php
                                $last_index =count($user_application_list)-1;
                                for($i=$last_index ; $i >= 0; $i--){
                                    $user_application = $user_application_list[$i];
                                    $in = "";
                                    if($last_index==$i){
                                        $in = "in";
                                    }
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading accordion-head">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i?>">
                                                    <?=ucwords(strtolower($user_application['NAME']))?> <?=ucwords(strtolower($user_application['PROGRAM_TITLE']))?> Degree Program For The Academic Year <?=$user_application['YEAR']?> </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?=$i?>" class="panel-collapse panel-ic collapse <?=$in?>">
                                            <div class="panel-body admin-panel-content animated bounce">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <table class="table table-bordered">
                                                        <tr >
                                                            <th colspan="2"><h4>Admission Form Status </h4></th>
                                                        </tr>
                                                        <tr >
                                                            <th style="width: 30%;">Form Status</th>
                                                            <th ><?=$user_application['STATUS_NAME']?></th>
                                                        </tr>
                                                        <tr >
                                                            <th >Form Remarks</th>
                                                            <th ><?=$user_application['REMARKS']?$user_application['REMARKS']:"N/A"?></th>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <table class="table table-bordered">
                                                        <tr >
                                                            <th colspan="2">Admission Form Information</th>

                                                        </tr>
                                                        <tr >
                                                            <th style="width: 30%;">Print Form</th>
                                                            <th ></th>
                                                        </tr>
                                                        <tr >
                                                            <th >Admit Card</th>
                                                            <th ></th>
                                                        </tr>

                                                        <tr >
                                                            <th >Test Score</th>
                                                            <th ></th>
                                                        </tr>
                                                        <tr >
                                                            <th >Apply Choice</th>
                                                            <th ></th>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php
                                }
                                ?>


                            </div>
                        </div>
                    </div>

    </div>
</div>

