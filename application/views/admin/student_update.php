<?php
/**
 * Created by PhpStorm.
 * User: JAVED
 * Date: 2020-09-16
 * Time: 12:09 PM
 */
$read_only="";
$readonly="";
$family_info="";
?>
<script>
    <?php
    $res = getcsrf($this);
    ?>
    var csrfName="<?=$res['csrfName']?>";
    var csrfHash="<?=$res['csrfHash']?>";
    var is_next = false;
    function callAjax(url,set_id,msg_id="alert_msg_for_ajax_call"){
        jQuery.ajax({
            url:url ,
            async:true,
            success: function (data, status) {
                $('#'+msg_id).html("");
                $('#'+set_id).html(data);
            },
            beforeSend:function (data, status) {


                $('#'+msg_id).html("LOADING...!");
            },
            error:function (data, status) {
                alertMsg("Error",data.responseText);
                $('#'+msg_id).html("Something went worng..!");
            },
        });
        // jQuery.ajax({
        //     url:url ,
        //
        //     async:false,
        //     success: function (data, status) {
        //         $('#'+msg_id).html("");
        //         $('#'+set_id).html(data);
        //     },
        //     beforeSend:function (data, status) {
        //         $('#'+msg_id).html("LOADING...!");
        //     },
        //     error:function (data, status) {
        //         alertMsg("Error",data.responseText);
        //         $('#'+msg_id).html("Something went worng..!");
        //     },
        // });
    }

