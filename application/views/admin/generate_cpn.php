<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="card">
        <div class="card-body">
            <form id="add_course_file_form"  enctype="multipart/form-data" onsubmit="return confirm('Are You Sure? Do you want Generate Cpn')" method="post">
                <div class="row">
                    <div class="col-md-1 top-margin">

                    </div>
                    <div class="col-md-4 top-margin">
                        <h3>GENERATE CPN</h3>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" name="YEAR" id="YEAR">
                            <option value="0">--Choose--</option>
                            <?php
                            foreach ($test_year as $year){
                                echo "<option value='{$year['YEAR']}'>{$year['YEAR']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-control" name="TEST_ID" id="TEST_ID" onchange="get_cpn_by_test_id()">
                            <option value="0">--Choose--</option>

                        </select>
                    </div>
                </div>
                <br>


                <br>
                <div class="row">
                    <div class="col-md-4 top-margin">
                        <button class="btn btn-success"id="upload_course_file">GENERATE CPN</button>
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
    function get_cpn_by_test_id(){
       let TEST_ID =  $('#TEST_ID').val();
        if(TEST_ID>0){
            jQuery.ajax({
                url: "<?=base_url()?>AdminPanel/getTestResultByTestId?TEST_ID="+TEST_ID,
                async:true,
                success: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("");

                    $('#VIEW_ALL_CPN').html(data);


                },
                beforeSend:function (data, status) {


                    $('#VIEW_ALL_CPN').html("LOADING...!");
                },
                error:function (data, status) {
                    alertMsg("Error",data.responseText);
                    $('#alert_msg_for_ajax_call').html("Something went worng..!");
                    $('#VIEW_ALL_CPN').html("Something went worng..!");
                },
            });
        }else{
            $('#VIEW_ALL_CPN').html("");
        }
    }
</script>