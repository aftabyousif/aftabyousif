<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 9/16/2020
 * Time: 1:38 PM
 */
?>
<!--<br>-->
<hr>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="basic-login-inner">
                        <h3 style='font-size:11pt'>Update Contact Details</h3>
                        <form action="#" id='basic_info_form'>
                        <h3 class='text-danger'><?=$user['REMARKS']=='NEW_ADMISSION'?'New User '.$user['USER_ID']:'Old User '.$user['USER_ID'];?></h3>
                        <div class="form-group-inner">
                       <label for="exampleInput1">Full Name</label>
                        <input type="text" id="FIRST_NAME" class="form-control allow-number" readonly placeholder="FIRST_NAME" name="FIRST_NAME" value="<?=$user['FIRST_NAME']?>">
                        </div>
                         <div class="form-group-inner">
                       <label for="exampleInput1">Surname</label>
                        <input type="text" id="LAST_NAME" class="form-control allow-number" readonly placeholder="LAST_NAME" name="LAST_NAME" value="<?=$user['LAST_NAME']?>">
                        </div>
                         <div class="form-group-inner">
                       <label for="exampleInput1">Father's Name</label>
                        <input type="text" id="FNAME" class="form-control allow-number" readonly placeholder="FNAME" name="FNAME" value="<?=$user['FNAME']?>">
                        </div>
                                 <div class="form-group-inner">
                       <label for="exampleInput1">CNIC No</label>
                        <input type="text" id="CNIC_NO" class="form-control allow-number" placeholder="CNIC_NO" name="CNIC_NO" value="<?=$user['CNIC_NO']?>">
                        </div>
                        
                        <div class="form-group-inner">
                       <label for="exampleInput1">Email Address</label>
                        <input type="email" id="EMAIL" class="form-control allow-string" placeholder="Email" name="EMAIL" value="<?=$user['EMAIL']?>">
                        </div>
                   
                        
                        <div class="form-group-inner">
                     <label for="exampleInput1">Mobile No</label>
                     <input type="text" id="MOBILE_NO" class="form-control allow-number" placeholder="MOBILE_NO" name="MOBILE_NO" value="<?=$user['MOBILE_NO']?>">
                        </div>
                        <style>
                        .inline-remember-me{
                            padding:10px;
                        }
                        </style>
                        <?php if($ROLE_ID != 8 ){?>
                        <div class="login-btn-inner">
                            <div class="inline-remember-me">
                        <button class="btn btn-sm btn-primary   btn-custon-rounded-two" id="save" type="button"><i class='fa fa-save'></i> Update</button>

                        </div>
                            <div class="inline-remember-me">
                        <button class="btn btn-sm btn-warning   btn-custon-rounded-two" id="forget" type="button"><i class='fa fa-save'></i> Change Password</button>

                        </div>
                        
                        
                        </div>
                        <?php }?>
                        
                        </form>
                        </div>
        </form>

        </div>
    <!--</div>-->
