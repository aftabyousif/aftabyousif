<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 4:22 PM
 */
$readonly ="";
?>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-payment-inner-st">
                <ul id="myTabedu1" class="tab-review-design">
                    <li class="active"><a href="#basic_information">Basic Information</a></li>
                    <li class=""><a href="#education"> Education Information</a></li>

                </ul>
                <div id="myTabContent" class="tab-content custom-product-edit">
                    <div class="product-tab-list tab-pane fade active in" id="basic_information">
                    <?php   require_once "profile_section/basic_information.php";?>
                    </div>
                    <div class="product-tab-list tab-pane fade" id="education">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="review-content-section">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="top-margin">
                                                <button class='btn btn-success btn-md btn-round disab' id="add_qulification"">+ Add</button>
                                            </div>
                                        </div>
                                    </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="top-margin" id='qulification_form_view'>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="top-margin" id="qulification_table_view">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="alert_msg_for_ajax_call"></div>
                </div>
            </div>
        </div>
</div>
<script>
    function callAjax(url,set_id,msg_id="alert_msg_for_ajax_call"){
        jQuery.ajax({
            url:url ,
            async:false,
            success: function (data, status) {
                $('#'+msg_id).html("");
                $('#'+set_id).html(data);
            },
            beforeSend:function (data, status) {
                $('#'+msg_id).html("LOADING...!");
            },
            error:function (data, status) {
                alertMsg("Error",data.responseText);
                $('#'+msg_id).html("Something went worng..!");
            },
        });
    }
    $('#add_qulification').click(function (event) {
        event.preventDefault();

        callAjax("<?=base_url()?>Candidate/apiGetAddQualificationForm","qulification_form_view");
        $('.js-example-basic-single').select2();
        $('.select2').attr('style','width:100%');
        $('.disab').hide();


    });

    function getQualification(){
        callAjax("<?=base_url()?>Candidate/apiGetQualificationList","qulification_table_view");
    }
    getQualification();
</script>

