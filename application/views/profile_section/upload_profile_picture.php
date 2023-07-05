<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 9/18/2020
 * Time: 12:31 AM
 */
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <form action="<?=base_url()."Candidate/upload_profile_image_handler"?>"method="post" onsubmit="return confirm('Please make sure your proflie picture must be valid because once you upload your profile picture you can not change it again')" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="form-group res-mg-t-15">
                <img src="<?php echo base_url()."dash_assets/img/cp1.jpg"; ?>"   width="150px" height="150px">

                <img src="<?php echo base_url()."dash_assets/img/cp2.jpg"; ?>"   width="150px" height="150px">


                <img src="<?php echo base_url()."dash_assets/img/cp3.jpg"; ?>"   width="150px" height="150px">

                <img src="<?php echo base_url()."dash_assets/img/correct-photo.jpeg"; ?>"   width="150px" height="150px">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class="form-group res-mg-t-15">
                <label for="exampleInput1" class="bmd-label-floating">Profile Image
                    <span class="text-danger">*</span>
                </label><br>
                <?php

                $image_path_default =base_url()."dash_assets/img/avatar/default-avatar.png";
                $image_path = "";
                if($user['PROFILE_IMAGE'] != ""){
                    $image_path_default = itsc_url().PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];
                    $image_path = itsc_url().PROFILE_IMAGE_PATH.$user['PROFILE_IMAGE'];

                }
                ?>
                <img src="<?php echo $image_path_default; ?>" alt="Profile" class="" id="profile-image-view"  width="150px" height="150px" name="profile-image-view" >

                    <input required type="file" name="profile_image" id="profile_image"
                                            onchange="changeImage(this,'profile_image','profile-image-view',100)"
                                            accept=".jpg,.png,.jpeg" value="<?php echo $image_path; ?>">
                    <input type="text" name="profile_image1" id="profile_image1"
                           value="<?php echo $image_path; ?>" hidden>
                    <span class="text-danger">Image must be passport size with white background and image size should be less than 100KB</span>

            </div>
        </div>

    </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success">Upload Image</button>
            </div>
            <div class="col-md-4"></div>
        </div>
    </form>
</div>
