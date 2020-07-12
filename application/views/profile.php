<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 4:22 PM
 */

?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="card">

        <div class="admintab-wrap edu-tab1 mg-t-30">
            <ul class="nav nav-tabs custom-menu-wrap custon-tab-menu-style1">


                    <li class="active">
                        <a data-toggle="tab" href="#basic_profile" id="basic_profile_link" aria-expanded="true">

                            <span class="educate-icon educate-student icon-wrap"></span> Profile

                        </a>
                    </li>
                <li class="">
                    <a data-toggle="tab" href="#education" id="education_link" aria-expanded="false">

                        <span class="educate-icon educate-department icon-wrap"></span> Education

                    </a>
                </li>


            </ul>
            <div class="tab-content">


                    <div id="basic_profile" class="tab-pane animated custon-tab-style1 active">
                        <div class="card-body">
                            <div class="card-header text-center">
                                <h3>Personal Information</h3>
                            </div>

                            <div class="row"  >

                                <div class="col-md-1">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Prefix
                                            <span class="text-danger">*</span></label>
                                        <select  class=" form-control mb-3" name="PREFIX_ID" id="PREFIX_ID">
                                            <?php
                                            $PREFIXS = getDataStaticQuery("*","configurations","ACTIVE = 1 AND DESCRIPTION ='PREFIXS'");
                                            if(count($PREFIXS)>0){
                                                $v = json_decode($PREFIXS[0]['VALUE'],true);

                                                echo "<option value='0' >--Choose Title--</option>";
                                                foreach ($v as $p) {
                                                    $select = "";
                                                    if($p['PREFIX_ID']==$user['PREFIX_ID']){
                                                        $select = "selected";
                                                    }
                                                    echo "<option value='{$p['PREFIX_ID']}' $select >{$p['PREFIX']}</option>";
                                                }
                                            }

                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Full Name
                                            <span class="text-danger">*</span></label>
                                        <input <?=$readonly?> type="text" id="FIRST_NAME" class="form-control" name="FIRST_NAME" value="<?=$user['FIRST_NAME']?>"  >
                                        <input type="text" id="USER_ID" class="" name="USER_ID" value="<?=$user['USER_ID']?>" hidden>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Surname
                                            <span class="text-danger">*</span></label>
                                        <input <?=$readonly?> type="text" id="LAST_NAME" class="form-control" name="LAST_NAME" value="<?=$user['LAST_NAME']?>"  >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating"> Email
                                            <span class="text-danger">*</span></label>
                                        <input type="text" id="EMAIL" class="form-control" name="EMAIL" value="<?=$user['EMAIL']?>"  >

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Date Of Birth
                                            <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>
                                        <div class="form-group data-custon-pick" id="data_2">
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input <?=$readonly?> type="text" id="DATE_OF_BIRTH"  name="DATE_OF_BIRTH" class="form-control" value="<?=getDateForView($user['DATE_OF_BIRTH'])?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row"  >

                                <?php
                                if($user['IS_CNIC_PASS']==='P'){
                                    ?>
                                    <div class="col-md-2">
                                        <div class="top-margin">
                                            <label for="exampleInput1" class="bmd-label-floating">Passport No
                                                <span class="text-danger">*</span></label>
                                            <input readonly type="text" id="PASSPORT_NO" class="form-control" name="PASSPORT_NO"
                                                   value="<?= $user['PASSPORT_NO'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="top-margin">
                                            <label for="exampleInput1" class="bmd-label-floating">Passport Expiry
                                                <span class="text-danger">*</span></label>
                                            <div class="form-group data-custon-pick" id="data_2">
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
                                    <div class="col-md-2">
                                        <div class="top-margin">
                                            <label for="exampleInput1" class="bmd-label-floating">CNIC / Form-B
                                                <span class="text-danger">*</span></label>
                                            <input  type="text" id="CNIC_NO" class="form-control" name="CNIC_NO" value="<?=$user['CNIC_NO']?>"  >
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="top-margin">
                                            <label for="exampleInput1" class="bmd-label-floating">CNIC Expiry
                                                <span class="text-danger"></span></label>
                                            <div class="form-group data-custon-pick" id="data_2">
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
                                <div class="col-md-2">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Father's Name
                                            <span class="text-danger"></span></label>
                                        <input <?=$readonly?> type="text" id="FNAME" class="form-control" name="FNAME" value="<?=$user['FNAME']?>"  >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating"> Mobile Code
                                            <span class="text-danger">*</span></label>
                                        <select  class="js-example-basic-single form-control mb-3" name="MOBILE_CODE" id="MOBILE_CODE">
                                            <?php
                                            $countries = getDataStaticQuery("*","countries","ACTIVE = 1");
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
                                <div class="col-md-2">
                                    <div class="top-margin">

                                        <label for="exampleInput1" class="bmd-label-floating"> Mobile No
                                            <span class="text-danger">*</span></label>
                                        <input type="text" id="MOBILE_NO" class="form-control" name="MOBILE_NO" value="<?=$user['MOBILE_NO']?>"  >

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Place of Birth
                                            <span class="text-danger"></span></label>
                                        <input type="text" id="PLACE_OF_BIRTH" class="form-control" name="PLACE_OF_BIRTH" value="<?=$user['PLACE_OF_BIRTH']?>"  >
                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Country
                                            <span class="text-danger"></span></label>
                                        <br>

                                        <select  disabled id="COUNTRY_ID" class="js-example-basic-single form-control " ONCHANGE="getProvinces(this.value)" name="COUNTRY_ID">
                                            <option value="0">--Choose--</option>
                                            <?php
                                            $countries = getDataStaticQuery("*","countries","1");
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
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Province / State
                                            <span class="text-danger"></span></label>
                                        <br>
                                        <select  id="PROVINCE_ID" class="js-example-basic-single form-control"  ONCHANGE="getDistrict(this.value)" name="PROVINCE_ID">
                                            <option value="0">--Choose--</option>


                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">District
                                            <span class="text-danger"></span></label>
                                        <br>
                                        <select  id="DISTRICT_ID" class="js-example-basic-single form-control" ONCHANGE="getCity(this.value)" name="DISTRICT_ID">
                                            <option value="0">--Choose--</option>



                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">City
                                            <span class="text-danger"></span></label>
                                        <br>
                                        <select  id="CITY_ID" class="js-example-basic-single form-control"  name="CITY_ID">
                                            <option value="0">--Choose--</option>


                                        </select>

                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Home Address <small>Postal Address</small>
                                            <span class="text-danger">*</span></label>
                                        <textarea name="HOME_ADDRESS" id="HOME_ADDRESS" CLASS="form-control" cols="30" rows="3"><?=$user['HOME_ADDRESS']?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Parmanent Address
                                            <span class="text-danger"></span></label>
                                        <textarea name="PERMANENT_ADDRESS" id="PERMANENT_ADDRESS" CLASS="form-control" cols="30" rows="3"><?=$user['PERMANENT_ADDRESS']?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="top-margin">
                                        <label for="exampleInput1" class="bmd-label-floating">Zip / Postal Code
                                            <span class="text-danger"></span></label>
                                        <input type="text" id="ZIP_CODE" class="form-control" name="ZIP_CODE" value="<?=$user['ZIP_CODE']?>"  >

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="top-margin">
                                        <label for="BLOOD_GROUP" class="bmd-label-floating">Blood Group

                                            <span class="text-danger"></span></label>

                                        <select name="BLOOD_GROUP" id="BLOOD_GROUP" class="form-control">
                                            <?php
                                            $selected = "";
                                            $blood_groups=array("A+","A-","B+","B-","O+","O-","AB+","AB-");
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
                                <div class="col-md-2">
                                    <div class="top-margin">
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

                            </div>


                            <div class="row">
                                <div class="col-md-3">
                                    <div class="top-margin">
                                        <button  class='btn btn-primary btn-md' >Save</button>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="top-margin">


                                    </div>
                                </div>
                            </div>

                        </div>

                        <script>
                            function getProvinces(country_id){
                                let query_string = "action=PROVINCES";
                                if(country_id>0){
                                    query_string +="&COUNTRY_ID="+country_id;

                                }else{
                                    $('#CITY_ID').html(" <option value='0'>--Choose--</option>");
                                    $('#DISTRICT_ID').html(" <option value='0'>--Choose--</option>");
                                    $('#PROVINCE_ID').html(" <option value='0'>--Choose--</option>");
                                    return;
                                }
                                callAjax("../view/ajax_get.php?"+query_string,'PROVINCE_ID');
                            }
                            function getDistrict(province_id){
                                let query_string = "action=DISTIRCTS";
                                let country_id = $('#COUNTRY_ID').val();
                                if(province_id>0&&country_id>0){
                                    query_string +="&PROVINCE_ID="+province_id+"&COUNTRY_ID="+country_id;

                                }else{
                                    $('#DISTRICT_ID').html(" <option value='0'>--Choose--</option>");
                                    return;
                                }
                                callAjax("../view/ajax_get.php?"+query_string,'DISTRICT_ID');
                            }
                            function getCity(district_id){

                                let country_id = $('#COUNTRY_ID').val();
                                let PROVINCE_ID = $('#PROVINCE_ID').val();
                                populateCity(district_id,PROVINCE_ID,country_id);
                            }
                            function populateCity(district_id,PROVINCE_ID,country_id){
                                let query_string = "action=CITIES";
                                if(PROVINCE_ID>0&&country_id>0&&(district_id>=0||district_id==-1)){
                                    query_string +="&PROVINCE_ID="+PROVINCE_ID+"&COUNTRY_ID="+country_id+"&DISTRICT_ID="+district_id;
                                    //console.log('PROVINCE_ID'+PROVINCE_ID+'country_id'+country_id);
                                }else{
                                    console.log('PROVINCE_ID'+PROVINCE_ID+'country_id'+country_id);
                                    $('#CITY_ID').html(" <option value='0'>--Choose--</option>");
                                    return;
                                }
                                callAjax("../view/ajax_get.php?"+query_string,'CITY_ID');
                            }
                            <?php
//                            echo "getProvinces('{$user['COUNTRY_ID']}');";
//                            echo "getDistrict('{$user['PROVINCE_ID']}');";
//                            echo "populateCity('{$user['DISTRICT_ID']}','{$user['PROVINCE_ID']}','{$user['COUNTRY_ID']}');";

                            ?>
                        </script>
                    </div>
                    <div id="education" class="tab-pane animated custon-tab-style1">
                       education  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.

                        when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries.

                        the leap into electronic typesetting, remaining essentially unchanged.
                    </div>





            </div>
        </div>
    </div>
</div>
