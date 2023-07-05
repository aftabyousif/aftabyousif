<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 03/10/2022
 * Time: 12:45 PM
 *  [ADMISSION_SESSION_ID] => 124
    [CAMPUS_ID] => 3
    [SESSION_ID] => 9
    [PROGRAM_TYPE_ID] => 2
    [ADMISSION_START_DATE] => 2022-09-19
    [ADMISSION_END_DATE] => 2022-10-24
    [CREATED_ON] => 2022-09-12 00:00:00
    [DISPLAY] => 1
    [PASSING_SCORE] => 
    [PASSING_OUT] => 
    [REMARKS] => MASTER NAUSHEHRO FEROZE CAMPUS
 */
$program_type_id = $remarks=$passing_score= $created_on=$admission_end_date = $admission_start_date=$campus_id=$admisssion_session_id=$session_id='';
 
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class='card'>
        <div class='card-body'>
            <form action='<?=base_url()."AdminPanel/add_data_in_candidate_selection_list_handler"?>' onsubmit='return confirm("Are You Sure?")' method='post'>
               
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Session<span class="text-danger">*</span></label>
                        <select  onchange='admission_list()' id='session' name='session' class='form-control'>
                            <option value='0'>Choose</option>
                            <?php
                            foreach($session  as $sess){
                                $selected = "";
                                if($sess['SESSION_ID'] == $session_id){
                                $selected = "selected";    
                                }
                                echo "<option value='{$sess['SESSION_ID']}' $selected >{$sess['YEAR']}</option>";
                            }
                            ?>
                                
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                     <div class='form-group'>
                         <label>Degree Program<span class="text-danger">*</span></label>
                    <select onchange='admission_list()' id='program_type'  name='program_type' class='form-control'>
                         
                            <option value='0'>Choose</option>
                            <?php
                            foreach($program_types  as $pt){
                                 $selected = "";
                                if($pt['PROGRAM_TYPE_ID'] == $program_type_id){
                                $selected = "selected";    
                                }
                                echo "<option value='{$pt['PROGRAM_TYPE_ID']}' $selected >{$pt['PROGRAM_TITLE']}</option>";
                            }
                            ?>
                                
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Campus<span class="text-danger">*</span></label>
                    <select onchange='admission_list()' id='campus'  name='campus' class='form-control'>
                            <option value='0'>Choose</option>
                            <?php
                            foreach($campus  as $cam){
                                $selected = "";
                                if($campus_id == $cam['CAMPUS_ID']){
                                  $selected = "selected";      
                                }
                                echo "<option value='{$cam['CAMPUS_ID']}' $selected>{$cam['NAME']}</option>";
                            }
                            ?>
                                
                        </select>

                    </div>
                </div>
                 <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Shift<span class="text-danger">*</span></label>
                        <select  onchange='admission_list()' name='shift' id='shift' class='form-control'>
                            <option value='0'>Choose</option>
                            <option value='1'>Morning</option>
                            <option value='2'>Evening</option>        
                        </select>

                    </div>
                </div>
            </div>
            <div class='row'>
                  <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Admission List<span class="text-danger">*</span></label>
                        <select  onchange='get_selection_list_data()' name='ADMISSION_LIST_ID' id='ADMISSION_LIST_ID' class='form-control'>
                            <option value='0'>Choose</option>
                              
                        </select>

                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Is Provisional?<span class="text-danger">*</span></label>
                        <select onchange='get_selection_list_data()' name='IS_PROVISIONAL' id='IS_PROVISIONAL' class='form-control'>
                            <option value='Y'>YES</option>
                            <option value='N'>NO</option>
                              
                        </select>

                    </div>
                </div>

                
            </div>
            
            <div class='row'>
                <div class='col-md-4'>
                    <br><br>
                     <?php
                        echo "<button  class='btn btn-success' name='add' type='submit'>Add</button>";
                    ?>
                    
                </div>
                </div>
            </form>
        </div>
    </div>
      <div class='card'>
        <div class='card-body'>
            <table class='table table-borderd' id='data'>
                
            </table>
        </div>
    </div>
    
</div>
<script>
function get_selection_list_data(){
        $('#data').html("");
        let ADMISSION_LIST_ID = $('#ADMISSION_LIST_ID').val();
        let IS_PROVISIONAL = $('#IS_PROVISIONAL').val();
        if(ADMISSION_LIST_ID>0){
            jQuery.ajax({
                    url:'https://admission.usindh.edu.pk/admission/Selection_list_report/getSelectionListByListId',
                    method: 'POST',
                    data: {ADMISSION_LIST_ID:ADMISSION_LIST_ID,IS_PROVISIONAL:IS_PROVISIONAL},
                    dataType: 'json',
                    success: function (response) {
                        let i=0;
                        $.each(response, function (index,value) {
                            // i++;
                            // if (value['REMARKS'] == null)
                            // 	var remarks = '';
                            let keys=Object.keys(value);
                            if(index==0){
                                var row ="<tr>";
                                for(let k=0;k<keys.length;k++){
                                row += "<td>"+keys[k]+"</td>";
                                }
                                row +="<tr>";
                                $('#data').append(row);
                            }
                            
                                var  row ="<tr>";
                                for(let k=0;k<keys.length;k++){
                                row += "<td>"+value[keys[k]]+"</td>";
                                }
                                row +="<tr>";
                                $('#data').append(row);
                        });



                    },
                    beforeSend:function (data, status) {


                        $('#alert_msg_for_ajax_call').html("LOADING...!");
                    },
                    error:function (data, status) {
                        alertMsg("Error",data.responseText);
                        $('#alert_msg_for_ajax_call').html("Something went worng..!");
                        $("#ADMISSION_LIST_ID").html("<option value='0'>Choose</option>");
                      //  $('#').html("")
                    },
                });
        }
        
    
}
    function admission_list(){
          $("#ADMISSION_LIST_ID").html("<option value='0'>Choose</option>");
        let shift =  $('#shift').val();
        let campus = $('#campus').val();
        let program_type = $('#program_type').val();
        let session = $('#session').val();
        if(shift>0&&campus>0&&program_type>0&&session>0){
            jQuery.ajax({
                    url:'https://admission.usindh.edu.pk/admission/SelectionList/getAdmissionList',
                    method: 'POST',
                    data: {SHIFT_ID:shift,PROG_TYPE_ID:program_type,CAMPUS_ID:campus,SESSION_ID:session},
                    dataType: 'json',
                    success: function (response) {
                        let i=0;
                        $.each(response, function (index,value) {
                            // i++;
                            // if (value['REMARKS'] == null)
                            // 	var remarks = '';
                            let option="";
                            option+= "<option value='"+value['ADMISSION_LIST_ID']+"'>"+value['LIST_TITLE']+" -> "+value['LIST_NO']+"</option>";
                            $("#ADMISSION_LIST_ID").append(option);
                        });



                    },
                    beforeSend:function (data, status) {


                        $('#alert_msg_for_ajax_call').html("LOADING...!");
                    },
                    error:function (data, status) {
                        alertMsg("Error",data.responseText);
                        $('#alert_msg_for_ajax_call').html("Something went worng..!");
                        $("#ADMISSION_LIST_ID").html("<option value='0'>Choose</option>");
                      //  $('#').html("")
                    },
                });
        }
        
    }
</script>