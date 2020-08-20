
<div style="height:100px"></div>


<div class="container">


    <div class="card" style="margin-top: 20px;margin-bottom: 50px;">
        <div class="card-header">
            <h1 >Registeration</h1>
        </div>
        <div class="card-body">
            <div class="login">
                <?=form_open('', 'class="row" id="registration"');?>
<!--                <form  id="registration" action="" class="row" method="post" >-->
                    <div class="col-12">
                        <span class="text-danger font-weight-bold">* Note: Please provide your full name as per matriculation certificate.</span><br>
                        <label for="" style="font-size:17px">Full Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" id="full_name" name="full_name" data-toggle="tooltip" title="Full Name" placeholder="Full Name">
                    </div>
                <div class="col-12">

                    <label for="" style="font-size:17px">Father Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control mb-3" id="f_name" name="f_name" data-toggle="tooltip" title="Father Name" placeholder="Father Name">
                </div>
                    <div class="col-12">

                        <label for="" style="font-size:17px">Surname / Cast<span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-3" id="surname" name="surname" data-toggle="tooltip" title="Surname/Cast/Family Name" placeholder="Surname/Cast/Family Name">
                    </div>
                    <div class="col-12">
                        <label for="" style="font-size:17px">Email<span class="text-danger">*</span></label>
                        <input type="email" class="form-control mb-3" id="email" name="email" placeholder="Email Address">
                    </div>
                    <div class="col-12">
                        <label for="" style="font-size:17px">Mobile<span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-2">
                                <select  class="js-example-basic-single form-control mb-3" name="PHONE_CODE" id="PHONE_CODE">
                                    <?php

                                    foreach ($countries as $country) {
                                        $select = "";
                                        if($country['COUNTRY_NAME']=='PAKISTAN'){
                                            $select = "selected";
                                        }
                                        echo "<option value='{$country['PHONE_CODE']}' $select >{$country['COUNTRY_NAME']} &nbsp;&nbsp; {$country['PHONE_CODE']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-10">
                                <input type="text" class="form-control mb-3" id="mobile" name="mobile" placeholder="3423527802">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="" style="font-size:17px">Country/Nationality<span class="text-danger">*</span></label>
                        <select name="COUNTRY_ID" id="COUNTRY_ID"  class="js-example-basic-single form-control mb-3">

                            <?php

                            foreach ($countries as $country) {
                                $select = "";
                                if($country['COUNTRY_NAME']=='PAKISTAN'){
                                    $select = "selected";
                                }
                                echo "<option value='{$country['COUNTRY_ID']}' $select >{$country['COUNTRY_NAME']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                <div class="col-12">
                    <label for="" style="font-size:17px">Domicile Province / State<span class="text-danger">*</span></label>
                    <select  id="PROVINCE_ID" class="js-example-basic-single form-control"  ONCHANGE="getDistrict(this.value)" name="PROVINCE_ID">
                        <option value="0">--Choose--</option>


                    </select>
                </div>
                <div class="col-12">
                    <label for="" style="font-size:17px">Domicile District<span class="text-danger">*</span></label>
                    <select  id="DISTRICT_ID" class="js-example-basic-single form-control" name="DISTRICT_ID">
                        <option value="0">--Choose--</option>



                    </select>
                </div>

                    <div class="col-12">
                        <!--                        <label for="" style="font-size:17px">CNIC<span class="text-danger"></span></label>-->
                        <input style="width:1.3em;height:1.3em;" hidden type="radio" class=" mb-3" id="is_cnic" name="check_cnic" value="cnic" checked>
                        &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;
                        <!--                        <label for="" style="font-size:17px">Passport <span class="text-danger">(For Foregin)</span></label>-->
                        <input style="width:1.3em;height:1.3em;" hidden  type="radio" class=" mb-3" id="is_passport" name="check_cnic" value="passport">
                    </div>
                    <div id="cnic_view" style="width:100%">
                        <div class="col-12">
                                <span class="text-danger font-weight-bold" style="margin:5px;">
                                *Note
                                    <ul>
                                        <li>Please use your own CNIC number to register.</li>
                                        <li>CNIC can not be changed after registration.</li>
                                    </ul>
                                </span>
                        </div>


                        <div class="col-12">
                            <label for="" style="font-size:17px">CNIC<span class="text-danger">* (with out dashes)</span></label>
                            <input onfocusout="checkAlertValidation('CNIC')" type="text" class="form-control mb-3" id="cnic" name="cnic" placeholder="CNIC or Form-B(xxxxxxxxxxxxx)">
                        </div>
                        <div class="col-12">
                            <label for="" style="font-size:17px">Re-Type CNIC<span class="text-danger">* (with out dashes)</span></label>
                            <input onfocusout="checkAlertValidation('RE-CNIC')" type="text" class="form-control mb-3" id="retype_cnic" name="retype_cnic" placeholder="Re-Type CNIC or Form-B(xxxxxxxxxxxxx)">
                        </div>
                    </div>
                    <div id="passport_view" style="width:100%">
                        <div class="col-12">
                                <span class="text-danger font-weight-bold" style="margin:5px;">
                                *Note
                                    <ul>
                                        <li>Please use your own Passport number to register.</li>
                                        <li>Passport can not be changed after registration.</li>
                                    </ul>
                                </span>
                        </div>


                        <div class="col-12">
                            <label for="" style="font-size:17px">Passport No<span class="text-danger">* </span></label>
                            <input onfocusout="checkAlertValidation('PASSPORT')" type="text" class="form-control mb-3" id="passport" name="passport" placeholder="Passport No">
                        </div>
                        <div class="col-12">
                            <label for="" style="font-size:17px">Re-Type Passport No<span class="text-danger">* </span></label>
                            <input onfocusout="checkAlertValidation('RE-PASSPORT')" type="text" class="form-control mb-3" id="retype_passport" name="retype_passport" placeholder="Passport No">
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="" style="font-size:17px">Password<span class="text-danger">* </span></label>
                        <input onfocusout="checkAlertValidation('PASSWORD')" type="password" class="form-control mb-3" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="col-12">
                        <label for="" style="font-size:17px">Re-Type Password<span class="text-danger">* </span></label>
                        <input onfocusout="checkAlertValidation('RE-PASSWORD')" type="password" class="form-control mb-3" id="retype_password" name="retype_password" placeholder="Re-Type Password">
                    </div>
                    <div class="col-12">
                        <button type="submit" id="register" class="btn btn-primary">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</div>
<!-- footer -->

<!-----Scripting for Registration form------>
<script>

    <?php
    $res = getcsrf($this);
    ?>
    var csrfName="<?=$res['csrfName']?>";
    var csrfHash="<?=$res['csrfHash']?>";

    $("#register").click(function (event) {

        //stop submit the form, we will post it manually.
        event.preventDefault();

        let big_error = "";
        let error="";
        let name = $("#full_name").val();
        let f_name = $("#f_name").val();
        let surname = $("#surname").val();
        let email = $("#email").val();
        let mobile = $("#mobile").val();
        let code = $("#PHONE_CODE").val();
        let DISTRICT_ID = $("#DISTRICT_ID").val();
        let PROVINCE_ID = $("#PROVINCE_ID").val();
        if(!name){
            big_error+= "<div class='text-danger'>Please provide your full name as per matriculation certificate.</div>";;
        }
        if(!f_name){
            big_error+= "<div class='text-danger'>Please provide your Father.</div>";;
        }
        if(!surname){
            big_error+= "<div class='text-danger'>Please provide your Surname / Cast / Family Name.</div>";;
        }
        if(!email){
            big_error+= "<div class='text-danger'>Please provide email</div>";
        }
        if(!mobile || mobile.length>=12 ||mobile.length<=9){
            big_error+= "<div class='text-danger'>Please provide your active mobile number  must be less then 12 and greater 9.</div>";;
        }
        if(!(/^\d+$/.test(mobile))){
            big_error += "<div class='text-danger'>All Character must be digit in Mobile No</div>";
        }
        if(!(PROVINCE_ID>0)){
            big_error+= "<div class='text-danger'>Domilice province must be select</div>";
        }
        if(!(DISTRICT_ID>0)){
            big_error+= "<div class='text-danger'>Domilice district must be select</div>";
        }

        if($("#is_cnic").is(':checked')) {
            error = checkCnicValidation();
            if (error !== true) {
                big_error += error;
            }

            error = checkCnicReValidation();
            if (error !== true) {
                big_error += error;
            }
        }
        else {
            error = checkPassportValidation();
            if (error !== true) {
                big_error += error;
            }

            error = checkPassportReValidation();
            if (error !== true) {
                big_error += error;
            }
        }

        error = checkPasswordValidation()
        if(error!==true){
            big_error+=error;
        }
        error = checkPasswordReValidation();
        if(error!==true){
            big_error+=error;
        }


        if(big_error!==""){
            alert_msg(big_error);
            return;
        }
        var form = $('#registration')[0];

        // Create an FormData object
        var data = new FormData(form);

        // If you want to add an extra field for the FormData



        $('.preloader').fadeIn(700);
        // disabled the submit button
        $("#register").prop("disabled", true);
        data.append(csrfName, csrfHash);

        //data.set('mobile',code+mobile);
        data.append("action", "add_new_user");
        jQuery.ajax({
            url: "<?=base_url();?>Register/user_register_handler",
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            data: data,
            success: function (data, status) {

                $('.preloader').fadeOut(700);
                $('input[name="csrf_form_token"]').val(data.csrfHash);
                csrfHash = data.csrfHash;
                alert_msg(data.MESSAGE,"Success");

                $("#register").prop("disabled", false);
                setNull();

            },
            beforeSend:function (data, status) {
                $('.preloader').fadeIn(700);

            },
            error:function (data, status) {

                var value = data.responseJSON;

                alert_msg(value.MESSAGE,"Error");
                $('input[name="csrf_form_token"]').val(value.csrfHash);
                csrfHash = value.csrfHash;
                //$('#alert_msg_for_ajax_call').html(value.MESSAGE);


                $("#register").prop("disabled", false);
                $('.preloader').fadeOut(700);



            },
        });




    });



    $("#passport_view").hide();

    $("#is_passport").change(function(){
        if($("#is_passport").is(':checked')){
            //console.log("passport");
            $("#passport_view").show();
            $("#cnic_view").hide();
        }else{
            $("#cnic_view").show();
            $("#passport_view").hide();
            //console.log("cnic");
        }
    });

    $("#is_cnic").change(function(){
        if($("#is_cnic").is(':checked')){
            $("#passport_view").hide();
            $("#cnic_view").show();
            //console.log("is_cnic");
        }else{

            $("#passport_view").show();
            $("#cnic_view").hide();
            //    console.log("pass");
        }
    });
    $("#COUNTRY_ID").change(function(){
        var value = $("#COUNTRY_ID option:selected");

        // console.log($("#COUNTRY_ID").text());
        getProvinces($("#COUNTRY_ID").val());
        getDistrict(0);
        if(value.text()==='PAKISTAN'){
            // $("#is_cnic").checked();
            $("#is_cnic").prop("checked", true);
            $("#is_passport").prop("checked", false);
            $("#passport_view").hide();
            $("#cnic_view").show();
            //console.log("is_cnic");
        }else{
            $("#is_cnic").prop("checked", false);
            $("#is_passport").prop("checked", true);
            $("#passport_view").show();
            $("#cnic_view").hide();
            //    console.log("pass");
        }
    });

    function checkAlertValidation(val){
        if(val==="CNIC"){
            let error = checkCnicValidation();
            if(error!==true){
                alert_msg(error);
            }
        }else if(val==="RE-CNIC"){
            let error = checkCnicReValidation();
            if(error!==true){
                alert_msg(error);
            }
        }else if(val==="PASSPORT"){
            let error = checkPassportValidation();
            if(error!==true){
                alert_msg(error);
            }
        }else if(val==="RE-PASSPORT"){
            let error = checkPassportReValidation();
            if(error!==true){
                alert_msg(error);
            }
        }else if(val==="PASSWORD"){
            let error = checkPasswordValidation()
            if(error!==true){
                alert_msg(error);
            }
        }else if(val==="RE-PASSWORD"){
            let error = checkPasswordReValidation();
            if(error!==true){
                alert_msg(error);
            }
        }


    }
    function checkCnicValidation(){
        let cnic = $('#cnic').val();
        //console.log(cnic);
        let error = "";
        if(!cnic){
            error += "<div class='text-danger'>Cnic must fill</div>";
        }
        if(cnic.length!==13){
            error += "<div class='text-danger'>Cnic must contain 13 digit</div>";
        }
        if(!(/^\d+$/.test(cnic))){
            error += "<div class='text-danger'>All Character must be digit</div>";
        }

        if(error!==""){
            // alert_msg(error);
            return error;
        }else{
            return true;
        }
    }
    function checkCnicReValidation(){

        let retype_cnic = $('#retype_cnic').val();
        //console.log(cnic);
        let cnic_val =  checkCnicValidation();
        if(cnic_val===true){
            let error = "";
            if(!retype_cnic){
                error += "<div class='text-danger'>Cnic must fill</div>";
            }
            if(retype_cnic.length!==13){
                error += "<div class='text-danger'>Cnic must contain 13 digit</div>";
            }
            if(!(/^\d+$/.test(retype_cnic))){
                error += "<div class='text-danger'>All Character must be digit</div>";
            }
            let cnic = $('#cnic').val();
            if(retype_cnic!==cnic){
                error += "<div class='text-danger'>Cnic missmatch</div>";
            }

            if(error!==""){
                //alert_msg(error);
                return error;
            }else{
                return true;
            }
        }else{
            return cnic_val;
        }
    }
    function checkPassportValidation(){
        let passport = $('#passport').val();
        //console.log(cnic);
        let error = "";
        if(!passport){
            error += "<div class='text-danger'>Passport must fill</div>";
        }

        if(passport.length<3||passport.length>20){
            error += "<div class='text-danger'>Passport length should be minimum 3 characters to a maximum of 20 characters</div>";
        }
        if(error!==""){
            //alert_msg(error);
            return error;
        }else{
            return true;
        }
    }
    function checkPassportReValidation(){
        let retype_passport = $('#retype_passport').val();
        let passport = $('#passport').val();
        //console.log(cnic);
        let error = "";
        if(!retype_passport){
            error += "<div class='text-danger'>Passport must fill</div>";
        }

        if(retype_passport.length<3||retype_passport.length>20){
            error += "<div class='text-danger'>Passport length should be minimum 3 characters to a maximum of 20 characters</div>";
        }
        if(retype_passport!==passport){
            error += "<div class='text-danger'>Passport missmatch</div>";
        }
        if(error!==""){
            // alert_msg(error);
            return error;
        }else{
            return true;
        }
    }

    function checkPasswordValidation(){
        let password = $('#password').val();
        //console.log(cnic);
        let error = "";
        if(!password){
            error += "<div class='text-danger'>Password must fill</div>";
        }

        if(password.length<8){
            error += "<div class='text-danger'>Password length should be minimum 8 characters</div>";
        }
        if(error!==""){
            // alert_msg(error);
            return error;
        }else{
            return true;
        }
    }
    function checkPasswordReValidation(){
        let password = $('#password').val();
        let repassword = $('#retype_password').val();
        let error = "";
        if(password!==repassword){
            error += "<div class='text-danger'>Password missmatch</div>";
        }
        if(error!==""){
            // alert_msg(error);
            return error;
        }else{
            return true;
        }
    }
    function setNull() {
        $('#full_name').val(null);
        $('#email').val(null);
        $('#passport').val(null);
        $('#retype_passport').val(null);
        $('#password').val(null);
        $('#retype_password').val(null);
        $('#cnic').val(null);
        $('#retype_cnic').val(null);
        $('#mobile').val(null);
        $('#surname').val(null);
        getProvinces($("#COUNTRY_ID").val());
        getDistrict(0);
    }

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
            $("#PROVINCE_ID").html("<option value='0'>--Choose--</option>");
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
            $("#DISTRICT_ID").html("<option value='0'>--Choose--</option>");
            console.log("error");
        }
    }
    getProvinces($("#COUNTRY_ID").val());
</script>
