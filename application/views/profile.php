<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/11/2020
 * Time: 4:22 PM
 */
$readonly ="";
?>
<script >
    <?php
    $res = getcsrf($this);
    ?>
    var csrfName="<?=$res['csrfName']?>";
    var csrfHash="<?=$res['csrfHash']?>";

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

</script>
<div id = "min-height" class="container-fluid" style="padding:30px">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-payment-inner-st">
                <ul id="myTabedu1" class="tab-review-design">
                    <li class="active"><a href="#basic_information">Basic Information</a></li>
                    <li class=""><a href="#education"> Education Information</a></li>
                    <li class=""><a href="#documents"> Additional Documents</a></li>
                    <li class=""><a href="#experiances"> Experiances</a></li>

                </ul>
                <div id="myTabContent" class="tab-content custom-product-edit">
                    <div class="product-tab-list tab-pane fade active in" id="basic_information">
                    <?php   require_once "profile_section/basic_information.php";?>
                    </div>
                    <div class="product-tab-list tab-pane fade" id="education">
                        <?php   require_once "profile_section/qualification_information.php";?>
                    </div>
                    <div class="product-tab-list tab-pane fade" id="documents">
                        <?php   require_once "profile_section/document_form.php";?>
                    </div>
                    <div class="product-tab-list tab-pane fade" id="experiances">
                        <?php   require_once "profile_section/experiances.php";?>
                    </div>
                </div>
                <div id="alert_msg_for_ajax_call"></div>
                </div>
            </div>
        </div>
</div>

<script>

    $( '.img-table-certificate' ).click(function() {
        alertImage('Image',$(this).attr('src'));
    });
</script>

