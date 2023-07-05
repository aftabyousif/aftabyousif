
 <div id="min-height" style="padding: 30px; min-height: 889px;">
            <form onsubmit='return confirm("Are you sure?")' method='post' action='<?=base_url()."AdminPanel/photoHandler"?>'>
                <div class='container-fluid'>
           <?php
           foreach($photodata as $k=>$obj){
                if($k%6==0){
                    echo "<div class='row'>";
                }
                $image_path = itsc_url().PROFILE_IMAGE_PATH.$obj['PROFILE_IMAGE'];
               
               ?>
              <div class='col-md-2'>
               <img style='width:150px;height:200px' src='<?=$image_path?>' id="profile-image-view_<?=$obj['USER_ID']?>">
                <hr>Application id : <?=$obj['APPLICATION_ID']?> <br> User ID : <?=$obj['USER_ID']?>
                <hr>Name : <?=$obj['FIRST_NAME']?>
                <hr>Is Profile Verfied : <input type='checkbox' name='<?=$obj['APPLICATION_ID']?>' checked>
                 <hr>
                 <style>
                 .big-radio{
           
                     height:30px;
                     width:30px;
                }
                .font-text{
                    font-size: xx-large;
                }
                 </style>
               <input type='radio' class='big-radio' value='0'  <?=($obj['IS_PROFILE_PHOTO_VERIFIED']=='0'||$obj['IS_PROFILE_PHOTO_VERIFIED']==null)?'checked':'';?> name='photo_<?=$obj['APPLICATION_ID']?>' onchange='changePhotoStatus(<?=$obj['APPLICATION_ID']?>)'/><span class='font-text'> : Pending </span>
                <br><input type='radio' class='big-radio' value='1' <?=($obj['IS_PROFILE_PHOTO_VERIFIED']=='1')?'checked':'';?> name='photo_<?=$obj['APPLICATION_ID']?>'onchange='changePhotoStatus(<?=$obj['APPLICATION_ID']?>)'/> <span class='font-text'> : Verified </span>
                <br><input type='radio' class='big-radio' value='2' <?=($obj['IS_PROFILE_PHOTO_VERIFIED']=='2')?'checked':'';?> name='photo_<?=$obj['APPLICATION_ID']?>'onchange='changePhotoStatus(<?=$obj['APPLICATION_ID']?>)'/> <span class='font-text' >: Rejected</span>
                
                <hr>
                <input type='radio' value='M'  <?=($obj['GENDER']=='M')?'checked':'';?> name='gender_<?=$obj['USER_ID']?>' onchange='changeGender(<?=$obj['USER_ID']?>)'/> : Male 
                <input type='radio'value='F' <?=($obj['GENDER']=='F')?'checked':'';?> name='gender_<?=$obj['USER_ID']?>'onchange='changeGender(<?=$obj['USER_ID']?>)'/> : Female  
                <hr>
                 <input type="text" name="profile_image_<?=$obj['USER_ID']?>1" id="profile_image_<?=$obj['USER_ID']?>1" value="" hidden="">
                <input type='file' name='photo' name="profile_image_<?=$obj['USER_ID']?>" id="profile_image_<?=$obj['USER_ID']?>" onchange="changeImage(this,'profile_image_<?=$obj['USER_ID']?>','profile-image-view_<?=$obj['USER_ID']?>',100)" accept=".jpg,.png,.jpeg"/>
                <br>
                <button type='button' onclick='changeImageHandler(<?=$obj['USER_ID']?>)'>Upload</button>
                <!--
                <select onchange='changeGender(<?=$obj['APPLICATION_ID']?>)' id="gender_<?=$obj['APPLICATION_ID']?>">
                            <option value='null'>Choose</option>
                            <option value='M'>Male</option>
                            <option value='F'>Female</option>
                        </select>
                -->
                
                </div>
            <?php
                
                if(($k+1)%6==0){
                    echo "</div><hr>";
                }
            }
            if(($k+1)%6!=0){
                    echo "</div>";
                }
            ?>
            <br><br><button class='btn btn-success'type='submit'>Save</button>
            </div>
            </form>
            </div>
            
<script>
    function changeImageHandler(id){
        // event.preventDefault();
        da= new FormData();
        da.append('user_id',id);
        da.append('file', $("#profile_image_"+id)[0].files[0]);
        console.log(da);
         jQuery.ajax({
             url: "<?=base_url();?>AdminPanel/change_photo_handler",
            type: "POST",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            data: da,
           success: function (data, status) {

            console.log(data);
               
              

              

            },
            beforeSend:function (data, status) {
                

            },
            error:function (data, status) {

                var value = data.responseJSON;

               alertMsg(value.MESSAGE);
               
                // $('input[name="csrf_form_token"]').val(value.csrfHash);
                // csrfHash = value.csrfHash;
                //$('#alert_msg_for_ajax_call').html(value.MESSAGE);


              



            },
        });

    }
    function changeGender(user_id){
        
        //let gender = $("#gender_"+app_id).val();
          let gender = $("input[name='gender_"+user_id+"']:checked").val();
       
        jQuery.ajax({
               url: "https://admission.usindh.edu.pk/admission/AdminPanel/update_gender_by_user_id?USER_ID="+user_id+"&GENDER="+gender,
               type: "GET",
               success: function (data, status) {
                  
                  
                
                 
               },
               beforeSend:function (data, status) {

                   $('#basic_data').html("LOADING...!");
                  
               },
               error:function (data, status) {
                   
                   alertMsg("Error",data.responseText);
                 
               },
           });
    }
    
    function changePhotoStatus(application_id){
        //let gender = $("#gender_"+app_id).val();
          let status = $("input[name='photo_"+application_id+"']:checked").val();
       
        jQuery.ajax({
               url: "https://admission.usindh.edu.pk/admission/AdminPanel/update_photo_by_application_id?APPLICATION_ID="+application_id+"&STATUS="+status,
               type: "GET",
               success: function (data, status) {
                  
                  
                
                 
               },
               beforeSend:function (data, status) {

                   $('#basic_data').html("LOADING...!");
                  
               },
               error:function (data, status) {
                   
                   alertMsg("Error",data.responseText);
                 
               },
           });
    }
    
</script>