</script>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-payment-inner-st">
                <ul id="myTabedu1" class="tab-review-design">
                    <li class="active"><a  id="basic_information_tab" href="#basic_information">Basic Information</a></li>
                    <li class=""><a id="education_tab" href="#education"> Education Information</a></li>
                    <!--					<li class=""><a id="experiances_tab" href="#experiances"> Experience</a></li>-->
                    <li class=""><a id="documents_tab" href="#documents">Additional Documents</a></li>
                    <li class=""><a id="challan_tab" href="#challan">Upload Challan</a></li>
                    <li class=""><a  onclick="window.location.href = '<?=base_url()?>AdminPanel/select_category'"> Select Category</a></li>
                    <li class=""><a  onclick="window.location.href = '<?=base_url()?>AdminPanel/select_program'"> <br>Select Program</a></li>
                    <li class=""><a  onclick="window.location.href = '<?=base_url()?>AdminPanel/special_self_choices'"> <br>Select Special Program</a></li>
                    <li class=""><a  onclick="window.location.href = '<?=base_url()?>AdminPanel/add_evening_category'"> <br>Select Evening Program</a></li>
                </ul>

                <div id="myTabContent" class="tab-content custom-product-edit">
                    <div class="product-tab-list tab-pane fade active in" id="basic_information">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                <!--                                        <form action="/upload" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="demo1-upload" novalidate="novalidate">-->
                                <?=form_open('', 'class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="base_profile_form"');?>
                                <div class="row">

                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Full Name
                                                <span class="text-danger">*<small>As Per Matriculation</small></span></label>
                                            <input <?=$read_only?> type="text" id="FIRST_NAME" class="form-control allow-string"  placeholder="Full Name" name="FIRST_NAME" value="<?=$user['FIRST_NAME']?>">
                                            <input type="text" id="USER_ID" class="" name="USER_ID" value="<?=$user['USER_ID']?>" hidden>

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Father's Name
                                                <span class="text-danger">*</span></label>
                                            <input <?=$read_only?> type="text" id="FNAME" class="form-control allow-string"  name="FNAME" value="<?=$user['FNAME']?>"  >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Surname
                                                <span class="text-danger">*</span></label>
                                            <input <?=$read_only?> type="text" id="LAST_NAME" class="form-control allow-cast"  name="LAST_NAME" value="<?=$user['LAST_NAME']?>"  >

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="BLOOD_GROUP" class="bmd-label-floating">Religion

                                                <span class="text-danger">*</span></label>

                                            <select name="RELIGION" id="RELIGION" class="form-control">
                                                <?php
                                                $selected = "";

                                                echo "<option value='0' >--Choose Religion--</option>";
                                                foreach($RELIGIONS as $RELIGION){
                                                    if($user['RELIGION']==$RELIGION)
                                                        echo "<option value='$RELIGION' selected>$RELIGION</option>" ;
                                                    else
                                                        echo "<option value='$RELIGION' >$RELIGION</option>" ;
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="BLOOD_GROUP" class="bmd-label-floating">Blood Group

                                                <span class="text-danger">*</span></label>

                                            <select name="BLOOD_GROUP" id="BLOOD_GROUP" class="form-control">
                                                <?php
                                                $selected = "";

                                                echo "<option value='0' >--Choose Blood Group--</option>";
                                                foreach($blood_groups as $boolg){
                                                    if($user['BLOOD_GROUP']==$boolg)
                                                        echo "<option value='$boolg' selected>$boolg</option>" ;
                                                    else
                                                        echo "<option value='$boolg' >$boolg</option>" ;
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group res-mg-t-15">
                                            <label for="GENDER" class="bmd-label-floating">Gender

                                                <span class="text-danger">*</span></label>

                                            <select name="GENDER" id="GENDER" class="form-control">
                                                <option value='0'>--choose--</option> ;
                                                <?php
                                                $selected = "";
                                                $blood_groups=array('M'=>"MALE","F"=>"FEMALE");
                                                foreach($blood_groups as $k=>$boolg){
                                                    if($user['GENDER']==$k)
                                                        echo "<option value='$k' selected>$boolg</option>" ;
                                                    else
                                                        echo "<option value='$k' >$boolg</option>" ;
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>



                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Date Of Birth
                                                <span class="text-danger">* &nbsp;<small>dd/mm/yyyy (As per CNIC)</small></span></label>
                                            <div class="form-group data-custon-pick" id="data_2">
                                                <div class="input-group date">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input <?=$readonly?> type="text" id="DATE_OF_BIRTH"  name="DATE_OF_BIRTH" class="form-control" value="<?=getDateForView($user['DATE_OF_BIRTH'])?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    if($user['IS_CNIC_PASS']==='P'){
                                        ?>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInput1" class="bmd-label-floating">Passport No
                                                    <span class="text-danger">*</span></label>
                                                <input readonly type="text" id="PASSPORT_NO" class="form-control" name="PASSPORT_NO"
                                                       value="<?= $user['PASSPORT_NO'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInput1" class="bmd-label-floating">Passport Expiry
                                                    <span class="text-danger">*<small>dd/mm/yyyy</small></span></label>
                                                <div class=" data-custon-pick" id="data_2">
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        <input data-toggle="tooltip" title="Passport Expiry Date"  type="text" id="PASSPORT_EXPIRY"  name="PASSPORT_EXPIRY" class="form-control" value="<?=getDateForView($user['PASSPORT_EXPIRY'])?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    else {
                                        ?>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInput1" class="bmd-label-floating">CNIC / Form-B
                                                    <span class="text-danger">*</span></label>
                                                <input   type="text" id="CNIC_NO" class="form-control" name="CNIC_NO" value="<?=$user['CNIC_NO']?>"  >

                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInput1" class="bmd-label-floating">CNIC Expiry
                                                    <span class="text-danger"><small>dd/mm/yyyy</small></span></label>
                                                <div class=" data-custon-pick" id="data_2">
                                                    <div class="input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        <input data-toggle="tooltip" title="CNIC Expiry Date"  type="text" id="CNIC_EXPIRY"  name="CNIC_EXPIRY" class="form-control" value="<?=getDateForView($user['CNIC_EXPIRY'])?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                    ?>



                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating"> Mobile Code
                                                <span class="text-danger">*</span></label>
                                            <select  class="js-example-basic-single form-control mb-3" name="MOBILE_CODE" id="MOBILE_CODE">
                                                <?php
                                                foreach ($countries as $country) {
                                                    $select = "";
                                                    if($country['PHONE_CODE']==$user['MOBILE_CODE']){
                                                        $select = "selected";
                                                    }
                                                    echo "<option value='{$country['PHONE_CODE']}' $select >{$country['COUNTRY_NAME']} &nbsp;&nbsp; {$country['PHONE_CODE']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating"> Mobile No
                                                <span class="text-danger">*</span></label>
                                            <input type="text" id="MOBILE_NO" class="form-control allow-mobile-number" name="MOBILE_NO" value="<?=$user['MOBILE_NO']?>"  >`
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating"> Phone No
                                                <span class="text-danger"></span></label>
                                            <input type="text" id="PHONE" class="form-control allow-mobile-number" name="PHONE" value="<?=$user['PHONE']?>"  >`
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Country
                                                <span class="text-danger">*</span></label>
                                            <br>

                                            <select   id="COUNTRY_ID" class="js-example-basic-single form-control " ONCHANGE="getProvinces(this.value)" name="COUNTRY_ID">
                                                <option value="0">--Choose--</option>
                                                <?php

                                                foreach ($countries as $country) {
                                                    $select = "";
                                                    if($country['COUNTRY_ID']==$user['COUNTRY_ID']){
                                                        $select = "selected";
                                                    }
                                                    echo "<option value='{$country['COUNTRY_ID']}' $select >{$country['COUNTRY_NAME']}</option>";
                                                }
                                                ?>

                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Province / State
                                                <span class="text-danger">*</span></label>
                                            <br>
                                            <select  id="PROVINCE_ID" class="js-example-basic-single form-control"  ONCHANGE="getDistrict(this.value)" name="PROVINCE_ID">
                                                <option value="0">--Choose--</option>


                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">District
                                                <span class="text-danger">*</span></label>
                                            <br>
                                            <select  id="DISTRICT_ID" class="js-example-basic-single form-control" ONCHANGE="getCity(this.value)" name="DISTRICT_ID">
                                                <option value="0">--Choose--</option>



                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">City
                                                <span class="text-danger">*</span></label>
                                            <br>
                                            <select  id="CITY_ID" class="js-example-basic-single form-control"  name="CITY_ID">
                                                <option value="0">--Choose--</option>


                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group res-mg-t-15">
                                            <label for="GENDER" class="bmd-label-floating">Area

                                                <span class="text-danger">*</span></label>

                                            <select name="U_R" id="U_R" class="form-control">
                                                <option value='0'>--choose--</option> ;
                                                <?php
                                                $selected = "";

                                                foreach($area as $k=>$boolg){
                                                    if($user['U_R']==$k)
                                                        echo "<option value='$k' selected>$boolg</option>" ;
                                                    else
                                                        echo "<option value='$k' >$boolg</option>" ;
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Zip / Postal Code
                                                <span class="text-danger"></span></label>
                                            <input type="text" id="ZIP_CODE" class="form-control allow-number" name="ZIP_CODE" value="<?=$user['ZIP_CODE']?>"  >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group res-mg-t-15">
                                            <label for="exampleInput1" class="bmd-label-floating">Home Address <small>Postal Address</small>
                                                <span class="text-danger">*</span></label>
                                            <textarea name="HOME_ADDRESS" id="HOME_ADDRESS" class="allow-address"  style="height:70px" rows="3"><?=$user['HOME_ADDRESS']?></textarea>

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group res-mg-t-15">
                                            <label for="exampleInput1" class="bmd-label-floating">Parmanent Address
                                                <span class="text-danger">*</span></label>
                                            <textarea name="PERMANENT_ADDRESS" id="PERMANENT_ADDRESS" class="allow-address"  style="height:70px" row="3"><?=$user['PERMANENT_ADDRESS']?></textarea>

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Place of Birth
                                                <span class="text-danger"></span></label>
                                            <input type="text" id="PLACE_OF_BIRTH" class="form-control allow-string" name="PLACE_OF_BIRTH" value="<?=$user['PLACE_OF_BIRTH']?>"  ></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">


                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating"> Email
                                                <span class="text-danger">*</span></label>
                                            <input  type="email" id="EMAIL" class="form-control" name="EMAIL" value="<?=$user['EMAIL']?>"  >

                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <h4>Gaurdian's / Sponser Information</h4>
                                <hr>
                                <div class="row">

                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Guardian Name
                                                <span class="text-danger">*</span></label>
                                            <input <?=$readonly?> type="text" id="GNAME" class="form-control allow-string" name="GNAME" value="<?=$guardian['FIRST_NAME']?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group res-mg-t-15">
                                            <label for="GENDER" class="bmd-label-floating">Relationship With Guardian

                                                <span class="text-danger">*</span></label>

                                            <select name="REL_GUARD" id="REL_GUARD" class="form-control">
                                                <option value='0'>--choose--</option> ;
                                                <?php
                                                $selected = "";

                                                foreach($REL_GUARD as $k=>$boolg){
                                                    if($guardian['RELATIONSHIP']==$k)
                                                        echo "<option value='$k' selected>$boolg</option>" ;
                                                    else
                                                        echo "<option value='$k' >$boolg</option>" ;
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group res-mg-t-15">
                                            <label for="U_R" class="bmd-label-floating">Occupation of Guardian

                                                <span class="text-danger">*</span></label>

                                            <select name="OCC_GUARD" id="OCC_GUARD" class="form-control">
                                                <option value='0'>--choose--</option> ;
                                                <?php
                                                $selected = "";

                                                foreach($OCC_GUARD as $k=>$boolg){
                                                    if($guardian['OCCUPATION']==$k)
                                                        echo "<option value='$k' selected>$boolg</option>" ;
                                                    else
                                                        echo "<option value='$k' >$boolg</option>" ;
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating"> Mobile Code
                                                <span class="text-danger">*</span></label>
                                            <select  class="js-example-basic-single form-control mb-3" name="GAURD_MOBILE_CODE" id="GAURD_MOBILE_CODE">
                                                <?php
                                                foreach ($countries as $country) {
                                                    $select = "";
                                                    $bool=true;
                                                    if($country['PHONE_CODE']==$guardian['MOBILE_CODE']){
                                                        $select = "selected";
                                                        $bool= false;
                                                    }else if($country['PHONE_CODE']=="0092"&&$bool){
                                                        $select = "selected";
                                                    }
                                                    echo "<option value='{$country['PHONE_CODE']}' $select >{$country['COUNTRY_NAME']} &nbsp;&nbsp; {$country['PHONE_CODE']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label for="exampleInput1" class="bmd-label-floating">Guardian's Mobile No
                                                <span class="text-danger">*</span></label>
                                            <input type="text" id="GAURD_MOBILE_NO" class="form-control allow-mobile-number" name="GAURD_MOBILE_NO" value="<?=$guardian['MOBILE_NO']?>"  >`
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group res-mg-t-15">
                                            <label for="exampleInput1" class="bmd-label-floating">Guardian Address
                                                <span class="text-danger">*</span></label>
                                            <textarea name="GAURD_HOME_ADDRESS" id="GAURD_HOME_ADDRESS" class="allow-address"  style="height:70px" rows="3"><?=$guardian['HOME_ADDRESS']?></textarea>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="form-group res-mg-t-15">
                            <img src="<?php echo base_url()."dash_assets/img/cp1.jpg"; ?>"   width="150px" height="150px">

                            <img src="<?php echo base_url()."dash_assets/img/cp2.jpg"; ?>"   width="150px" height="150px">


                            <img src="<?php echo base_url()."dash_assets/img/cp3.jpg"; ?>"   width="150px" height="150px">

                            <img src="<?php echo base_url()."dash_assets/img/correct-photo.jpeg"; ?>"   width="150px" height="150px">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group res-mg-t-15">
                            <label for="exampleInput1" class="bmd-label-floating">Profile Image
                                <span class="text-danger">*</span>
                            </label><br>
                            <?php

                            $image_path_default =base_url()."dash_assets/img/avatar/default-avatar.png";
                            $image_path = "";
                            if($user['PROFILE_IMAGE'] != ""){
                                $image_path_default = itsc_url().PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];
                                $image_path = itsc_url().PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];

                            }
                            ?>
                            <img src="<?php echo $image_path_default; ?>" alt="Profile" class="" id="profile-image-view"  width="150px" height="150px" name="profile-image-view" >
                           
                                <input <?= $readonly ?> type="file" name="profile_image" id="profile_image"
                                                        onchange="changeImage(this,'profile_image','profile-image-view',100)"
                                                        accept=".jpg,.png,.jpeg" value="<?php echo $image_path; ?>">
                                <input type="text" name="profile_image1" id="profile_image1"
                                       value="<?php echo $image_path; ?>" hidden>
                                <span class="text-danger">Image must be passport size with white background and image size should be less than 100KB</span>
                              
                        </div>
                    </div>

                </div>

                                <div class="row">
                                    <div class="col-lg-3">
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="payment-adress">
                                            <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light" id="save">Save</button>
                                        </div>
                                    </div>


                                </div>





                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="product-tab-list tab-pane fade" id="education">
                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="review-content-section">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">
                                                <center><h4 >Educational Information</h4></center>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">
                                                <button class='btn btn-success btn-md btn-round disab' id="add_qulification"><i class='fa fa-plus'></i> Add New Qualification</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="top-margin" id='qulification_form_view'>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="top-margin" id="qulification_table_view">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="payment-adress">
                                                <button  id="next_tab_btn" type="button"onclick = "next_tab('documents_tab')" class="btn btn-primary btn-lg waves-effect waves-light">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                   <div class="product-tab-list tab-pane fade" id="documents">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="review-content-section">
                                    <?=form_open('', 'id="document_upload" onsubmit="event.preventDefault();"');?>

                                    <div id="doc_form_msg"></div>
                                    <hr>
                                    <h3>Upload Supporting Documents</h3>
                                    <hr>
                                    <div class="row">
                                        <?php
                                        $condidtions = true;
                                        if($user['IS_CNIC_PASS']==='P'){
                                            ?>

                                            <div class="col-md-6">
                                                <div style="margin-top:35px">

                                                    <label for="exampleInput1" class="bmd-label-floating">Upload Passport Front Side
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <br>
                                                    <?php
                                                    $image_path = "";

                                                    $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                                                    if($user['PASSPORT_FRONT_IMAGE'] != ""){
                                                        $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['PASSPORT_FRONT_IMAGE'];
                                                        $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['PASSPORT_FRONT_IMAGE'];

                                                    }
                                                    ?>

                                                    <img src="<?php echo $image_path_default; ?>" alt="Passport Front Side " class="img-table-certificate" id="passport-front-image-view"  width="150px" height="150px" name="passport-front-image-view" >
                                                    <?php
                                                    if($condidtions) {
                                                        ?>
                                                        <input type="file" name="passport_front_image" id="passport_front_image"
                                                               onchange="changeImage(this,'passport_front_image','passport-front-image-view',500)"
                                                               accept=".jpg,.png,.jpeg">
                                                        <input type="text" name="passport_front_image1" id="passport_front_image1"
                                                               value="<?php echo $image_path; ?>" hidden>
                                                        <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div style="margin-top:35px">

                                                    <label for="exampleInput1" class="bmd-label-floating">Upload Passport Back Side
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <br>
                                                    <?php
                                                    $image_path = "";

                                                    $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                                                    if($user['PASSPORT_BACK_IMAGE'] != ""){
                                                        $image_path_default =itsc_url(). EXTRA_IMAGE_PATH.$user['PASSPORT_BACK_IMAGE'];
                                                        $image_path =itsc_url(). EXTRA_IMAGE_PATH.$user['PASSPORT_BACK_IMAGE'];

                                                    }
                                                    ?>

                                                    <img src="<?php echo $image_path_default; ?>" alt="Passport Back Side " class="img-table-certificate" id="passport-back-image-view"  width="150px" height="150px" name="passport-back-image-view" >
                                                    <?php
                                                    if($condidtions) {
                                                        ?>
                                                        <input type="file" name="passport_back_image" id="passport_back_image"                       onchange="changeImage(this,'passport_back_image','passport-back-image-view',500)" accept=".jpg,.png,.jpeg">
                                                        <input type="text" name="passport_back_image1" id="passport_back_image1" value="<?php echo $image_path; ?>" hidden>
                                                        <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <?php
                                        }
                                        else{
                                            ?>
                                            <div class="col-md-6">
                                                <div style="margin-top:35px">

                                                    <label for="exampleInput1" class="bmd-label-floating">Upload CNIC Front Side / Form B
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <br>
                                                    <?php
                                                    $image_path = "";

                                                    $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                                                    if($user['CNIC_FRONT_IMAGE'] != ""){
                                                        $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['CNIC_FRONT_IMAGE'];
                                                        $image_path =itsc_url(). EXTRA_IMAGE_PATH.$user['CNIC_FRONT_IMAGE'];

                                                    }
                                                    ?>

                                                    <img src="<?php echo $image_path_default; ?>" alt="Cnic Front Side " class="img-table-certificate" id="cnic-front-image-view"  width="150px" height="150px" name="cnic-front-image-view" >
                                                    <?php
                                                    if($condidtions) {
                                                        ?>
                                                        <input type="file" name="cnic_front_image" id="cnic_front_image"                       onchange="changeImage(this,'cnic_front_image','cnic-front-image-view',500)" accept=".jpg,.png,.jpeg">
                                                        <input type="text" name="cnic_front_image1" id="cnic_front_image1" value="<?php echo $image_path; ?>" hidden>
                                                        <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div style="margin-top:35px">

                                                    <label for="exampleInput1" class="bmd-label-floating">Upload CNIC Back Side / Form B
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <br>
                                                    <?php
                                                    $image_path = "";

                                                    $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                                                    if($user['CNIC_BACK_IMAGE'] != ""){
                                                        $image_path_default =itsc_url(). EXTRA_IMAGE_PATH.$user['CNIC_BACK_IMAGE'];
                                                        $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['CNIC_BACK_IMAGE'];

                                                    }
                                                    ?>
                                                    <img src="<?php echo $image_path_default; ?>" alt="Cnic Back Side " class="img-table-certificate" id="cnic-back-image-view"  width="150px" height="150px" name="cnic-back-image-view" >
                                                    <?php
                                                    if($condidtions) {
                                                        ?>
                                                        <input type="file" name="cnic_back_image" id="cnic_back_image"                       onchange="changeImage(this,'cnic_back_image','cnic-back-image-view',500)" accept=".jpg,.png,.jpeg">
                                                        <input type="text" name="cnic_back_image1" id="cnic_back_image1" value="<?php echo $image_path; ?>" hidden>
                                                        <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div style="margin-top:35px">

                                                <label for="exampleInput1" class="bmd-label-floating">Upload Domicile Certificate <span class="text-danger">*</span></label>
                                                <br>
                                                <?php
                                                $image_path = "";

                                                $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                                                if($user['DOMICILE_IMAGE'] != ""){
                                                    $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_IMAGE'];
                                                    $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_IMAGE'];

                                                }
                                                ?>

                                                <img src="<?php echo $image_path_default; ?>" alt="Domicile Image" class="img-table-certificate" id="domicile-image-view"  width="150px" height="150px" name="domicile-image-view" >
                                                <?php
                                                if($condidtions) {
                                                    ?>
                                                    <input type="file" name="domicile_image" id="domicile_image"                       onchange="changeImage(this,'domicile_image','domicile-image-view',500)" accept=".jpg,.png,.jpeg">
                                                    <input type="text" name="domicile_image1" id="domicile_image1" value="<?php echo $image_path; ?>" hidden>
                                                    <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div style="margin-top:35px">

                                                <label for="exampleInput1" class="bmd-label-floating">Upload PRC Form-C <span class="text-danger">*</span>
                                                </label>
                                                <br>
                                                <?php
                                                $image_path = "";

                                                $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                                                if($user['DOMICILE_FORM_C_IMAGE'] != ""){
                                                    $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_FORM_C_IMAGE'];
                                                    $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_FORM_C_IMAGE'];

                                                }
                                                ?>
                                                <img src="<?php echo $image_path_default; ?>" alt="Domicile Form-C Image" class="img-table-certificate" id="domicile-formc-image-view"  width="150px" height="150px" name="domicile-formc-image-view" >
                                                <?php
                                                if($condidtions) {
                                                    ?>
                                                    <input type="file" name="domicile_formc_image" id="domicile_formc_image"
                                                           onchange="changeImage(this,'domicile_formc_image','domicile-formc-image-view',500)"
                                                           accept=".jpg,.png,.jpeg">
                                                    <input type="text" name="domicile_formc_image1" id="domicile_formc_image1"
                                                           value="<?php echo $image_path; ?>" hidden>
                                                    <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                    </div>
                                    <br>
                                    <hr>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-3">
                                        </div>
                                        <?php
                                        if($condidtions) {
                                            ?>
                                            <div class=" col-lg-2">
                                                <div class="payment-adress">
                                                    <button onclick="updateDocument()" type="submit"
                                                            class="btn btn-primary waves-effect waves-light">Upload Documents
                                                    </button>
                                                </div>
                                            </div>
                                            <?php
                                        }

                                        ?>

                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>

                            function updateDocument(){

                                var form = $('#document_upload')[0];
                                // Create an FormData object
                                var data = new FormData(form);
                                data.append(csrfName, csrfHash);
                                data.append("action", 'update_dcoument');

                                $('.preloader').fadeIn(700);

                                jQuery.ajax({
                                    url: "<?=base_url()?>AdminApi/uploadDocuments/<?=$user['USER_ID']?>",
                                    type: "POST",
                                    enctype: 'multipart/form-data',
                                    processData: false,
                                    contentType: false,
                                    data: data,
                                    xhr: function() {
                                        var xhr = new window.XMLHttpRequest();
                                        xhr.upload.addEventListener("progress", function(evt) {
                                            if (evt.lengthComputable) {
                                                var percentComplete = (evt.loaded / evt.total) * 100;
                                                percentComplete = Math.round(percentComplete);
                                                $("#pre_text").html("<br><br><h3>Uploading "+percentComplete+"%</h3>");
                                                console.log(percentComplete);
                                            }
                                        }, false);
                                        return xhr;
                                    },
                                    success: function (data, status) {
                                        $('.preloader').fadeOut(700);
                                        $('input[name="csrf_form_token"]').val(data.csrfHash);
                                        csrfHash =data.csrfHash;
                                        $('#doc_form_msg').html("");
                                        alertMsg("Success",data.MESSAGE);

                                    },
                                    beforeSend:function (data, status) {

                                        $('#doc_form_msg').html('Loading...!');


                                    },
                                    error:function (data, status) {
                                        var value = data.responseJSON;

                                        alertMsg("Error",value.MESSAGE);
                                        if(value.csrfHash){
                                            $('input[name="csrf_form_token"]').val(value.csrfHash);
                                            csrfHash =value.csrfHash;
                                        }

                                        $('#doc_form_msg').html(value.MESSAGE);
                                        $('.preloader').fadeOut(700);



                                    },
                                });

                            }
                        </script>
                    </div>
                       <div class="product-tab-list tab-pane fade" id="challan">
                           <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="review-content-section">
                        <div id="dropzone1" class="pro-ad">
                            <div class="card">
                                <div class="card-header">
                                    <h1>Upload Paid Challan For <?=ucwords(strtolower($application['PROGRAM_TITLE']))?> Program in <?=ucwords(strtolower($application['NAME']))?></h1>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $hidden = array("APPLICATION_ID"=>$APPLICATION_ID);

                                    ?>

                            <!--                                        <form action="/upload" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="demo1-upload" novalidate="novalidate">-->

                            <?php
                           
                                echo form_open(base_url('AdminApi/challan_upload_handler'), ' enctype="multipart/form-data" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="challan_form "',$hidden);
                         

                            ?>

                            <div class="row">

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Bank Branch
                                            <span class="text-danger">*</span></label>
                                        <br>
                                              <select   id="BRANCH_ID" class="js-example-basic-single form-control "  name="BRANCH_ID">
                                            <option value="0">--Choose--</option>
                                            <?php

                                            foreach ($bank_branches as $bank_branch) {
                                                $select = "";
                                                if($application['BRANCH_ID']==$bank_branch['BRANCH_ID']){
                                                    $select = "selected";
                                                }
                                                echo "<option   value='{$bank_branch['BRANCH_ID']}'  $select>{$bank_branch['BRANCH_CODE']} &nbsp;&nbsp;{$bank_branch['BRANCH_NAME']}</option>";
                                            }
                                            ?>

                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Challan Paid Date
                                            <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>
                                        <div class="form-group data-custon-pick" id="data_2">
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <?php
                                                if($application['CHALLAN_DATE']){
                                                    $date = getDateForView($application['CHALLAN_DATE']);
                                                }else{
                                                    $date = date('d/m/Y');
                                                    $date="";
                                                }

                                                ?>
                                                <input  type="text" id="CHALLAN_PAID_DATE"  name="CHALLAN_PAID_DATE" class="form-control"  value="<?=$date?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Challan Number
                                            <span class="text-danger">*</span>
                                            <span class="text-danger" id="CHALLAN_NO_VIEW_MSG"></span>
                                        </label>
                                        <input  readonly value ="<?= $challan_no = str_pad($application['FORM_CHALLAN_ID'], 5, '0', STR_PAD_LEFT);?>"type="text" id="CHALLAN_NO" class="form-control allow-number" placeholder="CHALLAN NO" name="CHALLAN_NO" value="<?=($application['PAID']=='N'||$application['PAID']=='Y')?$application['FORM_CHALLAN_ID']:'';?>">


                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Challan Amount
                                            <span class="text-danger">*</span>
                                            <span class="text-danger" id="CHALLAN_AMOUNT_VIEW_MSG"></span>
                                        </label>
                                        <input readonly type="text"  value =" <?=$application['CHALLAN_AMOUNT']?>" id="CHALLAN_AMOUNT" class="form-control allow-number" placeholder="CHALLAN AMOUNT" name="CHALLAN_AMOUNT" value="<?=$application['PAID_AMOUNT']?>" >


                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group res-mg-t-15">
                                        <label for="exampleInput1" class="bmd-label-floating">Paid Challan Image
                                            <span class="text-danger">*</span>
                                        </label><br>
                                        <?php

                                        $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                                        $image_path = "";
                                        if($application['CHALLAN_IMAGE'] != ""){

                                            $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$application['CHALLAN_IMAGE'];
                                            $image_path = itsc_url(). EXTRA_IMAGE_PATH.$application['CHALLAN_IMAGE'];

                                        }
                                        ?>

                                        <img src="<?php echo $image_path_default; ?>" alt="CHALLAN IMAGE" id="challan-image-view"  class="img-table-certificate"  width="150px" height="150px" name="challan-image-view" >
                                        <input type="file" name="challan_image" id="challan_image"   onchange="changeImage(this,'challan_image','challan-image-view',500)" accept=".jpg,.png,.jpeg" value="<?php echo $image_path; ?>">
                                        <input type="text" name="challan_image1" id="challan_image1" value="<?php echo $image_path; ?>" hidden>
                                        <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>

                                    </div>
                                </div>
                            </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>
                                       
                                        <div class="col-lg-4">
                                            <div class="payment-adress">
                                                <button type="submit"
                                                        class="btn btn-primary btn-lg waves-effect waves-light">Save
                                                </button>
                                            </div>
                                        </div>
                                      
                                       
                                    </div>
                                    <?php
                                  
                                    echo "</form>";
                               
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
    var FORM_CHALLAN_ID = <?=$application['FORM_CHALLAN_ID']?>;
    var CHALLAN_AMOUNT = <?=$application['CHALLAN_AMOUNT']?>;

    $( '#CHALLAN_NO' ).keyup(function() {
       if(FORM_CHALLAN_ID==$( '#CHALLAN_NO' ).val()){
           $( '#CHALLAN_NO_VIEW_MSG' ).html("");
       }else{
           $( '#CHALLAN_NO_VIEW_MSG' ).html("INVALID CHALLAN NO");
       }
    });
    $( '#CHALLAN_AMOUNT' ).keyup(function() {
        if(CHALLAN_AMOUNT==$( '#CHALLAN_AMOUNT' ).val()){
            $( '#CHALLAN_AMOUNT_VIEW_MSG' ).html("");
        }else{
            $( '#CHALLAN_AMOUNT_VIEW_MSG' ).html("INVALID CHALLAN AMOUNT");
        }
    });
   
    function check_validtion_of_challan(){
        window.location.href = "<?=base_url()?>form/check_validation_and_challan";
    }
</script>
<style>
    .select2-container--default .select2-results__option[aria-disabled=true] {
        color: #f90000;
    }
    .btn-success {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;
    }
</style>
                        </div>
                    </div>
                    <!--                    <div class="product-tab-list tab-pane fade" id="experiances">-->
                    <!--                        --><?php //  require_once "profile_section/experiances.php";?>
                    <!--                    </div>-->
                </div>
                <div id="alert_msg_for_ajax_call"></div>
            </div>
        </div>
    </div>

</div>

<script>
    function getProvinces(country_id){
        if(country_id>0){
            $("#PROVINCE_ID").html("<option value='0'>--Choose--</option>");
            jQuery.ajax({
                url: "<?=base_url()?>api/getProvinceByCountryId?country_id="+country_id,
                async:true,
                success: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("");

                    data.forEach(function(item, index) {
                        $("#PROVINCE_ID").append(new Option(item.PROVINCE_NAME, item.PROVINCE_ID));
                    });
                    let PROVINCE_ID = <?=$user['PROVINCE_ID']?>;
                    if(PROVINCE_ID){
                        $('#PROVINCE_ID').val(PROVINCE_ID);
                    }



                },
                beforeSend:function (data, status) {


                    $('#alert_msg_for_ajax_call').html("LOADING...!");
                },
                error:function (data, status) {
                    alertMsg("Error",data.responseText);
                    $('#alert_msg_for_ajax_call').html("Something went worng..!");
                },
            });
        }else{
            console.log("error");
        }
    }
    function getDistrict(province_id){
        if(province_id>0){
            $("#DISTRICT_ID").html("<option value='0'>--Choose--</option>");
            jQuery.ajax({
                url: "<?=base_url()?>api/getDistrictByProvinceId?province_id="+province_id,
                async:true,
                success: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("");

                    data.forEach(function(item, index) {
                        $("#DISTRICT_ID").append(new Option(item.DISTRICT_NAME, item.DISTRICT_ID));
                    });

                    let DISTRICT_ID = <?=$user['DISTRICT_ID']?>;
                    if(DISTRICT_ID){
                        $('#DISTRICT_ID').val(DISTRICT_ID);
                    }

                },
                beforeSend:function (data, status) {


                    $('#alert_msg_for_ajax_call').html("LOADING...!");
                },
                error:function (data, status) {
                    alertMsg("Error",data.responseText);
                    $('#alert_msg_for_ajax_call').html("Something went worng..!");
                },
            });
        }else{
            console.log("error");
        }
    }
    function getCity(district_id){
        if(district_id>0){
            $("#CITY_ID").html("<option value='0'>--Choose--</option>");
            jQuery.ajax({
                url: "<?=base_url()?>api/getCityByDistrictId?district_id="+district_id,
                async:true,
                success: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("");

                    data.forEach(function(item, index) {
                        $("#CITY_ID").append(new Option(item.CITY_NAME, item.CITY_ID));
                    });

                    let CITY_ID = <?=$user['CITY_ID']?$user['CITY_ID']:0?>;
                    if(CITY_ID){
                        $('#CITY_ID').val(CITY_ID);
                    }

                },
                beforeSend:function (data, status) {


                    $('#alert_msg_for_ajax_call').html("LOADING...!");
                },
                error:function (data, status) {
                    alertMsg("Error",data.responseText);
                    $('#alert_msg_for_ajax_call').html("Something went worng..!");
                },
            });
        }else{
            console.log("error");
        }
    }
    <?php
    echo "getProvinces('{$user['COUNTRY_ID']}');";
    echo "getDistrict('{$user['PROVINCE_ID']}');";
    echo "getCity('{$user['DISTRICT_ID']}');";
    ?>

   //var is_profile = fasle;
   //  $('#base_profile_form').submit(function (event) {
$("#save").on('click',function () {
    if(confirm("Are You Sure?\nDo you want to update information")) {
        event.preventDefault();
        var form = $('#base_profile_form')[0];
        var data = new FormData(form);
        $('.preloader').fadeIn(700);
        jQuery.ajax({
            url: "<?=base_url()?>AdminPanel/basic_info_handler",
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            data: data,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        percentComplete = Math.round(percentComplete);
                        $("#pre_text").html("<br><br><h3>Uploading "+percentComplete+"%</h3>");
                        console.log(percentComplete);
                    }
                }, false);
                return xhr;
            },
            success: function (data, status) {
                $('.preloader').fadeOut(700);
                //$('input[name="csrf_form_token"]').val(data.csrfHash);
                $('#alert_msg_for_ajax_call').html("");

                 alertMsg("Success",data.MESSAGE);
                //console.log(data.MESSAGE);


            },
            beforeSend:function (data, status) {


                $('#alert_msg_for_ajax_call').html("LOADING...!");
            },
            error:function (data, status) {
                var value = data.responseJSON;
                 alertMsg("Error",value.MESSAGE);
                //alert("Error"+status);
                //$('input[name="csrf_form_token"]').val(value.csrfHash);
                $('#alert_msg_for_ajax_call').html(value.MESSAGE);
                //console.log(value.MESSAGE);
                $('.preloader').fadeOut(700);
            },
        });
    }
    });
</script>

<script>




        function getQualification(){

            callAjax("<?=base_url()?>AdminApi/apiGetQualificationList/<?=$user['USER_ID']?>","qulification_table_view");
        }






    function deleteQualification(id){
        if(confirm("Are You Sure?\nDo You want to delete your qualification..!")){

            $('.preloader').fadeIn(700);
            var data = new FormData();
            data.append("qualification_id", id);

            data.append(csrfName, csrfHash);
            jQuery.ajax({
                url: "<?=base_url()?>AdminApi/apiDeleteQualification/<?=$user['USER_ID']?>",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: data,
                success: function (data, status) {

                    $('.preloader').fadeOut(700);
                    $('#qul_form_msg').html("");
                    alertMsg("Success",data.MESSAGE);
                    csrfHash = data.csrfHash;
                    getQualification();

                },
                beforeSend:function (data, status) {


                    $('#qul_form_msg').html("Loading...!");



                },
                error:function (data, status) {
                    var value = data.responseJSON;

                    alertMsg("Error",value.MESSAGE);
                    $('#qul_form_msg').html(value.MESSAGE);
                    csrfHash = value.csrfHash;
                    $('.preloader').fadeOut(700);
                    getQualification();


                },
            });
        }
    }

    function addInst() {
        let ORGANIZATION_ID  = $("#ORGANIZATION_ID").val();
        if(ORGANIZATION_ID<=0){
            $('#inst_msg').html("First Select Organization..!");
            return;
        }
        let institute_name  = $("#institute_name").val();
        if(institute_name){
            if(confirm("Are You Sure?\nDo You want to add institute name..!")){

                $('.preloader').fadeIn(700);
                var data = new FormData();
                data.append("institute", institute_name);
                data.append("org_id", ORGANIZATION_ID);

                data.append(csrfName, csrfHash);
                jQuery.ajax({
                    url: "<?=base_url()?>AdminApi/addInstituteForQualification",
                    type: "POST",
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (data, status) {

                        $('.preloader').fadeOut(700);
                        $('#qul_form_msg').html("");
                        alertMsg("Success",data.MESSAGE);
                        csrfHash = data.csrfHash;
                        $("#add_new_inst").hide();
                        getInstituteByOrgId();

                    },
                    beforeSend:function (data, status) {


                        $('#qul_form_msg').html("Loading...!");



                    },
                    error:function (data, status) {
                        var value = data.responseJSON;

                        alertMsg("Error",value.MESSAGE);
                        $('#qul_form_msg').html(value.MESSAGE);
                        csrfHash = value.csrfHash;
                        $('.preloader').fadeOut(700);



                    },
                });
            }




        }else{
            $("#institute_name").attr('style',"border-color:red");
        }


    }

    function addOrg() {
        let org_name  = $("#org_name").val();
        if(org_name){
            if(confirm("Are You Sure?\nDo You want to add Organization name..!")){

                $('.preloader').fadeIn(700);
                var data = new FormData();
                data.append("org_name", org_name);

                data.append(csrfName, csrfHash);
                jQuery.ajax({
                    url: "<?=base_url()?>AdminApi/addOrganizationForQualification",
                    type: "POST",
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (data, status) {

                        $('.preloader').fadeOut(700);
                        $('#qul_form_msg').html("");
                        alertMsg("Success",data.MESSAGE);
                        csrfHash = data.csrfHash;
                        $("#add_new_org").hide();
                        getOrganization();

                    },
                    beforeSend:function (data, status) {


                        $('#qul_form_msg').html("Loading...!");



                    },
                    error:function (data, status) {
                        var value = data.responseJSON;

                        alertMsg("Error",value.MESSAGE);
                        $('#qul_form_msg').html(value.MESSAGE);
                        csrfHash = value.csrfHash;
                        $('.preloader').fadeOut(700);



                    },
                });
            }




        }else{
            $("#org_name").attr('style',"border-color:red");
        }


    }

    function getOrganization(){
        callAjax("<?=base_url()?>AdminApi/apiGetOrganization",'ORGANIZATION_ID','msg_alert');
    }

    function getInstituteByOrgId(){
        if(checkOrganization()){
            let ORGANIZATION_ID  = $("#ORGANIZATION_ID").val();
            query_string = "ORG_ID="+ORGANIZATION_ID;
            callAjax("<?=base_url()?>AdminApi/apiGetInstituteByOrgId?"+query_string,'INSTITUTE_ID','msg_alert');
        }
    }

    function checkInstitute(){
        let INSTITUTE_ID  = $("#INSTITUTE_ID").val();
        INSTITUTE_ID =Number(INSTITUTE_ID);
        if(INSTITUTE_ID===-1){
            $("#add_new_inst").show();
        }else{
            $("#inst_msg").html('');
            $("#add_new_inst").hide();
            $("#institute_name").val(null);
        }
    }

    function checkOrganization(){
        let ORGANIZATION_ID  = $("#ORGANIZATION_ID").val();
        ORGANIZATION_ID =Number(ORGANIZATION_ID);
        if(ORGANIZATION_ID===-1){
            $("#add_new_org").show();
            return false;
        }else{
            $("#org_msg").html('');
            $("#add_new_org").hide();
            $("#org_name").val(null);
            return true;
        }
    }

    function checkIsDeclare() {
        if($('#result_not_declare').is(':checked')){
            $('#RESULT_DATE').prop('disabled',true);
            $('#RESULT_DATE').val(null);

        }else{
            $('#RESULT_DATE').prop('disabled',false);

        }
    }

    function checkPercentage(){
        let TOTAL_MARKS = $('#TOTAL_MARKS').val();
        let OBTAINED_MARKS = $('#OBTAINED_MARKS').val();
        if(isNaN(TOTAL_MARKS)){
            $('#view_total_mark_error').html('Total Marks Invalid');
            $('#TOTAL_MARKS').val(0);
            $('#view_per').html('');
            return false;
        }
        if(isNaN(OBTAINED_MARKS)){
            $('#view_obtained_mark_error').html('Obtained Marks Invalid');
            $('#OBTAINED_MARKS').val(0);
            $('#view_per').html('');
            return false;
        }
        TOTAL_MARKS=  Number(TOTAL_MARKS);
        OBTAINED_MARKS =  Number(OBTAINED_MARKS);
        if(TOTAL_MARKS<100){
            $('#view_total_mark_error').html('Total Marks Invalid.Enter Your Total Marks');
            $('#view_per').html('');
            return false;
        }
        if(TOTAL_MARKS===0 || TOTAL_MARKS<OBTAINED_MARKS){
            $('#view_total_mark_error').html('Total Marks Invalid');
            $('#view_per').html('');
            return false;
        }else{
            $('#view_total_mark_error').html('');
        }
        if(OBTAINED_MARKS===0){
            $('#view_obtained_mark_error').html('Obtained Marks Invalid');
            $('#view_per').html('');
            return false;

        }else{
            $('#view_obtained_mark_error').html('');
        }
        let per = OBTAINED_MARKS/TOTAL_MARKS*100;
        if(per>100||per<0){
            $('#view_per').html('Invalid Percentage');
            return false;
        }else{
            per = per.toFixed(2);
            $('#view_per').html(per+'%');
        }

    }

    function  cancleQualificaion() {
        $('.disab').show();
        $('#qulification_form_view').html('');
    }

    function getDiscipline(degree_id) {

        let query_string = "action=DISCIPLINE&PROG_TYPE_ID=";
        let program_type_id = <?=$application['PROGRAM_TYPE_ID']?$application['PROGRAM_TYPE_ID']:0 ?>;
        let YEAR = <?=date('Y')?>;
        let  last = 0;
        if(degree_id>0){
            query_string +="&DEGREE_ID="+degree_id;
            if(program_type_id==1 && degree_id==2){
                last  = YEAR-2;
            }
            if(program_type_id==1 && degree_id==3){
                last  = YEAR;
            }
            if(program_type_id==2 && degree_id==2){
                last = YEAR-5;
            }
            if(program_type_id==2 && degree_id==3){
                last  = YEAR-3;
            }
            if(program_type_id==2 && degree_id==4){
                last  = YEAR-1;
            }
            if(program_type_id==2 && degree_id==5){
                last  = YEAR-1;
            }
            if(program_type_id==2 && degree_id==6){
                last  = YEAR-1;
            }
            let string="";
            for(let i=last;i>=1770;i--){
                string+="<option value='"+i+"'>"+i+"</option>";
            }
            $('#PASSING_YEAR').html(string);
        }else{
            $('#DISTRICT_ID').html(" <option value='0'>--Choose--</option>");
            return;
        }
        callAjax("<?=base_url()?>AdminApi/apiGetDisciplineById?"+query_string,'DISCIPLINE_ID','cus_msg');

    }
        $('#add_qulification').click(function (event) {
            event.preventDefault();
            let program_type_id = <?=$application['PROGRAM_TYPE_ID']?$application['PROGRAM_TYPE_ID']:0 ?>;

            callAjax("<?=base_url()?>AdminApi/apiGetAddQualificationForm?program_type_id="+program_type_id,"qulification_form_view");
            $('.js-example-basic-single').select2();
            $('.select2').attr('style','width:100%');
            $('.disab').hide();


        });

        $( '.img-table-certificate' ).click(function() {
            alertImage('Image',$(this).attr('src'));
        });


        function editQualification(id){
            let program_type_id = <?=$application['PROGRAM_TYPE_ID']?$application['PROGRAM_TYPE_ID']:0 ?>;
            callAjax("<?=base_url()?>AdminApi/apiGetEditQualificationForm/<?=$user['USER_ID']?>?qualification_id="+id+"&program_type_id="+program_type_id,'qulification_form_view');
            $('.js-example-basic-single').select2();
            $('.select2').attr('style','width:100%');
            $('.disab').hide();
        }

        function deleteQulification(id){
            if(confirm("Are You Sure?\nDo You want to delete your qualification..!")){
                $('.preloader').fadeIn(700);
                var data = new FormData();
                data.append("action", 'delete_qualification');
                data.append("QUAL_ID", id);
                <?php
                $res = getcsrf($this);
                ;
                $res['csrfHash'];
                ?>
                data.append("<?=$res['csrfName']?>", <?=$res['csrfName']?>);
                jQuery.ajax({
                    url: "<?=base_url()?>AdminApi/apiDeleteQualification/<?=$user['USER_ID']?>",
                    type: "POST",
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function (data, status) {

                        $('.preloader').fadeOut(700);
                        $('#qul_form_msg').html("");
                        alertMsg("Success",data.MESSAGE);
                        getQualification();

                    },
                    beforeSend:function (data, status) {


                        $('#qul_form_msg').html("Loading...!");



                    },
                    error:function (data, status) {
                        var value = data.responseJSON;

                        alertMsg("Error",value.MESSAGE);
                        $('#qul_form_msg').html(value.MESSAGE);
                        $('.preloader').fadeOut(700);
                        getQualification();


                    },
                });
            }
        }
    getQualification();
</script>
