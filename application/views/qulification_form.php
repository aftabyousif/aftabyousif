<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/14/2020
 * Time: 10:28 PM
 */?>
<?=form_open('', 'id="qulification_form" onsubmit="event.preventDefault();"');?>
    <div id="qul_form_msg"></div>
    <h3>Add Qualification</h3>
    <div class="row">

        <div class="col-sm-6">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Qualification / Degree / Certificate
                    <span class="text-danger">*</span></label>
                <br>
                <select   id="DEGREE_ID" class="js-example-basic-single form-control " ONCHANGE="getDiscipline(this.value)" name="DEGREE_ID">
                    <option value="0">--Choose--</option>
                    <?php

                    foreach ($degree_program as $degree) {
                        $select = "";

                        echo "<option value='{$degree['DEGREE_ID']}' $select >{$degree['DEGREE_TITLE']}</option>";
                    }
                    ?>

                </select>

            </div>
        </div>
        <div class="col-sm-6">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Discipline / Subject / Group
                    <span class="text-danger">*</span></label>
                <br>
                <select   id="DISCIPLINE_ID" class="js-example-basic-single form-control " ONCHANGE="" name="DISCIPLINE_ID">
                    <option value="0">--Choose--</option>
                </select>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Organization / University / Board
                    <span class="text-danger">*</span><div class="text-info" id="org_msg"></div></label>
                <br>
                <select   id="ORGANIZATION_ID" class="js-example-basic-single form-control " ONCHANGE="getInstituteByOrgId()" name="ORGANIZATION_ID">

                    <option value="0">--Choose--</option>
                    <?php

                    foreach ($organizations as $INSTITUTE) {
                        $select = "";
                        echo "<option value='{$INSTITUTE['INSTITUTE_ID']}' $select >{$INSTITUTE['INSTITUTE_NAME']}</option>";
                    }
                    ?>
                    <option value="-1">--OTHER--</option>
                </select>
                <div id="add_new_org">
                    <input id="org_name" type="text" class="form-control"/> <button class="btn btn-warning" onclick="addOrg()">Add</button>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="top-margin" >
                <label for="exampleInput1" class="bmd-label-floating">Institute / Department / School / College
                    <span class="text-danger">*</span><div class="text-info" id="inst_msg"></div></label>
                <br>
                <select   id="INSTITUTE_ID" class="js-example-basic-single form-control " ONCHANGE="checkInstitute()" name="INSTITUTE_ID">
                    <option value="0">--Choose--</option>


                </select>
                <div id="add_new_inst">
                    <input id="institute_name" type="text" class="form-control"/> <button class="btn btn-warning" onclick="addInst()">Add</button>
                </div>
            </div>

        </div>



    </div>
    <div class="row">
        <div class="col-md-2">

            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating"><div style="color:black">Check if Result Not Declare</div></label><br>
                <input type="checkbox" onclick="checkIsDeclare()" style="width: 25px;height:25px;" id="result_not_declare" name="result_not_declare">
            </div>
        </div>

        <div class="col-md-4">

            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Result Declaration/Completion Date
                    <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>
                <div class="form-group data-custon-pick" id="data_2">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" id="RESULT_DATE"  name="RESULT_DATE" class="form-control" value="">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Roll No/ Seat No / Registration No
                    <span class="text-danger">*</span></label>
                <input type="text"   id="ROLL_NO" class="form-control" name="ROLL_NO" value=""  >


            </div>
        </div>




    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Start Date
                    <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>

                <div class="form-group data-custon-pick" id="data_2">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" id="START_DATE"  name="START_DATE" class="form-control" value="">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-6">

            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">End Date
                    <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>
                <div class="form-group data-custon-pick" id="data_2">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" id="END_DATE"  name="END_DATE" class="form-control" value="">
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Total Marks
                    <span class="text-danger">*
                        <small class="text-danger" id="view_total_mark_error">
                         </small>
                    </span></label>
                <input type="number"  onfocusout="checkPercentage()" id="TOTAL_MARKS" MIN="0" class="form-control" name="TOTAL_MARKS" value=""  >


            </div>
        </div>
        <div class="col-md-5">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Obtained Marks
                    <span class="text-danger">* <small class="text-danger" id="view_obtained_mark_error">
                         </small></span></label>
                <input type="number" onfocusout="checkPercentage()" id="OBTAINED_MARKS" MIN="0" class="form-control" name="OBTAINED_MARKS" value=""  >


            </div>

        </div>
        <div class="col-md-1">
            <div class="top-margin">
                <br>
                <label class="bmd-label-floating" id="view_per">

                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="top-margin">
                <div class="row">
                    <input style="width:1.3em;height:1.3em;" type="radio" class=" mb-3" id="is_grade" name="check_grade" value="grade" checked>
                    <label for="" style="font-size:17px"><small>Grade</small></label>
                </div>
                <div class="row">
                    <input style="width:1.3em;height:1.3em;" type="radio" class=" mb-3" id="is_cgpa" name="check_grade" value="cgpa">
                    <label for="" style="font-size:17px"><small>CGPA/Percentage</small></label>

                </div>
            </div>
        </div>
        <div class="col-md-4 grade_view">

            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Grade
                    <span class="text-danger">* &nbsp;</span></label>
                <select class="form-control" name="grade" id="grade">
                    <?php
                    $grades = array("N/A","A+","A","B+","B","C","D","E");
                    foreach($grades as $grade){
                        echo "<option>$grade</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-2 cgpa_view">

            <div class="top-margin">
                <label for="exampleInput1" style="font-size: 1.1rem" class="bmd-label-floating">CGPA/Percentage
                    <span class="text-danger">* &nbsp;</span></label>
                <input type="text"   id="cgpa" class="form-control" name="cgpa" value=""  >
            </div>
        </div>
        <div class="col-md-2 cgpa_view">

            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Out of
                    <span class="text-danger">* &nbsp;</span></label>
                <select class="form-control" name="out_of" id="out_of">
                    <?php
                    $out_ofs = array("4","5","6","10","20","100");
                    foreach($out_ofs as $out_of){
                        echo "<option>$out_of</option>";
                    }
                    ?>
                </select>
            </div>
        </div>


    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div style="margin-top:35px">

                <label for="exampleInput1" class="bmd-label-floating">Upload Marksheet/Transcript
                    <span class="text-danger">*</span>
                </label>
                <?php
                $image_path = "";

                $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                ?>
                <img src="<?php echo $image_path_default; ?>" alt="Marksheet/Transcript " class="" id="marksheet-image-view" onclick="setImage()" width="150px" height="150px" name="marksheet-image-view" >
                <input type="file" name="marksheet_image" id="marksheet_image"                       onchange="changeImage(this,'marksheet_image','marksheet-image-view',2048)" accept=".jpg,.png,.jpeg">
                <input type="text" name="marksheet_image1" id="marksheet_image1" value="<?php echo $image_path; ?>" hidden>
                <span class="text-danger">Make Sure Image must be clear Image size should be less than 2mb</span>

            </div>
        </div>
        <div class="col-md-3">
            <div style="margin-top:35px">

                <label for="exampleInput1" class="bmd-label-floating">Upload Pass/Pakka Certificate
                    <span class="text-danger">*</span>
                </label>
                <?php
                $image_path = "";

                $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                ?>
                <img src="<?php echo $image_path_default; ?>" alt="passcertificate " class="" id="passcertificate-image-view" onclick="setImage()" width="150px" height="150px" name="passcertificate-image-view" >
                <input type="file" name="passcertificate_image" id="passcertificate_image"                       onchange="changeImage(this,'passcertificate_image','passcertificate-image-view',2048)" accept=".jpg,.png,.jpeg">
                <input type="text" name="passcertificate_image1" id="passcertificate_image1" value="<?php echo $image_path; ?>" hidden>
                <span class="text-danger">Make Sure Image must be clear Image size should be less than 2mb</span>

            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="top-margin">
                <button type="button" class="btn btn-info btn-md" onclick="saveQualification()">Save</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="top-margin">
                <button type="button" class="btn btn-danger btn-md" onclick="cancleQualificaion()">Cancle</button>
            </div>
        </div>
    </div>
</form>
<script>
    $("#add_new_inst").hide();
    $("#add_new_org").hide();
    function saveQualification(){

        var form = $('#qulification_form')[0];
        // Create an FormData object
        var data = new FormData(form);

        data.append("action", 'add_new_qualification');

        $('.preloader').fadeIn(700);

        jQuery.ajax({
            url: "<?=base_url()?>candidate/addQualification",
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
                $('#qul_form_msg').html("");
                alertMsg("Success",data.MESSAGE);
                getQualification();
                cancleQualificaion();
            },
            beforeSend:function (data, status) {

                $('#qul_form_msg').html('Loading...!');


            },
            error:function (data, status) {
                var value = data.responseJSON;

                alertMsg("Error",value.MESSAGE);
                $('input[name="csrf_form_token"]').val(value.csrfHash);
                $('#qul_form_msg').html(value.MESSAGE);
                $('.preloader').fadeOut(700);



            },
        });

    }


    function addInst() {
        let query_string="";
        let ORGANIZATION_ID  = $("#ORGANIZATION_ID").val();
        if(ORGANIZATION_ID<=0){
            $('#inst_msg').html("First Select Organization..!");
            return;
        }
        let institute_name  = $("#institute_name").val();
        if(institute_name){
            institute_name= escapeHtml(institute_name);
            query_string = "action=add_institute&inst_name="+institute_name+'&PARENT_ID='+ORGANIZATION_ID;
            callAjax("../ajax/add_institute.php?"+query_string,'inst_msg','inst_msg');
            $("#add_new_inst").hide();
            getInstituteByOrgId()
        }else{
            $("#institute_name").attr('style',"border-color:red");
        }


    }
    function addOrg() {
        let query_string="";
        let org_name  = $("#org_name").val();
        if(org_name){
            org_name= escapeHtml(org_name);
            query_string = "action=add_organization&org_name="+org_name;
            callAjax("../ajax/add_institute.php?"+query_string,'org_msg','org_msg');
            $("#add_new_org").hide();

            query_string = "action=INSTITUTE&INSTITUTE_TYPE_ID=1&IS_INST=Y";
            callAjax("../view/ajax_get.php?"+query_string,'ORGANIZATION_ID','msg_alert');
        }else{
            $("#org_name").attr('style',"border-color:red");
        }


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

    function getDiscipline(degree_id) {

        let query_string = "action=DISCIPLINE";

        if(degree_id>0){
            query_string +="&DEGREE_ID="+degree_id;

        }else{
            $('#DISTRICT_ID').html(" <option value='0'>--Choose--</option>");
            return;
        }
        callAjax("<?=base_url()?>candidate/apiGetDisciplineById?"+query_string,'DISCIPLINE_ID','cus_msg');

    }

    $(".cgpa_view").hide();

    $("#is_cgpa").change(function(){
        if($("#is_cgpa").is(':checked')){
            console.log("passport");
            $(".cgpa_view").show();
            $(".grade_view").hide();
        }
    });
    $("#is_grade").change(function(){
        if($("#is_grade").is(':checked')){
            console.log("passport");
            $(".cgpa_view").hide();
            $(".grade_view").show();
        }
    });
    function  cancleQualificaion() {
        $('.disab').show();
        $('#qulification_form_view').html('');
    }
</script>
<script src="<?=base_url()?>dash_assets/js/datapicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>dash_assets/js/datapicker/datepicker-active.js"></script>
