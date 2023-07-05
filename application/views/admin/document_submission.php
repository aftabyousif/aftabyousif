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


                    <!--<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">-->
                        
                    <!--<div class="form-group">-->
                    <!--<label>Barcode</label>-->
                    <!--<input type='radio' name='card_type' id='barcode' value='barcode'>-->
                    <!--<label>QR-Code</label>-->
                    <!--<input type='radio' name='card_type' id='qr_code' value='qr_code' >-->
                    
                    <!--</div>-->
                    <!--</div>-->
                        
               
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Search
                            </label>
                            <input type="text" id="SEARCH" class="form-control " placeholder="" name="SEARCH" autofocus>
                        </div>
                        </div>
                        <!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">-->
                            
                                <div class="button-style-four btn-mg-b-10">
                                    <br/>
                                    <button type="button" onclick="getApplication()" class="btn btn-custon-rounded-four btn-primary"><i class="fa fa-search edu-search" aria-hidden="true"></i> Search</button>
                                    <button type="button" onclick='location.reload();'class="btn btn-custon-rounded-four btn-warning"><i class="fa fa-close edu-warning-danger" aria-hidden="true"></i> Clear</button>
                                    
                                </div>
                                
                         <!--<button type="button" onclick="getUserByCnic()" class="btn btn-primary btn-lg waves-effect waves-light">Search</button>-->
                         <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
     <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"> 
          <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Select Name of Reciver
                            </label>
        <select name="reciver_name" id="reciver_name" class='form-control'>
             <option>Choose Name of Reciver</option>
            <option>Ameer Bux Channa</option>
            <option>Muhammad Saleem Bhutto</option>
            <option>Aijaz Ahmed Qureshi</option>
            <option>Syed Irshad Ali Shah</option>
            <option>Ishtiaque Hussain Arbab</option>
            <option>Nazar Muhammad Channa</option>
            <option>Nooruddin Shaikh</option>
            <option>Manzoor Ali Saharan</option>
            <option>Ghulam Murtaza Halepoto</option>
            <option>Shaista Baloch</option>
            <option>Humar Nawaz Khaskheli</option>
            <option>Imran Hussain Rajar</option>
            <option>Syed Zaheer Abbas Shah</option>
            <option>Akram Zounr</option>

        </select>
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
<script src="<?=base_url('assets/jQuery-Scanner-Detection-master/jquery.scannerdetection.js')?>"></script>
<script>

$(document).scannerDetection(function(){
        console.log("working");
        let application_id =  $('#SEARCH').val();
        console.log(application_id);
        let cardType = "qr_code";
       application_id = application_id.split("~");
       console.log(application_id);
       application_id =Number( application_id[1]);
       console.log(application_id);
       if(application_id>0){
        getApplication(application_id);
           
       }
       
        $('#SEARCH').val('');
      
    });

    function getApplication(application_id=0){
        console.log(application_id)
        if(application_id <=0 ){
        application_id =  $('#SEARCH').val();
        }
      
        if(application_id){
                var data = new FormData();
           data.append('APPLICATION_ID',application_id);
           
           
           $('.preloader').fadeIn(700);
           jQuery.ajax({
               url: "<?=base_url()?>FormVerification/getApplicationById",
               type: "POST",
               enctype: 'multipart/form-data',
               processData: false,
               contentType: false,
               data: data,
               success: function (data, status) {
                   
                       $('#SEARCH').val("");
                   
                   $('.preloader').fadeOut(700);
                   // $('input[name="csrf_form_token"]').val(data.csrfHash);
                   //$('#alert_msg_for_ajax_call').html("");
                   $('#basic_data').html(data);
                   //alertMsg("Success",data.MESSAGE);
                   //console.log(is_next);
               },
               beforeSend:function (data, status) {

                   $('#basic_data').html("LOADING...!");
                   //$('#alert_msg_for_ajax_call').html("LOADING...!");
               },
               error:function (data, status) {
                   //var value = data.responseJSON;
                   alertMsg("Error",data.responseText);
                   //$('input[name="csrf_form_token"]').val(value.csrfHash);
                   //$('#alert_msg_for_ajax_call').html(value.MESSAGE);
                   $('#basic_data').html(data);
                   $('.preloader').fadeOut(700);
               },
           });
        }
          
       
     
    }
    </script>