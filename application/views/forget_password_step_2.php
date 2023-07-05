<script>
  $( function() {
    $( "#datepicker" ).datepicker({ 
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: '1950:2010',
        dateFormat: 'dd/mm/yy',
    });
  });
  </script>
  
<div style="height:200px"></div>
<div class="container-fluid">
    
                <?php
            $email = "";
            $cnic_no = "";
            $seat_no = "";
            $obt_marks = "";
            $passing_year = "";

            if($this->session->has_userdata('user_data_forget_pwd') && $this->session->has_userdata('user_data_forget_pwd_qualification'))
            {
                $USER_DATA = $this->session->userdata("user_data_forget_pwd");
                // $USER_QUALIFICATION = $this->session->userdata("user_data_forget_pwd_qualification");
                $USER_DATA = json_decode($USER_DATA,true);
                // $USER_QUALIFICATION = json_decode($USER_QUALIFICATION,true);
                $email = $USER_DATA['EMAIL'];
                $cnic_no = $USER_DATA['CNIC_NO'];
            }else
            {
                redirect("forget");
            }
            ?>
            
             <p class='text-center' style="font-size:15pt; font-weight:normal; font-family:'Times New Roman', Times, serif">
                Your password reset request email sent on <span class='text-danger font-weight-bold' style='font-weight:bold; font-size:19pt'>[ <?=$email?> ]</span>, if the mentioned email is not in use  <span class='' style='font-size:15pt'> <a href='javascript:void(0);' onclick="$('#login').show();"> then CLICK HERE.</a> </span>
            </p>
            
    <div class="row" style='display:center'>
        
                    <div class="offset-2 col-lg-5 col-md-5 col-sm-12 col-xs-12" style="padding-left: 40px;">
                        <div class="login form-horizontal" style='display:none' id='login'>
                        <?=form_open('forget/step2_process')?>
                        <div class='form-group'>
                            <label for="" style="font-size:17px">CNIC / B-FORM NO <span class="text-danger">*</span></label>
                            <input type="text" name='cnic_no' class='form-control' value="<?=$cnic_no?>" required>
                        </div>
                        
                        <div class='form-group'>
                            <label for="" style="font-size:17px">DATE OF BIRTH <span class="text-danger">*</span></label>
                            <input type="text" name='dob' class='form-control' id="datepicker" readonly required>
                        </div>
                        <div class='form-group'>
                             <!--<div class="tim-typo">-->
                            <h4 class="title text-center">Enter your matriculation / O-Level information in the following boxes</h4>
                            <!--</div>-->
                        </div>
 
                         <div class='form-group'>
                            <label for="" style="font-size:17px">SEAT NO <span class="text-danger">*</span></label>
                            <input type="number" name='seat_no' min=0 class='form-control'  required>
                        </div>
                        
                        <div class='form-group'>
                            <label for="" style="font-size:17px">OBTAINED MARKS <span class="text-danger">*</span></label>
                            <input type="number" name='obt_marks' min=0 class='form-control'  required>
                        </div>
                        
                        <div class='form-group'>
                            <label for="" style="font-size:17px">PASSING YEAR <span class="text-danger">*</span></label>
                            <input type="number" name='passing_year' min='0' class='form-control' placeholder='2020' required>
                        </div>
                        
                        <button type='submit' name='submit' class='btn btn-primary'>Verify & Request New Password</button>        
                        </div>
                 </div>
        </div>
    </div>

    <script>
    <?php
        if($this->session->has_userdata('ALERT_MSG')){
                echo "$('#login').show();";
            }
    ?>
    </script>