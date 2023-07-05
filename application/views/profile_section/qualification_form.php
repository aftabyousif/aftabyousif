<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/14/2020
 * Time: 10:28 PM
 */
 $msg ="Applicants applying for Admissions to Bachelor Degree Programs for the academic year 2021, are advised to enter / fill result of HSC Part – I (Intermediate First Year) whether his/ her result of HSC Part – II (Inter) is already announced or still not announced.";
 ?>

<script src="<?=base_url()?>assets/js/input-validation.js"></script>

<?=form_open('', 'id="qulification_form" onsubmit="event.preventDefault();"');?>
    <!--<div class='text-danger'> <strong></strong></div>-->
    <br>
    <div id="qul_form_msg"></div>
    <h3>Add Qualification</h3>
    <div class="row">

        <div class="col-sm-4">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Qualification / Degree / Certificate
                    <span class="text-danger">*</span></label>
                <br>
                <select   id="DEGREE_ID" class="js-example-basic-single form-control " ONCHANGE="getDiscipline(this.value)" name="DEGREE_ID">
                    <option value="0">--Choose--</option>
                    <?php



                    $bachelor = array(2,3);
                    $master_id = array(2,3,4,5,6);
                    if($program_type_id==1){
                        $in_array = $bachelor;
                    }else if($program_type_id==2){
                        $in_array = $master_id;
                    }

                    foreach ($degree_program as $degree) {
                        $select = "";
                        if(in_array($degree['DEGREE_ID'], $in_array)){
                            echo "<option value='{$degree['DEGREE_ID']}' $select >{$degree['DEGREE_TITLE']}</option>";
                        }

                    }
                    ?>

                </select>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Discipline / Subject / Group
                    <span class="text-danger">*</span></label>
                <br>
                <select   id="DISCIPLINE_ID" class="js-example-basic-single form-control " ONCHANGE="" name="DISCIPLINE_ID">
                    <option value="0">--Choose--</option>
                </select>

            </div>
        </div>
           <div class="col-sm-4">
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
<!--                    <option value="-2">--OTHER--</option>-->
                </select>
                <div id="add_new_org">
                    <input id="org_name" type="text" class="form-control allow-string-number-special" placeholder="Type missing institute name and add it"/> <button class="btn btn-warning" onclick="addOrg()">Add</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
     
        <!--<div class="col-sm-6">-->
        <!--    <div class="top-margin" >-->
        <!--        <label for="exampleInput1" class="bmd-label-floating">Institute / Department / School / College-->
        <!--            <span class="text-danger">*</span><div class="text-info" id="inst_msg"></div></label>-->
        <!--        <br>-->
        <!--        <select   id="INSTITUTE_ID" class="js-example-basic-single form-control " ONCHANGE="checkInstitute()" name="INSTITUTE_ID">-->
        <!--            <option value="0">--Choose--</option>-->


        <!--        </select>-->
        <!--        <div id="add_new_inst">-->
        <!--            <input id="institute_name" type="text" class="form-control allow-string-number-special" placeholder="Type missing institute name and add it"/> <button class="btn btn-warning" onclick="addInst()">Add</button>-->
        <!--        </div>-->
        <!--    </div>-->

        <!--</div>-->



    </div>
    <div class="row">
<!--        <div class="col-md-2">-->
<!---->
<!--            <div class="top-margin">-->
<!--                <label for="exampleInput1" class="bmd-label-floating"><div style="color:black">Check if Result Not Declare</div></label><br>-->
<!--                <input type="checkbox" onclick="checkIsDeclare()" style="width: 25px;height:25px;" id="result_not_declare" name="result_not_declare">-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="col-md-4">-->
<!---->
<!--            <div class="top-margin">-->
<!--                <label for="exampleInput1" class="bmd-label-floating">Result Declaration/Completion Date-->
<!--                    <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>-->
<!--                <div class="form-group data-custon-pick" id="data_2">-->
<!--                    <div class="input-group date">-->
<!--                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
<!--                        <input type="text" id="RESULT_DATE"  name="RESULT_DATE" class="form-control" value="" readonly>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
        <div class="col-md-2">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Exam Year
                    <span class="text-danger">*</span><div class="text-info" id="PASSING_YEAR_MSG"></div></label>
                <br>
                <select   id="PASSING_YEAR" class="js-example-basic-single form-control "  name="PASSING_YEAR">

                    <option value="0">--Choose--</option>


                </select>

            </div>
        </div>
        <div class="col-md-3">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Roll No/ Seat No 
                    <span class="text-danger">*</span></label>
                <input type="text"   id="ROLL_NO" class="form-control" name="ROLL_NO" value=""  >


            </div>
       




    </div>