<!--<div class="row">-->
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <section>
            <!-- Static Table Start -->
            <div class="static-table-area">
                <div class="container-fluid">
                    <!--<div class="row">-->
                        <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">-->
                            <div class="sparkline8-list">


                                <div class="sparkline8-graph">
                                    <div class="static-table-list table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr style="font-size: 11pt; font-family: 'Times New Roman'" class="text-center">
                                                <th>#</th>
                                                <th>APP ID</th>
                                                <th><i class="educate-icon educate-library"></i> Campus</th>
                                                <!--<th><i class="fa fa-location-arrow"></i> Campus City</th>-->
                                                <th>Degree Program</th>
                                                <th>Form Status</th>
                                               
                                                <!--										<th>Batch</th>-->
                                                <!--<th>Form Start Date</th>-->
                                                <!--<th>Form Last Date</th>-->
                                                <th colspan="4">Action</th>
                                                 <th>Challan Date <br><input data-toggle="tooltip" title="" type="date" id="EXP_DATE" name="EXP_DATE" value='<?=date('Y-m-d')?>'class="form-control"  ><th>
                                         
                                                <th>Admission Session</th>
                                                   </tr>
                                            </thead>
                                            <?php
                                            if(is_array($user_application_list) || is_object($user_application_list))
                                            {
                                                $sno=0;
                                                foreach ($user_application_list as $admission_announcement_key=>$admission_announcement_value)
                                                {

                                                    $is_already_applied = false;

                                                    //this method is define in functions_helper in this mehtod we provide Array and key of arary and finding value method return obj if exists else return false;
                                                    //$res = findObjectinList($user_application_list,'ADMISSION_SESSION_ID',$admission_announcement_value['ADMISSION_SESSION_ID']);

                                                    // if res contain not false value or any o object it mean user already applied
                                                    $APPLICATION_ID=0;
//                                            if($res){
//
//                                                $APPLICATION_ID=$res['APPLICATION_ID'];
//                                                $is_already_applied = true;
//                                            }
//                                            foreach($user_application_list as $user_app){
//                                                if($SESSION_ID==$user_app['SESSION_ID']){
//
//                                                }
//                                            }
                                                    $sno++;
                                                    $APP_ID = $admission_announcement_value['APPLICATION_ID'];
                                                    $APPLICANT_USER_ID = $admission_announcement_value['USER_ID'];
                                                    $NAME = $admission_announcement_value['NAME'];
                                                    $YEAR = $admission_announcement_value['YEAR'];
                                                    $ADMISSION_SESSION_ID = $admission_announcement_value['ADMISSION_SESSION_ID'];
                                                    $CAMPUS_ID = $admission_announcement_value['CAMPUS_ID'];
                                                    $SESSION_ID = $admission_announcement_value['SESSION_ID'];
                                                    $PROGRAM_TYPE_ID = $admission_announcement_value['PROGRAM_TYPE_ID'];
                                                    $ADMISSION_START_DATE = $admission_announcement_value['ADMISSION_START_DATE'];
                                                    $ADMISSION_END_DATE = $admission_announcement_value['ADMISSION_END_DATE'];
                                                    $LOCATION = $admission_announcement_value['LOCATION'];
                                                    $PROGRAM_TITLE = $admission_announcement_value['PROGRAM_TITLE'];
                                                    $BATCH_REMARKS = $admission_announcement_value['BATCH_REMARKS'];
                                                   
                                                    $APPLICATION_ID=$admission_announcement_value['APPLICATION_ID'];
                                                    //if($APPLICATION_ID==40532)
                                                    //prePrint($admission_announcement_value);
                                                     $STATUS_ID=$admission_announcement_value['STATUS_NAME'];
                                                    if ($BATCH_REMARKS == 'S') $BATCH_REMARKS = "Spring";
                                                    elseif ($BATCH_REMARKS == 'F') $BATCH_REMARKS = "Fall";

                                                    $start_date = date_create($ADMISSION_START_DATE);
                                                    $start_date = date_format($start_date,'D, d-m-Y');
                                                    $end_date = date_create($ADMISSION_END_DATE);
                                                    $end_date = date_format($end_date,'D, d-m-Y');

                                                    $link = "";

                                                    $APPLICATION_ID = urlencode(base64_encode($APPLICATION_ID));
                                                    $APPLICANT_USER_ID = urlencode(base64_encode($APPLICANT_USER_ID));

                                                    $url = "AdminPanel/admission_form_challan";
                                                    $url = urlencode(base64_encode($url));
                                                    $download_challan_link = base_url()."AdminPanel/set_application_id/$APPLICATION_ID";
                                                   // $download_challan_link = "<button class='btn btn-warning' onclick=\"download_challan('$url')\">Download Challan</button>";
                                                    
                                                    $url = "AdminPanel/student_update";
                                                    $url = urlencode(base64_encode($url));
                                                    $url = base_url()."AdminPanel/set_application_id/$APPLICATION_ID/$url";
                                                    $challan_link = "<a class='btn btn-warning' href='{$url}' target='_blank'>Go To Application</a>";
                                                     
                                                     
                                                     $url_select_subject = "AdminPanel/select_subject";
                                                    $url_select_subject = urlencode(base64_encode($url_select_subject));
                                                    $url_select_subject = base_url()."AdminPanel/set_application_id/$APPLICATION_ID/$url_select_subject";
                                                    $select_subject_link = "<a class='btn btn-info' href='{$url_select_subject}' target='_blank'>Update Minors</a>";
                                                    
                                                    $url_unlock = "AdminPanel/student_application_unlock";
                                                    $url_unlock = urlencode(base64_encode($url_unlock));
                                                    $url_unlock = base_url()."FormVerification/set_application_id/$APPLICANT_USER_ID/$APPLICATION_ID/$url_unlock";


                                                    $url_delete = "AdminPanel/delete_application";
                                                    $url_delete = urlencode(base64_encode($url_delete));
                                                    $url_delete = base_url()."FormVerification/set_application_id/$APPLICANT_USER_ID/$APPLICATION_ID/$url_delete";

                                                	$url_verification = "FormVerification/review";
													$url_verification = urlencode(base64_encode($url_verification));
													$url_verification = base_url()."FormVerification/set_application_id/$APPLICANT_USER_ID/$APPLICATION_ID/$url_verification";
													$verification_link = "<a class='btn btn-in' href='{$url_verification}' target='_blank'><i class='fa fa-edit'>&nbsp;&nbsp; Start Verification</i></a>";
                                                    
                                                     $admit_card = $this->AdmitCard_model->getAdmitCardOnAppID($admission_announcement_value['APPLICATION_ID']);
                			                        $admit_card_url = null;
                                                    if($admit_card){
                                                    $url_data = array("USER_ID"=>$admission_announcement_value['USER_ID'],"APPLICATION_ID"=>$admission_announcement_value['APPLICATION_ID'],"CARD_ID"=>$admit_card['CARD_ID']);
                		                            $url_data = Base64url_encode(base64_encode(urlencode(json_encode($url_data))));
                		                            $admit_card_url = base_url()."slip/".$url_data;
                                                        
                                                    }
                                                    
                                                    
                                                     
                                                    	$url_print_form = "AdminPanel/application_form";
													$url_print_form = urlencode(base64_encode($url_print_form));
													$url_print_form = base_url()."AdminPanel/set_application_id/$APPLICATION_ID/$url_print_form";
												//   if($ROLE_ID != 8){
												//       $admit_card_url = $url_print_form = "#";
												//   }
                                                    
                                                    //$url_print_form = "";

                                                    ?>


                                                    <tbody>
                                                    <tr style="font-size: 11pt;color: black">
                                                        <td><?=$sno?></td>
                                                        <td><?=$APP_ID?></td>
                                                        <td><?=ucwords(strtolower($NAME))?></td>
                                                        <!--<td><?=ucwords(strtolower($LOCATION))?></td>-->
                                                        <td><?=ucwords(strtolower($PROGRAM_TITLE))?></td>
                                                   
                                                        <!--<td><?=$start_date?></td>-->
                                                        <!--<td><?=$end_date?></td>-->

                                                        <td><?=$STATUS_ID?></td>
                                                        <?php if($ROLE_ID != 8 ){?>
                                                        
                                                        <td><?=$challan_link?> <br/><br/> <?=$select_subject_link?></td>
                                                         <?php }?>
                                                        <td>
                                                            <?php if($ROLE_ID != 8 ){?>
                                                            <button class="btn btn-primary" onclick="goto_url('<?=$url_delete?>','Do you want to delete this application')">Delete Application</button>
                                                             <?php }?>
                                                             <?php
                                                        if($ROLE_ID != 8 && $admission_announcement_value['IS_SUBMITTED']=='Y'){
                                                            ?>
                                                            <br><br> <button class="btn btn-danger" onclick="goto_url('<?=$url_unlock?>','Do you want to unlock this application')">Unlock Application</button>
                                                            <?php
                                                        }
                                                        ?></td>
                                                        <td>
                                                            <a href="<?=$url_verification?>" class="btn btn-info" target="_blank">Verification</a>
                                                            <?php
                                                        if($ROLE_ID != 8 && $admission_announcement_value['STATUS_ID']>=3){
                                                            ?>
                                                            <br>
                                                            <br>
                                                            <a href="<?=$url_print_form?>" class="btn btn-info" style="background-color:black" target="_blank">Print Form</a>
                                                            <?php
                                                        }
                                                        ?>
                                                        </td>
                                                         <td>
                                                             <?php if($ROLE_ID != 8 ){?>
                                                             <button class="btn btn-warning" onclick="download_challan('<?=$download_challan_link?>')">Download Challan</button>
                                                             <?php
                                                             }
                                                             ?>
                                                            <?php
                                                            if($ROLE_ID != 8 && $admit_card_url){
                                                                echo "<br><br><a href='$admit_card_url' target='_blank' class='btn btn-success'>Download Slip</a>";
                                                            }?>
                                                         </td>
                                                        <td><?=ucwords(strtolower($BATCH_REMARKS))?> <?=$YEAR?></td>
                                                    </tr>
                                                    </tbody>

                                                    <?php
                                                }//foreach
                                            }//if
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <!--</div>-->
                    <!--</div>-->
                    <br>

                </div>
            </div>

        </section>
    </div>
