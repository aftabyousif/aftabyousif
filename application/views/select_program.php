<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="review-content-section">
                        <div id="dropzone1" class="pro-ad">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $hidden = array("APPLICATION_ID"=>$APPLICATION_ID,"DISCIPLINE_ID"=>$DISCIPLINE_ID);

                                    ?>
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInput1" class="bmd-label-floating"> CHOOSE PROGRAM
                                                    <span class="text-danger">*</span></label>
                                                <select  class="js-example-basic-single form-control mb-3" name="PROGRAM_LIST_ID" id="PROGRAM_LIST_ID">
                                                    <option value="0">--Choose--</option>
                                                    <?php
                                                    foreach ($PROGRAM_LIST as $PROGRAM) {
                                                       foreach ($VALID_PROGRAM_LIST as $k =>$VALID_PROGRAM) {
                                                            if($PROGRAM['PROG_LIST_ID']==$VALID_PROGRAM['PROG_LIST_ID']){
                                                                echo "<option value='{$PROGRAM['PROG_LIST_ID']}' $is_disabled >{$PROGRAM['PROGRAM_TITLE']} </option>";
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    foreach ($PROGRAM_LIST as $PROGRAM) {
                                                        $bool = true;
                                                        foreach ($VALID_PROGRAM_LIST as $k =>$VALID_PROGRAM) {
                                                            if($PROGRAM['PROG_LIST_ID']==$VALID_PROGRAM['PROG_LIST_ID']){
                                                                $bool = false;
                                                                break;
                                                            }
                                                        }
                                                        if($bool){
                                                            echo "<option value='{$PROGRAM['PROG_LIST_ID']}' disabled >{$PROGRAM['PROGRAM_TITLE']} </option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <br>
                                                <button  class="btn btn-success " id="add_program">ADD</button>

                                        </div>
                                    </div>
                                    <!--                                        <form action="/upload" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="demo1-upload" novalidate="novalidate">-->
                                    <?=form_open(base_url('form/upload_program_handler'), ' enctype="multipart/form-data" class="dropzone dropzone-custom needsclick add-professors dz-clickable" onsubmit="return validateProgramSelection()" id="challan_form "',$hidden);?>


<!--                                    <div class="row">-->
<!--                                        <div class="col-lg-4">-->
<!---->
<!--                                        </div>-->
<!--                                        <div class="col-lg-4">-->
<!--                                            <div class="payment-adress">-->
<!--                                                <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light">Save</button>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <table class='table'>
                                        <thead>
                                        <th>S.No</th>
                                        <th>Program</th>
                                        </thead>
                                        <tbody id="table-body-courceDetail" >

                                        </tbody>

                                <tr><td colspan="3">
                                         <span class="input-group-btn">
        <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fa fa-save"></i> Save </button>>
                                                                  </span>

                                    </td></tr>

                                </table>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>

    <?php
    if($PROGRAM_TYPE_ID==1){
        $value = CHOICE_QUANTITY_FOR_BACHELOR;
    }else if($PROGRAM_TYPE_ID==2){
        $value = CHOICE_QUANTITY_FOR_MASTER;
    }
    ?>

    var max_list = <?=$value?>;
    function validateProgramSelection(){

        var len = ($("#table-body-courceDetail tr").length);
        if(len==max_list||max_list==-1) {
            return true;
        }else{
            alertMsg("Warning","You Must select  "+max_list+" Program...!")
            return false;
        }
    }
    $("#add_program").click(function(){

        var id = $("#PROGRAM_LIST_ID").val();
        if(id==null ||id<=0){
            return;
        }
        //alert(id);
        var txt = $("#PROGRAM_LIST_ID option:selected").text();
        addDataInTable(id,txt);
    });

    function addDataInTable(id,txt){
       // minor_option_id_
        var len = ($("#table-body-courceDetail tr").length)+1;
        if(len<=max_list||max_list==-1) {
            $("#table-body-courceDetail").append("<tr id='" + id + "'><td>" + len + "</td><input type='hidden' name='minor_subject_array[]' value='" + id + "'><td>" + txt + "</td><td><input type='button' onclick=\"deleteDataInTable('" + id + "','" + txt + "');\" value='Delete' class='btn btn-sm btn-danger' ></td></tr>");
            $("#PROGRAM_LIST_ID option[value='" + id + "']").remove();
            $("#PROGRAM_LIST_ID").siblings("[value='" + id + "']").remove();
        }else{
            alertMsg("Warning","You can select maximum "+max_list+" subject...!")
        }

    }
    function deleteDataInTable(elementNo,txt){
        //alert("aaa");
        //$(elementNo).parent().parent().remove();

        $("#table-body-courceDetail tr[id='"+elementNo+"']").remove();
        $("#PROGRAM_LIST_ID").append("<option value=\""+elementNo+"\" >"+txt+"</option>");
        $("#table-body-courceDetail tr").each(function(index,elem){
            var no = (index +1);
            var d = $(elem).children().get(0);
            $(d).html(no);

        });
     //   sort();
    }


</script>
