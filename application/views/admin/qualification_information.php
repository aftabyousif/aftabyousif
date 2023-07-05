<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/17/2020
 * Time: 11:06 AM
 */
?>


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

<script>



    function getQualification(){

        callAjax("<?=base_url()?>AdminApi/apiGetQualificationList","qulification_table_view");
    }



    function deleteQualification(id){
        if(confirm("Are You Sure?\nDo You want to delete your qualification..!")){

            $('.preloader').fadeIn(700);
            var data = new FormData();
            data.append("qualification_id", id);

            data.append(csrfName, csrfHash);
            jQuery.ajax({
                url: "<?=base_url()?>Candidate/apiDeleteQualification",
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
                    url: "<?=base_url()?>Candidate/addInstituteForQualification",
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
                    url: "<?=base_url()?>Candidate/addOrganizationForQualification",
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
        callAjax("<?=base_url()?>candidate/apiGetOrganization",'ORGANIZATION_ID','msg_alert');
    }

    function getInstituteByOrgId(){
        if(checkOrganization()){
            let ORGANIZATION_ID  = $("#ORGANIZATION_ID").val();
            query_string = "ORG_ID="+ORGANIZATION_ID;
            callAjax("<?=base_url()?>candidate/apiGetInstituteByOrgId?"+query_string,'INSTITUTE_ID','msg_alert');
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
        callAjax("<?=base_url()?>candidate/apiGetDisciplineById?"+query_string,'DISCIPLINE_ID','cus_msg');

    }

    getQualification();
</script>
