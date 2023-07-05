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
                        
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        
                    <div class="form-group">
                        <label for="exampleInput1" class="bmd-label-floating">Search By
                           </label>
                        <select id='searchBy' name='searchBy' class='form-control'>
                            <option value='CNIC'>CNIC NO</option>
                            <option value='USER_ID'>USER ID</option>
                            <option value='APPLICATION_ID'>APPLICATION ID</option>
                        </select>
                        </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="exampleInput1" class="bmd-label-floating">Search
                            </label>
                            <input type="text" id="SEARCH" class="form-control allow-number" placeholder="" name="SEARCH" autofocus>
                        </div>
                        </div>
                        <!--<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">-->
                            
                                <div class="button-style-four btn-mg-b-10">
                                    <br/>
                                    <button type="button" onclick="getUserByCnic()" class="btn btn-custon-rounded-four btn-primary"><i class="fa fa-search edu-search" aria-hidden="true"></i> Search</button>
                                    <button type="button" onclick='location.reload();'class="btn btn-custon-rounded-four btn-warning"><i class="fa fa-close edu-warning-danger" aria-hidden="true"></i> Clear</button>
                                    
                                </div>
                                
                         <!--<button type="button" onclick="getUserByCnic()" class="btn btn-primary btn-lg waves-effect waves-light">Search</button>-->
                         <!--</div>-->
                    </div>
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
$('#SEARCH').change(function(){
       let cnic =  $('#SEARCH').val();
        let cardType = "qr_code";
        console.log(cnic);
});
var count = 0;
var cnic_global ='';
$(document).scannerDetection(function(){
        // console.log("working");
        let cnic =  $('#SEARCH').val();
        let cardType = "qr_code";
       
        count++;
        //console.log(cnic.length);
        if(cnic.length==26 ){
             cnic = cnic.substr(12,13); 
             console.log(cnic);
             getUserByCnic(cnic);
             count = 0;
        }else if(cnic.length==13){
             console.log(cnic);
            getUserByCnic(cnic);
              count = 0;
        }
         count = 0;
        $('#SEARCH').val('');
        /*
        if (document.getElementById('barcode').checked) {
            cardType = document.getElementById('barcode').value;
        }
        
        if (document.getElementById('qr_code').checked) {
            cardType = document.getElementById('qr_code').value;
        }
        */
            // if(cardType == "qr_code"){
            //     cnic = cnic.substr(12,13);    
            // }else if(cardType == "barcode"){
            //     cnic = cnic.split(/\r?\n/);
            // }
           
            // cnic = $.trim(cnic);
            // console.log(cnic[0])
            // return;
           // getUserByCnic(cnic);
    });

    function getUserByCnic(cnic=0){
        console.log(cnic)
        if(cnic <=0 ){
        cnic =  $('#SEARCH').val();
        }
       let searchBy = $('#searchBy').val();
       console.log(cnic)
       /// cnic = trim(cnic);
       if(cnic && searchBy){
           var form = $('#base_profile_form')[0];
           var data = new FormData(form);
           data.append('CNIC_NO',cnic);
           data.append('SEARCH_BY',searchBy);
           
           $('.preloader').fadeIn(700);
           jQuery.ajax({
               url: "<?=base_url()?>AdminPanel/get_basic_information",
               type: "POST",
               enctype: 'multipart/form-data',
               processData: false,
               contentType: false,
               data: data,
               success: function (data, status) {
                   if(count!=0){
                       $('#SEARCH').val("");
                   }
                   count=0;
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
       }else{
           alertMsg('ALERT','Enter Cnic No');
       }
    }
    </script>