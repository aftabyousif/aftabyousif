<?php

function profile_validate($data){
    $application['PROGRAM_TYPE_ID'] = 0;
    $validation_array =  get_validation_array($application);
   $user_reg_validation= $validation_array['users_reg'];
    $error ="";
     foreach($user_reg_validation as $column=>$value){


            if(preg_match("/".$value['regex']."/", $data[$column])){

            }else{
                $error.="<div class='text-danger'>{$value['error_msg']}</div>";

            }
        }
        if($error==""){
          return true;  
        }else{
            $error;
        }
}
function qualification_validate($qualifications,$program_type_id){
    $application['PROGRAM_TYPE_ID'] = $program_type_id;
    $validation_array =  get_validation_array($application);
    
    
     $qualifications_validation = $validation_array['qualifications'];
        $qualification_error_msg = $validation_array['qualifications']['DEGREE_ID_MSG'];
        $or_qualifications_validation = $validation_array['or_qualifications']['OR_DEGREE_ID'];
        $or_qualification_error_msg = $validation_array['or_qualifications']['OR_DEGREE_ID_MSG'];
        
    $error ="";
      foreach($qualifications as $qual){

            foreach($qualifications_validation['DEGREE_ID'] as $k=>$val){
                if($qual['DEGREE_ID']==$val){
                    unset($qualifications_validation['DEGREE_ID'][$k]);
                    unset($qualification_error_msg[$k]);

                    break;
                }
            }
        }
        foreach ($qualification_error_msg as $error_msg){
            $error.="<div class='text-danger'>{$error_msg}</div>";
        }


            if(is_array($or_qualifications_validation)){
                $bool = true;
                foreach($qualifications as $qual){

                    foreach($or_qualifications_validation as $val){
                        if($qual['DEGREE_ID']==$val){
                            $bool = false;
                            break;
                        }
                    }
                }

                if($bool){
                    $error.="<div class='text-danger'>{$or_qualification_error_msg}</div>";
                }

            }
        if($error==""){
          return true;  
        }else{
            $error;
        }
}
function get_validation_array($application){
        $must_provide = "Must Be Provided";
        $must_select = "Must Be Provided";
        $must_upload = "Must Upload";

        $qualification = array();
        $master_id_or = null;
        if($application['PROGRAM_TYPE_ID']==1){
        $qualification =  $bachelor = array(2,3);
        $qualification_error_msg =  $bachelor_error_msg = array("Matriculation information is missing. Please must add","Intermediate information is missing. Please must add");

        }else if($application['PROGRAM_TYPE_ID']==2){
            $qualification =  $master_id = array(2,3);
             $master_id_or = array(4,5,6);

            $qualification_error_msg =  $master_error_msg = array("Matriculation information is missing. Please must add","Intermediate information is missing. Please must add");
        }

        $validation_array=array(
                "users_reg" =>array(
                    "FIRST_NAME"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Full Name $must_provide as per Matriculation"),
                    //"LAST_NAME"=>array("regex"=>"^[A-Za-z.]+","error_msg"=>"Surname $must_provide"),
                    "FNAME"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Father $must_provide"),
                    "GENDER"=>array("regex"=>"[A-Za-z]{1}","error_msg"=>"Gender $must_select"),
                    "MOBILE_NO"=>array("regex"=>"[0-9]{10}","error_msg"=>"Mobile Number $must_provide"),
                    "HOME_ADDRESS"=>array("regex"=>"[A-Za-z0-9\-\\,.]+","error_msg"=>"Home Address $must_provide"),
                    "PERMANENT_ADDRESS"=>array("regex"=>"[A-Za-z0-9\-\\,.]+","error_msg"=>"Parmanent Address $must_provide"),
                    "DATE_OF_BIRTH"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Date of Birth $must_provide"),
                    "BLOOD_GROUP"=>array("regex"=>"^(A|B|AB|O)[+-]$","error_msg"=>"Blood Group $must_select"),
                    "MOBILE_CODE"=>array("regex"=>"[0-9]{4}","error_msg"=>"Mobile $must_select"),
                    "COUNTRY_ID"=>array("regex"=>"[0-9]","error_msg"=>"Country $must_select"),
                    "PROVINCE_ID"=>array("regex"=>"[0-9]","error_msg"=>"Province $must_select"),
                    "DISTRICT_ID"=>array("regex"=>"[0-9]","error_msg"=>"District $must_select"),
                    "PROFILE_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Profile Image $must_upload"),
                    "DOMICILE_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Domicile Image $must_upload"),
                    "DOMICILE_FORM_C_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Domicile Form C Image $must_upload"),
                    "EMAIL"=>array("regex"=>"^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$","error_msg"=>"Email $must_provide"),
                    "RELIGION"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Religion $must_provide"),
                    "U_R"=>array("regex"=>"^\w{1}$","error_msg"=>"Area $must_select"),

                ),
                "CNIC"=>array(
                    "CNIC_NO"=>array("regex"=>"[0-9]{13}","error_msg"=>"CNIC No $must_provide"),
                    "CNIC_FRONT_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"CNIC Front / B-Form Image $must_upload"),
                    "CNIC_BACK_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"CNIC Back / B-Form  Image $must_upload"),

                ),
                "PASSPORT"=>array(
                    "PASSPORT_NO"=>array("regex"=>"[0-9]{13}","error_msg"=>"Passport No $must_provide"),
                    "PASSPORT_FRONT_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Passport Front Image $must_upload"),
                    "PASSPORT_BACK_IMAGE"=>array("regex"=>"[a-zA-Z]|\d|[^a-zA-Z\d]","error_msg"=>"Passport Back / B-Form Image $must_upload"),
                ),
                "guardian"=>array(
                    "FIRST_NAME"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Guardian Name $must_provide"),
                    "RELATIONSHIP"=>array("regex"=>"[A-Za-z]{2}","error_msg"=>"Relationship Name $must_select"),
                    "MOBILE_CODE"=>array("regex"=>"[0-9]{4}","error_msg"=>"Guardian Mobile Code $must_select"),
                    "MOBILE_NO"=>array("regex"=>"[0-9]{10}","error_msg"=>"Guardian Mobile Number $must_provide"),
                    "HOME_ADDRESS"=>array("regex"=>"[A-Za-z0-9\-\\,.]+","error_msg"=>"Home Address $must_provide"),
                ),
                "qualifications"=>array(
                    "DEGREE_ID" =>$qualification,
                    "DEGREE_ID_MSG" =>$qualification_error_msg
                ),
                "or_qualifications"=>array(
                    "OR_DEGREE_ID"=>$master_id_or,
                    "OR_DEGREE_ID_MSG"=>"Bachelor 14 Year / BA / BSC / BCOM / BSc information. Please must add"
                )
            );
            return $validation_array;
    }
