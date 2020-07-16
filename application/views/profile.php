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
    function editQulification(id){

        callAjax("<?=base_url()?>Candidate/apiGetEditQualificationForm?qualification_id="+id,'qulification_form_view');
        $('.js-example-basic-single').select2();
        $('.select2').attr('style','width:100%');
        $('.disab').hide();
    }
    var name;
    var
    function deleteQulification(id){
        if(confirm("Are You Sure?\nDo You want to delete your qualification..!")){
            $('.preloader').fadeIn(700);
            var data = new FormData();
            data.append("action", 'delete_qualification');
            data.append("QUAL_ID", id);
            <?php
                $res = getcsrf($this);
            ;
            $res['csrfHash'];
            ?>
            data.append("<?=$res['csrfName']?>", <?=$res['csrfName']?>);
            jQuery.ajax({
                url: "<?=base_url()?>Candidate/apiDeleteQualification",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: data,
                success: function (data, status) {

                    $('.preloader').fadeOut(700);
                    $('#qul_form_msg').html("");
                    alertMsg("Success",data.MESSAGE);
                    getQualification();

                },
                beforeSend:function (data, status) {


                    $('#qul_form_msg').html("Loading...!");



                },
                error:function (data, status) {
                    var value = data.responseJSON;

                    alertMsg("Error",value.MESSAGE);
                    $('#qul_form_msg').html(value.MESSAGE);
                    $('.preloader').fadeOut(700);
                    getQualification();


                },
            });
        }
    }
    getQualification();
</script>

