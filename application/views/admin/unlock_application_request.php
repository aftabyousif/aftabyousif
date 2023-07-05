<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 9/16/2020
 * Time: 12:45 PM
 */
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="review-content-section">
                <div id="dropzone1" class="pro-ad">

                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">


                    </div>
                    <form action="<?=base_url('AdminPanel/student_application_unlock')?>" method="post">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Please type your account password for the purpose of two step verification.
                            </label>
                            <input type="password" id="USER_PASSWORD" class="form-control" placeholder="" name="USER_PASSWORD">
                        </div>
                    </div>
                    <!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">-->
                        <input type="text"hidden name="IS_UNLOCK" value="Y">
                    <div class="button-style-four btn-mg-b-10">
                        <br/>
                        <button type="Submit"  class="btn btn-custon-rounded-four btn-danger"><i class="fa fa-key " aria-hidden="true"></i> Unlock</button>


                    </div>
                    </form>
                    <!--<button type="button" onclick="getUserByCnic()" class="btn btn-primary btn-lg waves-effect waves-light">Search</button>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="basic_data">

            </div>
        </div>
    </div>

</div>
