<div style="height:100px"></div>
<div class="container">
          <!--<div class="tim-typo">-->
              <h3 class="title text-center">
                Forget Password</h3>
            <!--</div>-->
    <div class="row text-center">
    <p class='text-danger' style="font-size:14pt; font-weight:bold; font-family:'Times New Roman', Times, serif">Type your CNIC NO (without dashes) to reset your password you will get an email of password reset request on your registered email address.</h2>
                           
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding-left: 40px;">
                    <div class="login form-inline">
                        <?=form_open('forget/forgetHandler')?>
                        <div class='form-group'>
                                <label for="" style="font-size:17px">ENTER YOUR  CNIC / B-FORM NO <span class="text-danger"> *</span></label>
                                <input type="text" name='cnic_no' class='form-control' required>
                        <button type='submit' name='submit' class='btn btn-success btn-md'>Request Password Reset Email</button>  
                        </div>      
                        </div>
                 </div>
        </div>
    </div>