?>
<?php
function show_progress_status($data){
    $user_application = $data['application'];
    $user =  $data['users_reg'];
    $qualifications = $data['qualifications'];
    $category = $data['category'];
    $program_choice = $data['program_choice'];
    $program_type_id = $user_application['PROGRAM_TYPE_ID'];
    $APPLICATION_ID = $user_application['APPLICATION_ID'];
    $application_id_encode = urlencode(base64_encode($APPLICATION_ID));
    $application_url = "form/set_application_id/$application_id_encode/";
    //  prePrint($category);
    //                                                   prePrint($program_choice);
    //                                             exit();
    $counting = 1;
    ?>
                         <div class="stepper-wrapper">

                                                    <div class="stepper-item completed">
                                                        <div class="step-counter"><?=$counting++;?></div>
                                                        <div class="step-name">Registration</div>
                                                    </div>
                                                    <?php
                                                    if($user_application['PAID']&&$user_application['CHALLAN_IMAGE']){
                                                       $result=true; 
                                                    }
                                                     $go_url = "form/upload_application_challan";
                                                    $url = base_url().$application_url.urlencode(base64_encode($go_url));
                                                    ?>
                                                   
                                                    <div onclick="window.location.href='<?=$url?>'" class="stepper-item <?=($result===true)?'completed':''?>">
                                                     
                                                           <div class="step-counter"><?=$counting++;?></div>
                                                        <div class="step-name">Challan Upload</div>
                                                     
                                                    </div>
                                                   
                                                    <?php
                                                    $result = profile_validate($user);
                                                     $go_url = "candidate/profile";
                                                    $url = base_url().$application_url.urlencode(base64_encode($go_url));
                                                    ?>
                                                    <div onclick="window.location.href='<?=$url?>'" class="stepper-item <?=($result===true)?'completed':''?>">
                                                        <div class="step-counter"><?=$counting++;?></div>
                                                        <div class="step-name">Personal Information</div>
                                                    </div>
                                                    <?php
                                                    if($user_application['PROGRAM_TYPE_ID'] == 1){
                                                    ?>
                                                    <div class="stepper-item completed">
                                                        <div class="step-counter"><?=$counting++;?></div>
                                                        <div class="step-name">Entry Test</div>
                                                    </div>
                                                     <?php
                                                    }
                                                    $result = qualification_validate($qualifications,$user_application['PROGRAM_TYPE_ID']);
                                                    // prePrint($result);
                                                    // exit();
                                                     
                                                    $go_url = "candidate/add_inter_qualification";
                                                    $url = base_url().$application_url.urlencode(base64_encode($go_url));
                                                      ?>
                                                       <?php
                                                    if($user_application['PROGRAM_TYPE_ID'] == 1){
                                                    ?>
                                                    <div  onclick="window.location.href='<?=$url?>'" class="stepper-item <?=($result===true)?'completed':''?>">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <div  onclick="window.location.href='<?=$url?>'" class="stepper-item <?=($result===true)?'completed':''?>">
                                                            <?php
                                                    }
                                                        ?>
                                                        <div class="step-counter"><?=$counting++;?></div>
                                                        <div class="step-name">Qualification</div>
                                                    </div>
                                                     <?php
                                                    $go_url = "form/select_category";
                                                    $url = base_url().$application_url.urlencode(base64_encode($go_url));
                                                      ?>
                                                       <?php
                                                    if($user_application['PROGRAM_TYPE_ID'] == 1){
                                                    ?>
                                                    <div onclick="window.location.href='<?=$url?>'" class="stepper-item <?=count($category)>0?'completed':''?>">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <div onclick="window.location.href='<?=$url?>'" class="stepper-item <?=count($category)>0?'completed':''?>">
                                                            <?php
                                                    }
                                                        ?>
                                                    
                                                        <div class="step-counter  "><?=$counting++;?></div>
                                                        <div class="step-name">Choose Category</div>
                                                    </div>
                                                    <?php
                                                    $go_url = "form/select_program";
                                                    $url = base_url().$application_url.urlencode(base64_encode($go_url));
                                                      ?>
                                                         <?php
                                                    if($user_application['PROGRAM_TYPE_ID'] == 1){
                                                    ?>
                                                    <div onclick="window.location.href='<?=$url?>'" class="stepper-item <?=count($program_choice)>0?'completed':''?>">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <div onclick="window.location.href='<?=$url?>'" class="stepper-item <?=count($program_choice)>0?'completed':''?>">
                                                            <?php
                                                    }
                                                        ?>
                                                    
                                                        <div class="step-counter "><?=$counting++;?></div>
                                                        <div class="step-name">Choose Degree Program</div>
                                                    </div>
                                                    <?php
                                                    if($user_application['PROGRAM_TYPE_ID'] == 1){
                                                    ?>
                                                    <div class="stepper-item <?=($user_application['STATUS_ID']>=3)?'completed':''?>">
                                                        <?php
                                                    }else{
                                                        ?>
                                                        <div class="stepper-item <?=($user_application['STATUS_ID']>=3)?'completed':''?>">
                                                            <?php
                                                    }
                                                        ?>
                                                    
                                                  
                                                        <div class="step-counter "><?=$counting++;?></div>
                                                        <div class="step-name">Complete</div>
                                                    </div>
                                                </div>
<?php
}
?>