</div>
</div>
<script>
function download_challan(url){
     let date = $('#EXP_DATE').val(); 
    const encodedData = encodeURIComponent(window.btoa('AdminPanel/admission_form_challan/'+date)); 
  
    window.location.href = url+"/"+encodedData; 
}
  function goto_url(url,msg){
        if(confirm(msg)){
            window.location.href = url;
        }
    }
    <?php
    if($ROLE_ID!=8){
        
   
    ?>
    $("#save").on('click',function () {
        if(confirm("Are You Sure?\nDo you want to update information")) {
            event.preventDefault();
            var form = $('#basic_info_form')[0];
            var data = new FormData(form);
            $('.preloader').fadeIn(700);
            jQuery.ajax({
                url: "<?=base_url()?>AdminPanel/basic_info_form_handler",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: data,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            percentComplete = Math.round(percentComplete);
                            $("#pre_text").html("<br><br><h3>Uploading " + percentComplete + "%</h3>");
                            //console.log(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
                success: function (data, status) {
                    $('.preloader').fadeOut(700);
                    $('input[name="csrf_form_token"]').val(data.csrfHash);
                    $('#alert_msg_for_ajax_call').html("");

                    alertMsg("Success", data.MESSAGE);
                    // console.log(data.MESSAGE);
                },
                beforeSend: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("LOADING...!");
                },
                error: function (data, status) {
                    var value = data.responseJSON;
                    alertMsg("Error", value.MESSAGE);
                    // alert("Error"+status);
                    // $('input[name="csrf_form_token"]').val(value.csrfHash);
                    $('#alert_msg_for_ajax_call').html(value.MESSAGE);
                    //console.log(value.MESSAGE);
                    $('.preloader').fadeOut(700);
                },
            });
        }
    });
     $("#forget").on('click',function () {
        if(confirm("Are You Sure?\nDo you want to Password")) {
            event.preventDefault();
            var form = $('#basic_info_form')[0];
            var data = new FormData(form);
            $('.preloader').fadeIn(700);
            jQuery.ajax({
                url: "<?=base_url()?>AdminPanel/forget_user_password",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: data,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            percentComplete = Math.round(percentComplete);
                            $("#pre_text").html("<br><br><h3>Uploading " + percentComplete + "%</h3>");
                            //console.log(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
                success: function (data, status) {
                    $('.preloader').fadeOut(700);
                    $('input[name="csrf_form_token"]').val(data.csrfHash);
                    $('#alert_msg_for_ajax_call').html("");

                    alertMsg("Success", data.MESSAGE);
                    // console.log(data.MESSAGE);
                },
                beforeSend: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("LOADING...!");
                },
                error: function (data, status) {
                    var value = data.responseJSON;
                    alertMsg("Error", value.MESSAGE);
                    // alert("Error"+status);
                    // $('input[name="csrf_form_token"]').val(value.csrfHash);
                    $('#alert_msg_for_ajax_call').html(value.MESSAGE);
                    //console.log(value.MESSAGE);
                    $('.preloader').fadeOut(700);
                },
            });
        }
    });
    <?php
    }
    ?>
</script>