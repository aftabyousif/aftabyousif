<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 9/18/2020
 * Time: 12:31 AM
 */
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <form action="<?=base_url()."form/update_contact_info"?>"method="post" onsubmit="return confirm('Please make sure your proflie picture must be valid because once you upload your profile picture you can not change it again')" enctype="multipart/form-data">
        <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="exampleInput1" class="bmd-label-floating"> Mobile Code
                            <span class="text-danger">*</span></label>
                        <select  class="js-example-basic-single form-control mb-3" name="MOBILE_CODE" id="MOBILE_CODE">


                               <option value='0092' $select >PAKISTAN &nbsp;&nbsp; 0092</option>";

                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="exampleInput1" class="bmd-label-floating"> Mobile No
                            <span class="text-danger">*</span></label>
                        <input type="text" id="MOBILE_NO" class="form-control allow-mobile-number" name="MOBILE_NO" value="<?=$user['MOBILE_NO']?>"  >`
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">


                <div class="form-group">
                    <label for="exampleInput1" class="bmd-label-floating"> Email
                        <span class="text-danger">*</span></label>
                    <input  type="email" id="EMAIL" class="form-control" name="EMAIL" value="<?=$user['EMAIL']?>"  >

                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="exampleInput1" class="bmd-label-floating"> Whatsapp No
                        <span class="text-danger">* (0342xxxxxxx)</span></label>
                    <input type="text" id="WHATSAPP_NO" class="form-control allow-mobile-number" name="WHATSAPP_NO" value="<?=$user['WHATSAPP_NO']?>"  >`
                </div>
            </div>





        </div>
        <br>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success">Update Contact Information</button>
            </div>
            <div class="col-md-4"></div>
        </div>
    </form>
</div>
