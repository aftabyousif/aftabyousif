<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="review-content-section">
            <div id="dropzone1" class="pro-ad">
                <!--                                        <form action="/upload" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="demo1-upload" novalidate="novalidate">-->
                <?=form_open('', 'class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="base_profile_form"');?>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Prefix
                                <span class="text-danger">*</span></label>
                            <select  class=" form-control mb-3" name="PREFIX_ID" id="PREFIX_ID">
                                <?php
                                echo "<option value='0' >--Choose Title--</option>";
                                foreach ($prefixs as $p) {
                                    $select = "";
                                    if($p['PREFIX_ID']==$user['PREFIX_ID']){
                                        $select = "selected";
                                    }
                                    echo "<option value='{$p['PREFIX_ID']}' $select >{$p['PREFIX']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Full Name
                                <span class="text-danger">*</span></label>
                            <input <?=$readonly?> type="text" id="FIRST_NAME" class="form-control allow-string" placeholder="Full Name" name="FIRST_NAME" value="<?=$user['FIRST_NAME']?>">
                            <input type="text" id="USER_ID" class="" name="USER_ID" value="<?=$user['USER_ID']?>" hidden>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Father's Name
                                <span class="text-danger">*</span></label>
                            <input <?=$readonly?> type="text" id="FNAME" class="form-control allow-string" name="FNAME" value="<?=$user['FNAME']?>"  >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating"> Email
                                <span class="text-danger">*</span></label>
                            <input readonly type="email" id="EMAIL" class="form-control" name="EMAIL" value="<?=$user['EMAIL']?>"  >

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Surname
                                <span class="text-danger">*</span></label>
                            <input <?=$readonly?> type="text" id="LAST_NAME" class="form-control allow-string" name="LAST_NAME" value="<?=$user['LAST_NAME']?>"  >

                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Date Of Birth
                                <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>
                            <div class="form-group data-custon-pick" id="data_2">
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input <?=$readonly?> type="text" id="DATE_OF_BIRTH"  name="DATE_OF_BIRTH" class="form-control" value="<?=getDateForView($user['DATE_OF_BIRTH'])?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">

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
                                <input  readonly type="text" id="CNIC_NO" class="form-control" name="CNIC_NO" value="<?=$user['CNIC_NO']?>"  >

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
                            <label for="exampleInput1" class="bmd-label-floating">Place of Birth
                                <span class="text-danger"></span></label>
                            <input type="text" id="PLACE_OF_BIRTH" class="form-control allow-string" name="PLACE_OF_BIRTH" value="<?=$user['PLACE_OF_BIRTH']?>"  ></div>
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
                                <span class="text-danger"></span></label>
                            <br>
                            <select  id="PROVINCE_ID" class="js-example-basic-single form-control"  ONCHANGE="getDistrict(this.value)" name="PROVINCE_ID">
                                <option value="0">--Choose--</option>


                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">District
                                <span class="text-danger"></span></label>
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
                                <span class="text-danger"></span></label>
                            <br>
                            <select  id="CITY_ID" class="js-example-basic-single form-control"  name="CITY_ID">
                                <option value="0">--Choose--</option>


                            </select>

                        </div>
                    </div>
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
                                <span class="text-danger"></span></label>
                            <textarea name="PERMANENT_ADDRESS" id="PERMANENT_ADDRESS" class="allow-address"  style="height:70px" row="3"><?=$user['PERMANENT_ADDRESS']?></textarea>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label for="BLOOD_GROUP" class="bmd-label-floating">Blood Group

                                <span class="text-danger"></span></label>

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
                                $blood_groups=array('M'=>"MALE","F"=>"FEMALE","O"=>"OTHER");
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
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="form-group res-mg-t-15">
                            <label for="exampleInput1" class="bmd-label-floating">Profile Image
                                <span class="text-danger">*</span>
                            </label><br>
                            <?php

                            $image_path_default =base_url()."dash_assets/img/avatar/default-avatar.png";
                            $image_path = "";
                            if($user['PROFILE_IMAGE'] != ""){
                                $image_path_default = PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];
                                $image_path = PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];

                            }
                            ?>
                            <img src="<?php echo $image_path_default; ?>" alt="Profile" class="" id="profile-image-view"  width="150px" height="150px" name="profile-image-view" >
                            <input <?=$readonly?> type="file" name="profile_image" id="profile_image"   onchange="changeImage(this,'profile_image','profile-image-view',50)" accept=".jpg,.png,.jpeg" value="<?php echo $image_path; ?>">
                            <input type="text" name="profile_image1" id="profile_image1" value="<?php echo $image_path; ?>" hidden>
                            <span class="text-danger">Image must be passport size with white background and image size should be less than 50KB</span>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="payment-adress">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        </div>
                    </div>
                </div>



                </form>
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

                    let CITY_ID = <?=$user['CITY_ID']?>;
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

    $('#base_profile_form').submit(function (event) {
        event.preventDefault();
        var form = $('#base_profile_form')[0];
        var data = new FormData(form);
        $('.preloader').fadeIn(700);
        jQuery.ajax({
            url: "<?=base_url()?>candidate/updateProfile",
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
                $('#alert_msg_for_ajax_call').html("");
                alertMsg("Success",data.MESSAGE);

            },
            beforeSend:function (data, status) {


                $('#alert_msg_for_ajax_call').html("LOADING...!");
            },
            error:function (data, status) {
                var value = data.responseJSON;

                alertMsg("Error",value.MESSAGE);
                $('input[name="csrf_form_token"]').val(value.csrfHash);
                $('#alert_msg_for_ajax_call').html(value.MESSAGE);
                $('.preloader').fadeOut(700);
            },
        });
    });
</script>
