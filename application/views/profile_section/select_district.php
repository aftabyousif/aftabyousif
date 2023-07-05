<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 9/18/2020
 * Time: 1:27 PM
 */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="review-content-section">
            <div id="dropzone1" class="pro-ad">
                <!--                                        <form action="/upload" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="demo1-upload" novalidate="novalidate">-->

                <form action="<?=base_url()."Advertisement/select_district_handler"?>" method="post" onsubmit="return confirm('Are you sure?\nOnce you selected district can not be changed.')">
 <div class="login-social-inner">
                       <a style="float: right;" href="<?=base_url().'logout'?>" class="button btn-social basic-ele-mg-b-10 twitter span-left"> <span><i class="fa fa-power-off"></i></span> Logout </a>
                       </div>
                    <div class="container" style="padding: 40px;
    background: white;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-danger text-big">
                            <h3>Please select domicile's district very carefully once you selected domicile's district it can not be changed</h3>
                            </div>
                        </div>
                        <br>
                        <br>
                <div class="row">

                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Country
                                <span class="text-danger">*</span></label>
                            <br>

                            <select  disabled id="COUNTRY_ID" class="js-example-basic-single form-control " ONCHANGE="getProvinces(this.value)" name="COUNTRY_ID">
                                <option value="0">--Choose--</option>
                                <?php
                                $COUNTRY_ID = $user['COUNTRY_ID']?$user['COUNTRY_ID']:160;
                                foreach ($countries as $country) {
                                    $select = "";
                                    if($country['COUNTRY_ID']==$COUNTRY_ID){
                                        $select = "selected";
                                    }
                                    echo "<option value='{$country['COUNTRY_ID']}' $select >{$country['COUNTRY_NAME']}</option>";
                                }
                                ?>

                            </select>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Province / State
                                <span class="text-danger">*</span></label>
                            <br>
                            <select   id="PROVINCE_ID" class="js-example-basic-single form-control"  ONCHANGE="getDistrict(this.value)" name="PROVINCE_ID">
                                <option value="0">--Choose--</option>


                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating"> Domicile District
                                <span class="text-danger">*</span></label>
                            <br>
                            <select  id="DISTRICT_ID" class="js-example-basic-single form-control"  name="DISTRICT_ID">
                                <option value="0">--Choose--</option>



                            </select>
                        </div>
                    </div>
                </div>
                        <br><br><br>
                <div class="row">
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-2">
                        <div class="payment-adress">
                            <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light">Save</button>
                        </div>
                    </div>

                    <div class="col-lg-2">
                       
                    </div>
                </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    <?php
    if($user['COUNTRY_ID']){
        $COUNTRY_ID = $user['COUNTRY_ID'];
    }else{
        $COUNTRY_ID = 160;
    }
    if($user['PROVINCE_ID']){
        $PROVINCE_ID= $user['PROVINCE_ID'];
    }else{
        $PROVINCE_ID = 0;
    }
    ?>
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
                    let PROVINCE_ID = <?=$PROVINCE_ID?>;
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

                    let DISTRICT_ID = <?=$user['DISTRICT_ID']?$user['DISTRICT_ID']:0?>;
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

    <?php

    echo "getProvinces('{$COUNTRY_ID}');";
    echo "getDistrict('{$PROVINCE_ID}');";

    ?>
    //var is_profile = fasle;

</script>

