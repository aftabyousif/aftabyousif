
 <div id="min-height" style="padding: 30px; min-height: 889px;">
           
                <div class='container-fluid'>
           <?php
           $k = 0;
           foreach($list as $obj){
             
                if($k%3==0){
                    echo "<div class='row'>";
                }
                $image_path = itsc_url().EXTRA_IMAGE_PATH.strtolower($obj['RESULT_IMAGE']);
               
               ?>
              <div class='col-md-4'>
               <img style='width:400px;height:600px' src='<?=$image_path?>' id="profile-image-view_<?=$obj['USER_ID']?>">
                <hr>Application id : <?=$obj['APPLICATION_ID']?> <br> User ID : <?=$obj['USER_ID']?><br><br>Token No : <?=$obj['TOKEN_NO']?><br><br>Token No : <?=$obj['TEST_SCORE']?>
                <hr>Name : <?=$obj['FIRST_NAME']?> 
                <hr>
                 <input class= 'big-radio' type='radio' value='1'  <?=($obj['IS_RECOMMENDED']=='Y'||$obj['IS_RECOMMENDED']==null)?'checked':'';?> name='lat_<?=$obj['APPLICATION_ID']?>' onchange='changeLATImage(<?=$obj['APPLICATION_ID']?>)'/>  RECOMMENDED &emsp;&emsp;&emsp; 
                <input class= 'big-radio' type='radio'value='0' <?=($obj['IS_RECOMMENDED']=='N')?'checked':'';?> name='lat_<?=$obj['APPLICATION_ID']?>'onchange='changeLATImage(<?=$obj['APPLICATION_ID']?>)'/>  NOT RECOMMENDED &emsp;&emsp;&emsp;
             
                 <hr>
                 <style>
                 .big-radio{
                     height:30px;
                     width:30px;
                 }
                 </style>
              
                
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
   
    
    function changeLATImage(application_id){
        //let gender = $("#gender_"+app_id).val();
          let status = $("input[name='lat_"+application_id+"']:checked").val();
       
        jQuery.ajax({
               url: "https://admission.usindh.edu.pk/admission/AdminPanel/lat_recommendation?APPLICATION_ID="+application_id+"&STATUS="+status,
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