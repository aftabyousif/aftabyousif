<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 03/10/2022
 * Time: 12:45 PM
 */
 $session_code = $year=$batch_remarks=$date=$session_id='';
 if($session_obj){
     $session_code = $session_obj['SESSION_CODE'];
     $year = $session_obj['YEAR'];
     $batch_remarks = $session_obj['BATCH_REMARKS'];
     $date = $session_obj['DATE'];
     $remarks = $session_obj['REMARKS'];
     $session_id = $session_obj['SESSION_ID'];
 }
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class='card'>
        <div class='card-body'>
            <form action='<?=base_url()."AdminPanel/view_all_session_handler"?>' onsubmit='return confirm("Are You Sure?")' method='post'>
               
            <div class='row'>
                <div class='col-md-4'>
                    <div class='form-group'>
                        <label>Sessoin Code<span class="text-danger">*</span></label>
                    <input required value='<?=$session_code?>' type='text' name='sessoin_code' class='form-control'/>
                    </div>
                </div>
                <div class='col-md-4'>
                     <div class='form-group'>
                    <label>Year<span class="text-danger">*</span></label>
                    <input required value='<?=$year?>'  type='number' name='year' class='form-control'/>
                    </div>
                </div>
                <div class='col-md-4'>
                    <div class='form-group'>
                        <label>Batch Remarks<span class="text-danger">*</span></label>
                    <input required value='<?=$batch_remarks?>' type='text' name='batch_remarks' class='form-control'/>
                    </div>
                </div>
                
            </div>
              <div class='row'>
                <div class='col-md-4'>
                    <div class='form-group'>
                        <label>Date<span class="text-danger">*</span></label>
                    <input required value='<?=$date?>' type='date' name='date' class='form-control'/>
                    </div>
                </div>
                <div class='col-md-4'>
                     <div class='form-group'>
                    <label>Remarks<span class="text-danger">*</span></label>
                    <input required value='<?=$remarks?>'  type='text' name='remarks' class='form-control'/>
                    </div>
                </div>
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
                            <th>SESSION ID</th>
                            <th>SESSION CODE</th>
                            <th>YEAR</th>
                            <th>BATCH REMARKS</th>
                            <th>DATE</th>
                            <th>REMARKS</th>
                            <th>ACTION</th>
                        </tr>";
                foreach($session_data as $session){
                    echo "<tr>
                            <td>{$session['SESSION_ID']}</td>
                            <td>{$session['SESSION_CODE']}</td>
                            <td>{$session['YEAR']}</td>
                            <td>{$session['BATCH_REMARKS']}</td>
                            <td>{$session['DATE']}</td>
                            <td>{$session['REMARKS']}</td>
                            <td><a href='".base_url()."AdminPanel/view_all_session?id={$session['SESSION_ID']}'>Edit</a></td>
                        </tr>";
                }
                ?>
            </table>    
        </div>    
    </div>    
</div>