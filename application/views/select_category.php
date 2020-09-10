<?php
/**
 * Created by PhpStorm.
 * User: JAVED
 * Date: 2020-09-03
 * Time: 2:17 PM
 */
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="review-content-section">
                        <div id="dropzone1" class="pro-ad">
                            <div class="card">
                                <div class="card-body">
									<tabel class="table">
                                    <?php 
									//prePrint($formcategory)
										
									foreach($formcategory as $value){
										
										?>
										<label><?php echo $value['FORM_CATEGORY_NAME']  ?></label>
										<input type="checkbox" name="category<?php echo $value['FORM_CATEGORY_ID']; ?>" id="category">
										<?php
									}
									?>
									</tabel>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>