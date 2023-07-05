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
 if($admission_session_obj){
     $admisssion_session_id = $admission_session_obj['ADMISSION_SESSION_ID'];
     $campus_id = $admission_session_obj['CAMPUS_ID'];
     $session_id = $admission_session_obj['SESSION_ID'];
     $admission_start_date = $admission_session_obj['ADMISSION_START_DATE'];
     $admission_end_date = $admission_session_obj['ADMISSION_END_DATE'];
     $created_on = $admission_session_obj['CREATED_ON'];
     $passing_score= $admission_session_obj['PASSING_SCORE'];
     $passing_out = $admission_session_obj['PASSING_OUT'];
     $remarks = $admission_session_obj['REMARKS'];
     $program_type_id = $admission_session_obj['PROGRAM_TYPE_ID'];
    
 }
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class='card'>
        <div class='card-body'>
            <form action='<?=base_url()."AdminPanel/view_all_session_handler"?>' onsubmit='return confirm("Are You Sure?")' method='post'>
               
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Session<span class="text-danger">*</span></label>
                        <select  name='session' class='form-control'>
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
                    <select  name='Program Type' class='form-control'>
                         
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
                    <select  name='campus' class='form-control'>
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
                        <label>Admission Start Date<span class="text-danger">*</span></label>
                    <input required value='<?=$admission_start_date?>' type='date' name='admission_start_date' class='form-control'/>
                    </div>
                </div>
                
            </div>
              <div class='row'>
                
                <div class='col-md-3'>
                     <div class='form-group'>
                    <label>Admission End Date<span class="text-danger">*</span></label>
                    <input required value='<?=$admission_end_date?>'  type='date' name='admission_end_date' class='form-control'/>
                    </div>
                </div>
                 <div class='col-md-3'>
                     <div class='form-group'>
                    <label>Passing Score<span class="text-danger">*</span></label>
                    <input required value='<?=$passing_score?>'  type='number' name='passing_score' class='form-control'/>
                    </div>
                </div>
                <div class='col-md-3'>
                     <div class='form-group'>
                    <label>Total Score <span class="text-danger">*</span></label>
                    <input required value='<?=$passing_out?>'  type='number' name='passing_out' class='form-control'/>
                    </div>
                </div>
                <div class='col-md-3'>
                     <div class='form-group'>
                    <label>Remarks<span class="text-danger"></span></label>
                    <input  value='<?=$remarks?>'  type='number' name='remarks' class='form-control'/>
                    </div>
                </div>
                
            </div>
            <div class='row'>
                <div class='col-md-4'>
                    <br><br>
                     <?php
                    if($session_id){
                        echo "<input name='session_id' value='$session_id' hidden/>";
                        echo "<button  class='btn btn-warning' name='update' type='submit'>Update</button>";
                    }else{
                        echo "<button  class='btn btn-success' name='add' type='submit'>Add</button>";
                    }
                    ?>
                    
                </div>
                </div>
            </form>
        </div>
    </div>
    <div class='card'>
        <div class='card-body'>
            <table class='table table-borderd'>
                <?php
                echo "<tr>
                            <th>ADMISSION SESSION ID</th>
                            <th>CAMPUS NAME</th>
                            <th>YEAR</th>
                            <th>PROGRAM TITLE</th>
                            <th>ADMISSION START DATE</th>
                            <th>ADMISSION END DATE</th>
                            <th>CREATE DATE</th>
                            <th>DISPLAY</th>
                            <th>PASSING SCORE</th>
                            <th>PASSING OUT</th>
                            <th>REMARKS</th>
                            <th>ACTION</th>
                        </tr>";
                foreach($admission_session_data as $admission_session){
                    echo "<tr>
                            <td>{$admission_session['ADMISSION_SESSION_ID']}</td>
                            <td>{$admission_session['NAME']}</td>
                            <td>{$admission_session['YEAR']}</td>
                            <td>{$admission_session['PROGRAM_TITLE']}</td>
                            <td>{$admission_session['ADMISSION_START_DATE']}</td>
                            <td>{$admission_session['ADMISSION_END_DATE']}</td>
                            <td>{$admission_session['CREATED_ON']}</td>
                            <td>{$admission_session['DISPLAY']}</td>
                            <td>{$admission_session['PASSING_SCORE']}</td>
                            <td>{$admission_session['PASSING_OUT']}</td>
                            <td>{$admission_session['REMARKS']}</td>
                            <td><a href='".base_url()."AdminPanel/view_all_admission_session?id={$admission_session['ADMISSION_SESSION_ID']}'>Edit</a></td>
                        </tr>";
                }
                ?>
            </table>    
        </div>    
    </div>    
</div>