<?php
/**
 * Created by PhpStorm.
 * User: Kashif Shaikh
 * Date: 7/17/2020
 * Time: 11:06 AM
 */
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="review-content-section">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="top-margin">
                        <button class='btn btn-success btn-md btn-round disab_experiance' id="add_experiance"><i class='fa fa-plus'></i> Add</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="top-margin" id='experiance_form_view'>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="top-margin" id="experiance_table_view">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5">
                </div>
                <div class="col-lg-2">
                    <div class="payment-adress">
                        <button type="button"onclick = "next_tab('documents_tab')" class="btn btn-primary btn-lg waves-effect waves-light">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>



    $('#add_experiance').click(function (event) {
        event.preventDefault();

        callAjax("<?=base_url()?>Candidate/apiGetAddExperianceForm","experiance_form_view");
        $('.js-example-basic-single').select2();
        $('.select2').attr('style','width:100%');
        $('.disab_experiance').hide();


    });

    function getExperiance(){
        callAjax("<?=base_url()?>Candidate/apiGetExperianceList","experiance_table_view");
    }

    /*function editQualification(id){

        callAjax("Candidate/apiGetEditQualificationForm?qualification_id="+id,'qulification_form_view');
        $('.js-example-basic-single').select2();
        $('.select2').attr('style','width:100%');
        $('.disab').hide();
    }*/

    function deleteExperiance(id){
        if(confirm("Are You Sure?\nDo You want to delete your Experiance..!")){

            $('.preloader').fadeIn(700);
            var data = new FormData();
            data.append("experiance_id", id);

            data.append(csrfName, csrfHash);
            jQuery.ajax({
                url: "<?=base_url()?>Candidate/apiDeleteExperiance",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: data,
                success: function (data, status) {

                    $('.preloader').fadeOut(700);
                    $('#exp_form_msg').html("");
                    alertMsg("Success",data.MESSAGE);
                    csrfHash = data.csrfHash;
                    getExperiance();

                },
                beforeSend:function (data, status) {


                    $('#exp_form_msg').html("Loading...!");



                },
                error:function (data, status) {
                    var value = data.responseJSON;

                    alertMsg("Error",value.MESSAGE);
                    $('#exp_form_msg').html(value.MESSAGE);
                    csrfHash = value.csrfHash;
                    $('.preloader').fadeOut(700);
                    getExperiance();


                },
            });
        }
    }


    function checkIsContinue() {
        if($('#is_job_continue').is(':checked')){

            $('#end_date_staric').html('');
            // $('#JOB_END_DATE').prop('disabled',true);
            // $('#JOB_END_DATE').val(null);

        }else{
            $('#end_date_staric').html('*');
                // $('#JOB_END_DATE').prop('disabled',false);

        }
    }



    function  cancleExperiance() {
        $('.disab_experiance').show();
        $('#experiance_form_view').html('');
    }







    getExperiance();
</script>
