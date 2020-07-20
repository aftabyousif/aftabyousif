<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/17/2020
 * Time: 5:43 PM
 */
?>
<script src="<?=base_url()?>assets/js/input-validation.js"></script>

<?=form_open('', 'id="experiance_form" onsubmit="event.preventDefault();"');?>
<div id="exp_form_msg"></div>
<h3>Add Experience</h3>

<div class="row">
    <div class="col-md-6">

        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating">Employeement Type
                <span class="text-danger">* &nbsp;</span></label>
            <select class="form-control" name="EMP_TYPE" id="EMP_TYPE">
                <?php
                $EMP_TYPES = array("GOVERMENT","PRIVATE","CONTRACT","INTERNSHIP");
                foreach($EMP_TYPES as $EMP_TYPE){
                    echo "<option>$EMP_TYPE</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating">Organization Name
                <span class="text-danger">*</span></label>
            <input type="text"   id="ORGANIZATION_NAME" class="form-control allow-string" name="ORGANIZATION_NAME" value=""  >


        </div>
    </div>







</div>
<div class="row">
    <div class="col-md-6">
        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating">Organization Address
                <span class="text-danger">*</span></label>
            <input type="text"   id="ADDRESS" class="form-control allow-address" name="ADDRESS" value=""  >


        </div>
    </div>
    <div class="col-md-6">
        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating">Job Description
                <span class="text-danger">*</span></label>
            <input type="text"   id="DESCRIPTION" class="form-control allow-string" name="DESCRIPTION" value=""  >


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
                    <input type="text" id="JOB_START_DATE"  name="JOB_START_DATE" class="form-control" readonly value="">
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-4">

        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating">End Date
                <span class="text-danger"><span id="end_date_staric">*</span> &nbsp;<small>dd/mm/yyyy</small></span></label>
            <div class="form-group data-custon-pick" id="data_2">
                <div class="input-group date">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" id="JOB_END_DATE"  name="JOB_END_DATE" class="form-control" readonly value="">
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-2">

        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating"><div style="color:black">Check if Continue</div></label><br>
            <input type="checkbox" onclick="checkIsContinue()" style="width: 25px;height:25px;" id="is_job_continue" name="is_job_continue">
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-6">
        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating">Organization Contact
                <span class="text-danger"></span></label>
            <input type="text"   id="CONTACT_NO" class="form-control allow-number" name="CONTACT_NO" value=""  >


        </div>
    </div>
    <div class="col-md-6">
        <div class="top-margin">
            <label for="exampleInput1" class="bmd-label-floating">Salary
                <span class="text-danger"><small>(PKR)</small></span></label>
            <input type="number"   id="SALARY" class="form-control allow-number" name="SALARY" value=""  >


        </div>
    </div>

</div>
<hr>

<div class="row">
    <div class="col-md-3">
        <div class="top-margin">
            <button type="button" class="btn btn-info btn-md" onclick="saveExperiance()">Save</button>
        </div>
    </div>
    <div class="col-md-3">
        <div class="top-margin">
            <button type="button" class="btn btn-danger btn-md" onclick="cancleExperiance()">Cancle</button>
        </div>
    </div>
</div>
</form>
<script>
    function saveExperiance(){

        var form = $('#experiance_form')[0];
        // Create an FormData object
        var data = new FormData(form);

        data.append("action", 'add_new_experiance');

        data.append(csrfName, csrfHash);
        $('.preloader').fadeIn(700);

        jQuery.ajax({
            url: "<?=base_url()?>candidate/addExperiance",
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
                $('#exp_form_msg').html("");
                alertMsg("Success",data.MESSAGE);
                getExperiance();
                cancleExperiance();
            },
            beforeSend:function (data, status) {

                $('#exp_form_msg').html('Loading...!');


            },
            error:function (data, status) {
                var value = data.responseJSON;

                alertMsg("Error",value.MESSAGE);
                if(value.csrfHash){
                    $('input[name="csrf_form_token"]').val(value.csrfHash);
                    csrfHash =value.csrfHash;
                }
                $('#exp_form_msg').html(value.MESSAGE);
                $('.preloader').fadeOut(700);



            },
        });

    }
</script>
<script src="<?=base_url()?>dash_assets/js/datapicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>dash_assets/js/datapicker/datepicker-active.js"></script>
