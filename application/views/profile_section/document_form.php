<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/17/2020
 * Time: 5:05 PM
 */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="review-content-section">
            <?=form_open('', 'id="document_upload" onsubmit="event.preventDefault();"');?>

            <div id="doc_form_msg"></div>
            <hr>
            <h3>Upload Supporting Documents</h3>
            <hr>
            <div class="row">
                <?php
                $condidtions = ($user['STATUS']!='C');
                if($user['IS_CNIC_PASS']==='P'){
                    ?>

                    <div class="col-md-6">
                        <div style="margin-top:35px">

                            <label for="exampleInput1" class="bmd-label-floating">Upload Passport Front Side
                                <span class="text-danger">*</span>
                            </label>
                            <br>
                            <?php
                            $image_path = "";

                            $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                            if($user['PASSPORT_FRONT_IMAGE'] != ""){
                                $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['PASSPORT_FRONT_IMAGE'];
                                $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['PASSPORT_FRONT_IMAGE'];

                            }
                            ?>

                            <img src="<?php echo $image_path_default; ?>" alt="Passport Front Side " class="img-table-certificate" id="passport-front-image-view"  width="150px" height="150px" name="passport-front-image-view" >
                            <?php
                            if($condidtions) {
                                ?>
                                <input type="file" name="passport_front_image" id="passport_front_image"
                                       onchange="changeImage(this,'passport_front_image','passport-front-image-view',500)"
                                       accept=".jpg,.png,.jpeg">
                                <input type="text" name="passport_front_image1" id="passport_front_image1"
                                       value="<?php echo $image_path; ?>" hidden>
                                <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="margin-top:35px">

                            <label for="exampleInput1" class="bmd-label-floating">Upload Passport Back Side
                                <span class="text-danger">*</span>
                            </label>
                            <br>
                            <?php
                            $image_path = "";

                            $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                            if($user['PASSPORT_BACK_IMAGE'] != ""){
                                $image_path_default =itsc_url(). EXTRA_IMAGE_PATH.$user['PASSPORT_BACK_IMAGE'];
                                $image_path =itsc_url(). EXTRA_IMAGE_PATH.$user['PASSPORT_BACK_IMAGE'];

                            }
                            ?>

                            <img src="<?php echo $image_path_default; ?>" alt="Passport Back Side " class="img-table-certificate" id="passport-back-image-view"  width="150px" height="150px" name="passport-back-image-view" >
                            <?php
                            if($condidtions) {
                            ?>
                            <input type="file" name="passport_back_image" id="passport_back_image"                       onchange="changeImage(this,'passport_back_image','passport-back-image-view',500)" accept=".jpg,.png,.jpeg">
                            <input type="text" name="passport_back_image1" id="passport_back_image1" value="<?php echo $image_path; ?>" hidden>
                            <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                }else{
                    ?>
                    <div class="col-md-6">
                        <div style="margin-top:35px">

                            <label for="exampleInput1" class="bmd-label-floating">Upload CNIC Front Side / Form B
                                <span class="text-danger">*</span>
                            </label>
                            <br>
                            <?php
                            $image_path = "";

                            $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                            if($user['CNIC_FRONT_IMAGE'] != ""){
                                $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['CNIC_FRONT_IMAGE'];
                                $image_path =itsc_url(). EXTRA_IMAGE_PATH.$user['CNIC_FRONT_IMAGE'];

                            }
                            ?>

                            <img src="<?php echo $image_path_default; ?>" alt="Cnic Front Side " class="img-table-certificate" id="cnic-front-image-view"  width="150px" height="150px" name="cnic-front-image-view" >
                            <?php
                            if($condidtions) {
                            ?>
                            <input type="file" name="cnic_front_image" id="cnic_front_image"                       onchange="changeImage(this,'cnic_front_image','cnic-front-image-view',500)" accept=".jpg,.png,.jpeg">
                            <input type="text" name="cnic_front_image1" id="cnic_front_image1" value="<?php echo $image_path; ?>" hidden>
                            <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="margin-top:35px">

                            <label for="exampleInput1" class="bmd-label-floating">Upload CNIC Back Side / Form B
                                <span class="text-danger">*</span>
                            </label>
                            <br>
                            <?php
                            $image_path = "";

                            $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                            if($user['CNIC_BACK_IMAGE'] != ""){
                                $image_path_default =itsc_url(). EXTRA_IMAGE_PATH.$user['CNIC_BACK_IMAGE'];
                                $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['CNIC_BACK_IMAGE'];

                            }
                            ?>
                            <img src="<?php echo $image_path_default; ?>" alt="Cnic Back Side " class="img-table-certificate" id="cnic-back-image-view"  width="150px" height="150px" name="cnic-back-image-view" >
                            <?php
                            if($condidtions) {
                            ?>
                            <input type="file" name="cnic_back_image" id="cnic_back_image"                       onchange="changeImage(this,'cnic_back_image','cnic-back-image-view',500)" accept=".jpg,.png,.jpeg">
                            <input type="text" name="cnic_back_image1" id="cnic_back_image1" value="<?php echo $image_path; ?>" hidden>
                            <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div style="margin-top:35px">

                        <label for="exampleInput1" class="bmd-label-floating">Upload Domicile Certificate <span class="text-danger">*</span></label>
                        <br>
                        <?php
                        $image_path = "";

                        $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";

                        if($user['DOMICILE_IMAGE'] != ""){
                            $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_IMAGE'];
                            $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_IMAGE'];

                        }
                        ?>

                        <img src="<?php echo $image_path_default; ?>" alt="Domicile Image" class="img-table-certificate" id="domicile-image-view"  width="150px" height="150px" name="domicile-image-view" >
                        <?php
                        if($condidtions) {
                        ?>
                        <input type="file" name="domicile_image" id="domicile_image"                       onchange="changeImage(this,'domicile_image','domicile-image-view',500)" accept=".jpg,.png,.jpeg">
                        <input type="text" name="domicile_image1" id="domicile_image1" value="<?php echo $image_path; ?>" hidden>
                        <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="margin-top:35px">

                        <label for="exampleInput1" class="bmd-label-floating">Upload PRC Form-C <span class="text-danger">*</span>
                        </label>
                        <br>
                        <?php
                        $image_path = "";

                        $image_path_default =base_url()."dash_assets/img/avatar/docavtar.png";
                        if($user['DOMICILE_FORM_C_IMAGE'] != ""){
                            $image_path_default = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_FORM_C_IMAGE'];
                            $image_path = itsc_url().EXTRA_IMAGE_PATH.$user['DOMICILE_FORM_C_IMAGE'];

                        }
                        ?>
                        <img src="<?php echo $image_path_default; ?>" alt="Domicile Form-C Image" class="img-table-certificate" id="domicile-formc-image-view"  width="150px" height="150px" name="domicile-formc-image-view" >
                        <?php
                        if($condidtions) {
                            ?>
                            <input type="file" name="domicile_formc_image" id="domicile_formc_image"
                                   onchange="changeImage(this,'domicile_formc_image','domicile-formc-image-view',500)"
                                   accept=".jpg,.png,.jpeg">
                            <input type="text" name="domicile_formc_image1" id="domicile_formc_image1"
                                   value="<?php echo $image_path; ?>" hidden>
                            <span class="text-danger">Make Sure Image must be clear and Image size should be less than 500KB</span>
                            <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
            <br>
            <hr>
            <br>
            <div class="row">
                <div class="col-lg-3">
                </div>
                <?php
                if($condidtions) {
                    ?>
                    <div class=" col-lg-2">
                        <div class="payment-adress">
                            <button onclick="updateDocument('N')" type="submit"
                                    class="btn btn-primary waves-effect waves-light">Upload Documents
                            </button>
                        </div>
                    </div>
                    <?php
                }

                ?>
                <div class="col-lg-2">
                    <div class="payment-adress">
                        <button type="button"   onclick = "updateDocument('Y')" class="btn btn-primary  waves-effect waves-light">Upload Documents & Next</button>
                    </div>
                </div>

            </div>
            </form>
        </div>
    </div>
</div>
<script>
    let prog_id = <?=$application['PROGRAM_TYPE_ID']?>;
    function updateDocument(is_next){

        var form = $('#document_upload')[0];
        // Create an FormData object
        var data = new FormData(form);
        data.append(csrfName, csrfHash);
        data.append("action", 'update_dcoument');

        $('.preloader').fadeIn(700);

        jQuery.ajax({
            url: "<?=base_url()?>candidate/uploadDocuments",
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            data: data,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) * 100;
                        percentComplete = Math.round(percentComplete);
                        $("#pre_text").html("<br><br><h3>Uploading "+percentComplete+"%</h3>");
                        console.log(percentComplete);
                    }
                }, false);
                return xhr;
            },
            success: function (data, status) {
                $('.preloader').fadeOut(700);
                $('input[name="csrf_form_token"]').val(data.csrfHash);
                csrfHash =data.csrfHash;
                $('#doc_form_msg').html("");
                //alertMsg("Success",data.MESSAGE);
                if(is_next=='Y'){
                   // next_tab('education_tab');
                  // if(prog_id==2){
                   window.location.href="<?=base_url()?>Candidate/add_inter_qualification";    
                  // }else{
                       
                  // }
                   
                }

            },
            beforeSend:function (data, status) {

                $('#doc_form_msg').html('Loading...!');


            },
            error:function (data, status) {
                var value = data.responseJSON;

                alertMsg("Error",value.MESSAGE);
                if(value.csrfHash){
                    $('input[name="csrf_form_token"]').val(value.csrfHash);
                    csrfHash =value.csrfHash;
                }

                $('#doc_form_msg').html(value.MESSAGE);
                $('.preloader').fadeOut(700);



            },
        });

    }
</script>