<!--    <div class="row">-->
<!--        <div class="col-md-6">-->
<!---->
<!--            <div class="top-margin">-->
<!--                <label for="exampleInput1" class="bmd-label-floating">Start Date-->
<!--                    <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>-->
<!---->
<!--                <div class="form-group data-custon-pick" id="data_2">-->
<!--                    <div class="input-group date">-->
<!--                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
<!--                        <input type="text" id="START_DATE"  name="START_DATE" class="form-control" value="" readonly>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-6">-->
<!---->
<!--            <div class="top-margin">-->
<!--                <label for="exampleInput1" class="bmd-label-floating">End Date-->
<!--                    <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>-->
<!--                <div class="form-group data-custon-pick" id="data_2">-->
<!--                    <div class="input-group date">-->
<!--                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
<!--                        <input type="text" id="END_DATE"  name="END_DATE" class="form-control" value="" readonly>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </div>-->
    
        <div class="col-md-3">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Total Marks
                    <span class="text-danger">*
                        <small class="text-danger" id="view_total_mark_error">
                         </small>
                    </span></label>
                <input type="text"  onfocusout="checkPercentage()" id="TOTAL_MARKS" MIN="0" class="form-control allow-number" name="TOTAL_MARKS" value=""  >


            </div>
        </div>
        <div class="col-md-3">
            <div class="top-margin">
                <label for="exampleInput1" class="bmd-label-floating">Obtained Marks
                    <span class="text-danger">* <small class="text-danger" id="view_obtained_mark_error">
                         </small></span></label>
                <input type="text" onfocusout="checkPercentage()" id="OBTAINED_MARKS" MIN="0" class="form-control allow-number" name="OBTAINED_MARKS" value=""  >


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
                <img src="<?php echo $image_path_default; ?>" alt="Marksheet/Transcript " class="img-table-certificate" id="marksheet-image-view" onclick="setImage()" width="150px" height="150px" name="marksheet-image-view" >
                <input type="file" name="marksheet_image" id="marksheet_image" onchange="changeImage(this,'marksheet_image','marksheet-image-view',500)" accept=".jpg,.png,.jpeg">
                <input type="text" name="marksheet_image1" id="marksheet_image1" value="<?php echo $image_path; ?>" hidden>
                <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>

            </div>
        </div>
        <div class="col-md-3">
            <div style="margin-top:35px">

                <label for="exampleInput1" class="bmd-label-floating">Upload Pass/ Pacca Certificate
                    <span class="text-danger">*</span>
                </label>
                <?php
                $image_path = "";

                $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                ?>
                <img src="<?php echo $image_path_default; ?>" alt="passcertificate " class="img-table-certificate" id="passcertificate-image-view" onclick="setImage()" width="150px" height="150px" name="passcertificate-image-view" >
                <input type="file" name="passcertificate_image" id="passcertificate_image"                       onchange="changeImage(this,'passcertificate_image','passcertificate-image-view',500)" accept=".jpg,.png,.jpeg">
                <input type="text" name="passcertificate_image1" id="passcertificate_image1" value="<?php echo $image_path; ?>" hidden>
                <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>

            </div>
        </div>

    </div>
    <div class="row">
      <div class="col-md-6"></div>
        <div class="col-md-2">
            <!--btn btn-info btn-md-->
            <div class="top-margin">
                <button type="button" class="btn btn-info btn-md" onclick="saveQualification()">
                    <i class='fa fa-save'> </i> Save</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="top-margin">
                <button type="button" class="btn btn-danger btn-md" onclick="cancleQualificaion()"><i class='fa fa-close'> </i> Cancel</button>
            </div>
            </div>
    </div>
</form>
<script>
    $("#add_new_inst").hide();
    $("#add_new_org").hide();
    $(".cgpa_view").hide();
    function saveQualification(){

        var form = $('#qulification_form')[0];
        // Create an FormData object
        var data = new FormData(form);

        data.append("action", 'add_new_qualification');

        data.append(csrfName, csrfHash);
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
                csrfHash =data.csrfHash;
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
                csrfHash =value.csrfHash;
                $('#qul_form_msg').html(value.MESSAGE);
                $('.preloader').fadeOut(700);



            },
        });

    }

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
</script>

<script src="<?=base_url()?>dash_assets/js/datapicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>dash_assets/js/datapicker/datepicker-active.js"></script>
