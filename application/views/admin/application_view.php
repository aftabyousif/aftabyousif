<hr>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="basic-login-inner">
                        <h3 style='font-size:11pt'>Update Document Submission Status</h3>
                        <form action="#" id='basic_info_form'>
                       <h4 class='text-danger'><?="APPLICATION ID ".$application['APPLICATION_ID']?></h4>
                       <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                 <div class="form-group-inner">
                           <label for="exampleInput1">Full Name</label>
                           <input type="text" id="APPLICATION_ID" hidden name="APPLICATION_ID" value="<?=$application['APPLICATION_ID']?>"/>
                            <input type="text" id="FIRST_NAME" class="form-control allow-number" readonly placeholder="FIRST_NAME" name="FIRST_NAME" value="<?=$application['FIRST_NAME']?>">
                            </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                
                                 <div class="form-group-inner">
                           <label for="exampleInput1">Surname</label>
                            <input type="text" id="LAST_NAME" class="form-control allow-number" readonly placeholder="LAST_NAME" name="LAST_NAME" value="<?=$application['LAST_NAME']?>">
                            </div>
                            </div>
                      </div>
                      <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                         <div class="form-group-inner">
                       <label for="exampleInput1">Father's Name</label>
                        <input type="text" id="FNAME" class="form-control allow-number" readonly placeholder="FNAME" name="FNAME" value="<?=$application['FNAME']?>">
                        </div>
                          </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                 <div class="form-group-inner">
                       <label for="exampleInput1">CNIC No</label>
                        <input type="text" id="CNIC_NO" class="form-control allow-number" placeholder="CNIC_NO" name="CNIC_NO" value="<?=$application['CNIC_NO']?>">
                        </div>
                             </div>
                      </div>
                        
                         <div class="form-group-inner">
                     <label for="exampleInput1">Document Message</label>
                     <input type="text" id="DOC_MSG" class="form-control allow-string" placeholder="Document Submission Message" name="DOC_MSG" value="">
                        </div>
                        <style>
                        .inline-remember-me{
                            padding:10px;
                        }
                        </style>
                        <div class="login-btn-inner">
                            <div class="inline-remember-me">
                        <button class="btn btn-sm btn-primary   btn-custon-rounded-two" id="save" type="button"><i class='fa fa-save'></i> Update</button>

                        </div>
                           
                        </div>
                        
                        </form>
                        </div>
  

        </div>
        </div>
  <script>
  var application_id = <?=$application['APPLICATION_ID']?>;
   $("#save").on('click',function () {
        if(true) {
            let recived_by = $("#reciver_name").val();
            event.preventDefault();
            var form = $('#basic_info_form')[0];
            var data = new FormData(form);
            data.append("recived_by",recived_by);
            $('.preloader').fadeIn(700);
            jQuery.ajax({
                url: "<?=base_url()?>FormVerification/update_document_msg",
                type: "POST",
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                data: data,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            percentComplete = Math.round(percentComplete);
                            $("#pre_text").html("<br><br><h3>Uploading " + percentComplete + "%</h3>");
                            //console.log(percentComplete);
                        }
                    }, false);
                    return xhr;
                },
                success: function (data, status) {
                    $('.preloader').fadeOut(700);
                    $('input[name="csrf_form_token"]').val(data.csrfHash);
                    $('#alert_msg_for_ajax_call').html("");

                    alertMsg("Success", data.MESSAGE);
                    // console.log(data.MESSAGE);
                },
                beforeSend: function (data, status) {
                    $('#alert_msg_for_ajax_call').html("LOADING...!");
                },
                error: function (data, status) {
                    var value = data.responseJSON;
                    alertMsg("Error", value.MESSAGE);
                    // alert("Error"+status);
                    // $('input[name="csrf_form_token"]').val(value.csrfHash);
                    $('#alert_msg_for_ajax_call').html(value.MESSAGE);
                    //console.log(value.MESSAGE);
                    $('.preloader').fadeOut(700);
                },
            });
        }
    });
  </script>