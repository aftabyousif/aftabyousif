
 <div id="min-height" style="padding: 30px; min-height: 889px;">
           
                <div class='container-fluid'>
           <?php
           $k = 0;
           foreach($list as $obj){
             
                if($k%3==0){
                    echo "<div class='row'>";
                }
                $image_path = itsc_url().EXTRA_IMAGE_PATH.$obj['CHALLAN_IMAGE'];
               
               ?>
              <div class='col-md-4'>
               <img style='width:400px;height:600px' src='<?=$image_path?>' id="profile-image-view_<?=$obj['USER_ID']?>">
                <hr>Application id : <?=$obj['APPLICATION_ID']?> <br> User ID : <?=$obj['USER_ID']?><br><br> Form Challan ID : <?=$obj['FORM_CHALLAN_ID']?>
                <hr>Name : <?=$obj['FIRST_NAME']?> 
                <hr><strong>Paid Date : <?=$obj['CHALLAN_DATE']?></strong>  &emsp; &emsp; &emsp;Branch Name : <?=$obj['BRANCH_NAME']?>
                
                
                 <hr>
                 <style>
                 .big-radio{
                     height:30px;
                     width:30px;
                 }
                 </style>
               <input class= 'big-radio' type='radio' value='0'  <?=($obj['IS_VERIFIED']=='N'||$obj['IS_VERIFIED']==null)?'checked':'';?> name='photo_<?=$obj['APPLICATION_ID']?>' onchange='changeChallanImage(<?=$obj['APPLICATION_ID']?>)'/>  Pending &emsp;&emsp;&emsp; 
                <input class= 'big-radio' type='radio'value='1' <?=($obj['IS_VERIFIED']=='Y')?'checked':'';?> name='photo_<?=$obj['APPLICATION_ID']?>'onchange='changeChallanImage(<?=$obj['APPLICATION_ID']?>,<?=$obj['FORM_CHALLAN_ID']?>)'/>  Verified &emsp;&emsp;&emsp;
                <input class= 'big-radio' type='radio'value='2' <?=($obj['IS_VERIFIED']=='R')?'checked':'';?> name='photo_<?=$obj['APPLICATION_ID']?>'onchange='changeChallanImage(<?=$obj['APPLICATION_ID']?>,<?=$obj['FORM_CHALLAN_ID']?>)'/> Rejected  &emsp;&emsp;&emsp; 
                
               
            
                
                </div>
            <?php
                
                if(($k+1)%3==0){
                    echo "</div><hr>";
                }
                 $k++;
            }
            if(($k+1)%3!=0){
                    echo "</div>";
                }
            ?>
            <br><br>
            </div>
           
            </div>
            
<script>
   
    
    function changeChallanImage(application_id,form_challan_id){
        //let gender = $("#gender_"+app_id).val();
          let status = $("input[name='photo_"+application_id+"']:checked").val();
       
        jQuery.ajax({
               url: "https://admission.usindh.edu.pk/admission/AdminPanel/challan_image_verification?APPLICATION_ID="+application_id+"&STATUS="+status+"&FORM_CHALLAN_ID="+form_challan_id,
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