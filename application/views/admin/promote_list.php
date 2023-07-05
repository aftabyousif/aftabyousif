<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="card">
        <div class="card-body">
            <form id=""  action = "<?=base_url('PromoteList/promote_list')?>" enctype="multipart/form-data" onsubmit="return check_validation_for_first_list()" method="post">
                <div class="row">
                    <div class="col-md-1 top-margin">

                    </div>
                    <div class="col-md-10 top-margin">
                        <h3>PROMOTE SELECTION LIST</h3>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label>Program *</label>
                        <select class="form-control" name="PROG_TYPE_ID" id="PROG_TYPE_ID">
                            <option value="0">--Choose--</option>
                            <option value="1">BACHELOR</option>
                            <option value="2">MASTER</option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Shift *</label>
                        <select class="form-control" name="SHIFT_ID" id="SHIFT_ID">
                            <option value="0">--Choose--</option>
                            <option value="1">MORNING</option>
                            <option value="2">EVENING</option>

                        </select>
                    </div>
                    <div class="col-md-2">

                        <label>Batch Year *</label>
                        <select class="form-control" name="YEAR" id="YEAR">
                            <option value="0">--Choose--</option>
                            <?php
                            foreach ($test_year as $year){
                                echo "<option value='{$year['YEAR']}'>{$year['YEAR']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Test Type *</label>
                        <select class="form-control" name="TEST_ID" id="TEST_ID">
                            <option value="0">--Choose--</option>

                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Campus *</label>
                        <select class="form-control" name="CAMPUS_ID" id="CAMPUS_ID">
                            <option value="0">--Choose--</option>

                            <?php
                            foreach ($campus as $c){
                                echo "<option value='{$c['CAMPUS_ID']}'>{$c['NAME']}</option>";
                            }
                            ?>

                        </select>
                    </div>

                </div>
                <br>

                <br>
                <div class="row">
                    <div class="col-md-2">
                        <label>List No *</label>
                        <select class="form-control" name="ADMISSION_LIST_ID" id="ADMISSION_LIST_ID">
                            <option value="0">--Choose--</option>

                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Is Specail Self *</label>
                        <select class="form-control" name="IS_SPECIAL_SELF" id="IS_SPECIAL_SELF">
                            <option value="0">--Choose--</option>
                            <option value="N">NO</option>
                            <option value="Y">YES</option>

                        </select>
                    </div>

                </div>
                <br>


                <br>
                <div class="row">
                    <div class="col-md-4 top-margin">
                        <button class="btn btn-success"id="upload_course_file">Promote Selection</button>
                    </div>
                </div>
            </form>
            <br>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-12' id="VIEW_ALL_CPN">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
 
    $('#YEAR').change(function (){
        let year = $('#YEAR').val();
        let PROG_TYPE_ID = $('#PROG_TYPE_ID').val();
        if(year>0){
            jQuery.ajax({
                url: "<?=base_url()?>AdminPanel/getTestType?YEAR="+year,
                async:true,
                success: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("");

                    $('#TEST_ID').html(data);


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
            $('#TEST_ID').html(" <option value='0'>--Choose--</option>");
        }
    });
    $('#CAMPUS_ID').change(
        function (){
            $("#PROG_LIST_ID").html("");
            $("#ADMISSION_LIST_ID").html(' <option value="0">--Choose--</option>');


            $('#selected_prog_list').html("")
            let year = $('#YEAR').val();
            let PROG_TYPE_ID = $('#PROG_TYPE_ID').val();
            let CAMPUS_ID = $('#CAMPUS_ID').val();
            let SHIFT_ID = $('#SHIFT_ID').val();
            if(PROG_TYPE_ID>0&&CAMPUS_ID>0&&SHIFT_ID>0){
                jQuery.ajax({
                    url:'<?=base_url()?>mapping/getMappedPrograms',
                    method: 'POST',
                    data: {shift_id:SHIFT_ID,program_type:PROG_TYPE_ID,campus_id:CAMPUS_ID},
                    dataType: 'json',
                    success: function (response) {
                        let i=0;
                        $.each(response, function (index,value) {
                            // i++;
                            // if (value['REMARKS'] == null)
                            // 	var remarks = '';
                            let option="";
                            option+= "<option value='"+value['PROG_ID']+"'>"+value['PROGRAM_TITLE']+"</option>";
                            $("#PROG_LIST_ID").append(option);
                        });



                    },
                    beforeSend:function (data, status) {


                        $('#alert_msg_for_ajax_call').html("LOADING...!");
                    },
                    error:function (data, status) {
                        alertMsg("Error",data.responseText);
                        $('#alert_msg_for_ajax_call').html("Something went worng..!");
                        $("#PROG_LIST_ID").html("");
                        $('#selected_prog_list').html("")
                    },
                });
                jQuery.ajax({
                    url:'<?=base_url()?>SelectionList/getAdmissionList',
                    method: 'POST',
                    data: {SHIFT_ID:SHIFT_ID,PROG_TYPE_ID:PROG_TYPE_ID,CAMPUS_ID:CAMPUS_ID,YEAR:year},
                    dataType: 'json',
                    success: function (response) {
                        let i=0;
                        $.each(response, function (index,value) {
                            // i++;
                            // if (value['REMARKS'] == null)
                            // 	var remarks = '';
                            let option="";
                            option+= "<option value='"+value['ADMISSION_LIST_ID']+"'>"+value['LIST_NO']+"</option>";
                            $("#ADMISSION_LIST_ID").append(option);
                        });



                    },
                    beforeSend:function (data, status) {


                        $('#alert_msg_for_ajax_call').html("LOADING...!");
                    },
                    error:function (data, status) {
                        alertMsg("Error",data.responseText);
                        $('#alert_msg_for_ajax_call').html("Something went worng..!");
                        $("#ADMISSION_LIST_ID").html("");
                        //  $('#').html("")
                    },
                });
            }else{
                $("#PROG_LIST_ID").html("");
                $('#selected_prog_list').html("")
            }
        }
    );
</script>