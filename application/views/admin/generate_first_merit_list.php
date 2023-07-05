<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="card">
        <div class="card-body">
            <form id=""  action = "<?=base_url('SelectionList/generateList')?>" enctype="multipart/form-data" onsubmit="return check_validation_for_first_list()" method="post">
                <div class="row">
                    <div class="col-md-1 top-margin">

                    </div>
                    <div class="col-md-10 top-margin">
                        <h3>GENERATE MERIT LIST</h3>
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
                    <div class="col-md-5">
                        <label>IS OBJECTION / PROVISIONAL OBJECTTION *</label>

                        <select class="form-control" name="IS_PROVISIONAL" id="IS_PROVISIONAL">
                            <option value="0">--Choose--</option>
                            <option value="Y">YES</option>
                            <option value="N">NO</option>

                        </select>
                    </div>
                    <div class="col-md-5">
                        <label>IS_SPECAIL_SELF *</label>

                        <select class="form-control" name="IS_SPECAIL_SELF" id="IS_SPECAIL_SELF">
                            <option value="0">--Choose--</option>
                            <option value="N">NO</option>
                            <option value="Y">YES</option>


                        </select>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-md-6">

                            <label>Choose Multiple Program
                                <span class='text-danger'> *</span>
                            </label>
                            <select style="height:400px;" name="PROG_LIST_ID[]" id="PROG_LIST_ID" class="form-control" multiple="multiple" onchange="getSelectedProgram()">

                            </select>

                    </div>
                    <div class="col-md-6">
                        <table>
                            <thead>
                            <tr>
                                <th>PROG_LIST_ID</th>
                                <th>PROGRAM NAME</th>
                            </tr>
                            </thead>
                            <tbody id="selected_prog_list">

                            </tbody>

                        </table>
                    </div>
                </div>
                <br>


                <br>
                <div class="row">
                    <div class="col-md-4 top-margin">
                        <button class="btn btn-success"id="upload_course_file">GENERATE MERIT LIST</button>
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
    function check_validation_for_first_list(){
        let year = $('#YEAR').val();
        let PROG_TYPE_ID = $('#PROG_TYPE_ID').val();
        let SHIFT_ID = $('#SHIFT_ID').val();
        let CAMPUS_ID = $('#CAMPUS_ID').val();
        let TEST_ID = $('#TEST_ID').val();
        let LIST_NO = $('#ADMISSION_LIST_ID').val();
        let IS_PROVISIONAL = $('#IS_PROVISIONAL').val();
        let PROG_LIST_ID = $('#PROG_LIST_ID').val();
        PROG_LIST_ID =  PROG_LIST_ID.length;

        if(year>0&&PROG_TYPE_ID>0&&SHIFT_ID>0&&CAMPUS_ID>0&&TEST_ID>0&&LIST_NO>0&&IS_PROVISIONAL!=0&&PROG_LIST_ID>0){
            var tr="";
            $.each($("#PROG_LIST_ID option:selected"), function(){
                let p_id = $(this).val();
                let p_title = $(this).text();

                tr+=""+p_id+"->";
                tr+=""+p_title+"\n";

            });
            if(confirm('Are You Sure? Do you want Generate  '+LIST_NO+' Merit List And Objection List = '+IS_PROVISIONAL+'\n'+tr)){
                return true;
            }
        }else{
            alertMsg("ERROR","Must Select All Select Box And At least one Program");
        }
        return false;
    }

function getSelectedProgram() {

    var PROG_LIST = [];
    $('#selected_prog_list').html("");
    $.each($("#PROG_LIST_ID option:selected"), function(){
        let p_id = $(this).val();
        let p_title = $(this).text();
        let tr="<tr>";
        tr+="<td>"+p_id+"</td>";
        tr+="<td>"+p_title+"</td>";
        $('#selected_prog_list').append(tr);
    });

}
</script>