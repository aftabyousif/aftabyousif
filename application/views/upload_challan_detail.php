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
                                    $hidden = array("APPLICATION_ID"=>$APPLICATION_ID);
                                    ?>
                            <!--                                        <form action="/upload" class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="demo1-upload" novalidate="novalidate">-->
                            <?=form_open('form/challan_upload_handler', 'class="dropzone dropzone-custom needsclick add-professors dz-clickable" id="base_profile_form"',$hidden);?>

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Bank Branch
                                            <span class="text-danger">*</span></label>
                                        <br>
                                              <select   id="BRANCH_ID" class="js-example-basic-single form-control "  name="BRANCH_ID">
                                            <option value="0">--Choose--</option>
                                            <?php

                                            foreach ($bank_branches as $bank_branch) {
                                                $select = "";

                                                echo "<option   value='{$bank_branch['BRANCH_ID']}'  >{$bank_branch['BRANCH_CODE']} &nbsp;&nbsp;{$bank_branch['BRANCH_NAME']}</option>";
                                            }
                                            ?>

                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Challan Amount
                                            <span class="text-danger">*</span></label>
                                        <input  type="text" id="CHALLAN_AMOUNT" class="form-control allow-string" placeholder="CHALLAN AMOUNT" name="CHALLAN_AMOUNT" >
                                        <input type="text" id="USER_ID" class="" name="USER_ID" value="" hidden>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Challan Paid Date
                                            <span class="text-danger">* &nbsp;<small>dd/mm/yyyy</small></span></label>
                                        <div class="form-group data-custon-pick" id="data_2">
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input  type="text" id="CHALLAN_PAID_DATE"  name="CHALLAN_PAID_DATE" class="form-control" value="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group res-mg-t-15">
                                        <label for="exampleInput1" class="bmd-label-floating">Profile Image
                                            <span class="text-danger">*</span>
                                        </label><br>
                                        <?php

                                        $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                                        $image_path = "";
//                                        if($user['PROFILE_IMAGE'] != ""){
//                                            $image_path_default = PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];
//                                            $image_path = PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];
//
//                                        }
                                        ?>

                                        <img src="<?php echo $image_path_default; ?>" alt="CHALLAN IMAGE" id="challan-image-view"  class="img-table-certificate"  width="150px" height="150px" name="challan-image-view" >
                                        <input type="file" name="challan_image" id="challan_image"   onchange="changeImage(this,'challan_image','challan-image-view',200)" accept=".jpg,.png,.jpeg" value="<?php echo $image_path; ?>">
                                        <input type="text" name="challan_image1" id="challan_image1" value="<?php echo $image_path; ?>" hidden>
                                        <span class="text-danger">Image must be passport size with white background and image size should be less than 200kb</span>

                                    </div>
                                </div>
                            </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="payment-adress">
                                                <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light">Save</button>
                                            </div>
                                        </div>
                                    </div>


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
    $( '.img-table-certificate' ).click(function() {
        alertImage('Image',$(this).attr('src'));
    });
</script>
<style>
    .select2-container--default .select2-results__option[aria-disabled=true] {
        color: #f90000;
    }
</style>