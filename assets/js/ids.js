var ids = ["name","surname","father_name","foccupation","cnic","pob","blood_group","religion","doba","profile_image","gender_male","gender_female","urban","rural","passport_no","domicile","nationality","cellno","phone","email","permanent_address","home_address","cnic-image","domicile_prc_image","domicile_formc_image","passport_image","is_employeed","employeed_anywhere","is_nominated","nominated_by_employer","is_research_work","published_r_work","is_grant_funding","grant_funding","is_ger_gat","gre_gat_id","gre_gat_score","gre_gate_qualified_date","gre_gate_expiry_date","ssc_board","ssc_group","ssc_grade","ssc_seat_no","ssc_passing_year","ssc_scholarship","ssc_school_name","ssc_total_marks","ssc_obtained_marks","ssc-marksheet-image","ssc-pass-certificate-image","hsc_board","hsc_group","hsc_grade","hsc_seat_no","hsc_passing_year","hsc_scholarship","hsc_school_name","hsc_total_marks","hsc_obtained_marks","hsc-marksheet-image","hsc-pass-certificate-image","is_two_year_bachelor","university_bachelor","bachelor_degree_title","bachelor_cgpa_percentage","bachelor_starting_year","bachelor_ending_year","bachelor_scholarship","bachelor_department","bachelor_total_marks","bachelor_obtained_marks","bachelor_roll_no","bachelor-marksheet-image","pass-certificate-image","university_master","master_degree_title","master_cgpa_percentage","master_starting_year","master_ending_year","master_scholarship","master_department","master_total_marks","master_obtained_marks","master_roll_no","master_marksheet_image","master_pass_certificate_image","university_mphill","mphill_degree_title","mphill_cgpa_percentage","mphill_starting_year","mphill_ending_year","mphill_scholarship","mphill_department","mphill_total_marks","mphill_obtained_marks","mphill_roll_no","mphil_research_topic","mphil_marksheet_image","mphil_pass_certificate_image","discipline_id","branch_code"];

var radio_btns;
radio_btns = ["gender_male","gender_female","urban","rural"];


var toggle_btns = ["is_employeed","is_nominated","is_research_work","is_grant_funding","is_ger_gat","is_two_year_bachelor"];

 var images = ["profile_image","cnic-image","domicile_prc_image","domicile_formc_image","passport_image"];


 var images1 = ["profile_image1", "cnic-image1","domicile_prc_image1","domicile_formc_image1","ssc-marksheet-image1","ssc-pass-certificate-image1","hsc-marksheet-image1","hsc-pass-certificate-image1","bachelor-marksheet-image1","pass-certificate-image1","bank_challan_image1"];

var master_images1 = ["master_marksheet_image1","master_pass_certificate_image1"];

var mphil_images1 = ["mphil_marksheet_image1","mphil_pass_certificate_image1"];

var mandatory_images1 = ["profile_image","cnic-image","domicile_prc_image","domicile_formc_image","ssc-marksheet-image","ssc-pass-certificate-image","hsc-marksheet-image","hsc-pass-certificate-image","bachelor-marksheet-image","pass-certificate-image","bank_challan_image"];


var master_mandatory_images1 = ["master_marksheet_image","master_pass_certificate_image"];

var mphil_mandatory_images1 = ["mphil_marksheet_image","mphil_pass_certificate_image"];

var mandatory_images = ["domicile_prc_image","domicile_formc_image"];



var select_boxes = ["blood_group","ssc_board","ssc_group","ssc_grade","ssc_passing_year","hsc_board","hsc_group","hsc_grade","hsc_passing_year","university_bachelor","bachelor_department","discipline_id","religion"];

var select_boxes_master = ["university_master","master_department"];

var select_boxes_mphil = ["university_mphill","mphill_department"];
   
var txt_boxes_mandatory =["name","surname","father_name","foccupation","cnic","pob","doba","domicile","nationality","cellno","email","permanent_address","home_address","branch_code","challan_number","challan_date"];


var hsc_txt_boxes = ["hsc_seat_no","hsc_school_name","hsc_total_marks","hsc_obtained_marks"];

var ssc_txt_boxes = ["ssc_seat_no","ssc_school_name","ssc_total_marks","ssc_obtained_marks"];

var remove_text_array =["branch_code","challan_number","challan_date"];
var select_boxes_array = ["discipline_id"];
var bachelor_txt_boxes = ["bachelor_degree_title","bachelor_cgpa_percentage","bachelor_starting_year","bachelor_ending_year","bachelor_total_marks","bachelor_obtained_marks","bachelor_roll_no"];


var mphil_txt_boxes = ["mphill_degree_title","mphill_cgpa_percentage","mphill_starting_year","mphill_ending_year","mphill_total_marks","mphill_obtained_marks","mphill_roll_no","mphil_research_topic"];


var master_txt_boxes = ["master_degree_title","master_cgpa_percentage","master_starting_year","master_ending_year","master_total_marks","master_obtained_marks","master_roll_no"];

var txt_boxes = ["passport_no","phone","employeed_anywhere","nominated_by_employer","published_r_work","grant_funding","gre_gat_score","gre_gate_qualified_date","gre_gate_expiry_date"];


function check_submit()
{
  var submit = document.getElementById("submit").value ; 
    var save = document.getElementById("save").value;
    if(submit==1 || save == 1){
        //alert(submit+" "+save);
         return true;
    }   
    else{
        //alert(submit+" "+save);
       return false; 
    }
        
}
function validation(id){
    var bool = true;
    if(document.getElementById(id) != null){
        var id_value = document.getElementById(id).value;
        id_value = id_value.trim();
        if(id_value == "" || id_value == null || id_value == 0){
            bool = false;
            console.log(id+"textValidation-if-----");
               document.getElementById(id).style = "border-color:red; outline-color:red; outline-width:5px; outline-style:auto;";    
        }else{
            document.getElementById(id).style = "border-color:#ccc;";    
        }
    }else{
        bool = false; 
        console.log(id+"txtValidation------");
    }
    return bool;
}

function selectBoxValidation(id){
    var bool = true;
    if(document.getElementById(id) != null){
        var id_value = document.getElementById(id).value;
        id_value = id_value.trim();
        if(id_value == "" || id_value == null || id_value == 0){
            bool = false;
            console.log(id+"----- selectValidation---if-----");
               document.getElementById(id).style = "border-color:red;";    
        }else{
            document.getElementById(id).style = "border-color:#ccc;";    
        }
    }else{
        bool = false; 
        console.log(id+"----- selectValidation---if-----");
    }
    return bool;
}

function validationWithRegx(id, regx){
     var bool = true;
    if(document.getElementById(id) != null){
        var id_value = document.getElementById(id).value;
        id_value = id_value.trim();
        if(id_value == "" || id_value == null|| id_value == 0){
            bool = false;
               document.getElementById(id).style = "border-color:red;";    
        }else{
            if(regx.test(id_value)){
                document.getElementById(id).style = "border-color:#ccc;";
                
            }else{
                document.getElementById(id).style = "border-color:red;";    
                bool = false;
            }
        }
        
    }else{
        bool = false;   
    }
    return bool;
}

function modalAlert(message, title){
      document.getElementById('alert-message').innerHTML = message;
        document.getElementById('alert-title').innerHTML = title;
        document.getElementById('modal-alert').click();
}
function exactValidation(id, regx, errorMessage, title){
     var bool = true;    
    var is_pakistani = document.getElementById("is_pakistani").value;
    
    var id_val = document.getElementById(id).value;
    //console.log("asdas "+is_pakistani);
    if( id_val =="" ){
      //  console.log("as");     
         return;
    }
   
    bool = validationWithRegx(id, regx);
    if(!bool){
      modalAlert(errorMessage, title);
      document.getElementById(id).focus();
    }
        
}

function removeArray(array1, array2){
    var array3 = [];
    for(var i=0; i<array1.length; i++){
        var bool = array2.includes(array1[i]);
        if(bool){
            
        }else{
            array3.push(array1[i]);
        }
    }
    return array3;
}
function mySave(){
    bool = true;
   if(bool){
        document.getElementById("save").value = "1";
        document.getElementById("click").click();    
        }else{
        modalAlert("Fill out red boxes carefully!","Error");
    } 
}
function mySubmit(){
    bool = true;
    for(var i = 0; i< for_validation_array.length; i++){
        if(!(validation(for_validation_array[i]))){
            bool = false;
        }
    }
    for(var i = 0; i< images1.length; i++){
        if(!(imageValidation(images1[i], mandatory_images1[i]))){
            bool = false;
        }
    }
    
    for(var i = 0; i< select_boxes.length; i++){
        if(!(selectBoxValidation(select_boxes[i]))){
            bool = false;
        }
    }
    
    
  
      if(bool){
         document.getElementById("submit").value = "1";
          document.getElementById("click").click();    
        }else{
        modalAlert("Fill out red boxes carefully!","Error");
    } 
}

function imageValidation(id, id2){
    var bool = true;
//    console.log(id +"-------"+id2);
    if(document.getElementById(id) != null && document.getElementById(id2) != null){
        var id_value = document.getElementById(id).value;
        id_value = id_value.trim();
        if(id_value == "" || id_value == null){
            
            bool = false;
            console.log(id+"imageValidation-if-----"+id2);
               document.getElementById(id2).style = "border-color:red; outline-color:red; outline-width:5px; outline-style:auto;";    
        }else{
            document.getElementById(id2).style = "border-color:#ccc;";    
        }
    }else{
        bool = false;  
        console.log(id+"imageValidation------"+id2);
    }
    return bool;
}
function checkOther(id,id1){
   if(getId(id).value==-1)
    {
        getId(id1).style.display="block";
    }
    else{
        getId(id1).style.display="none";
    }
}
function getId(id){
    return  document.getElementById(id);
    
}
function is_pakistani_fun(){
            var is_pak = document.getElementById("is_pakistani");
            if(is_pak.value == 0 ){
                is_pak.value=1;
                 document.getElementById("passport_no").style = "border-color:#ccc;";
                document.getElementById("passport_image").style = "border-color:#ccc;";
                for_validation_array = for_validation_array.concat(['cnic']);
                images1 = images1.concat(["cnic-image1"]);
                mandatory_images1 = mandatory_images1.concat(["cnic-image"]);
                for_validation_array = removeArray(for_validation_array, ["passport_no"]);
                images1 = removeArray(images1, ["passport_image1"]);
                mandatory_images1 = removeArray(mandatory_images1, ["passport_image"]);
            }else{
                is_pak.value=0;
                document.getElementById("cnic").style = "border-color:#ccc;";
                document.getElementById("cnic-image").style = "border-color:#ccc;";
                for_validation_array = removeArray(for_validation_array, ["cnic"]);
                images1 = removeArray(images1, ["cnic-image1"]);
                mandatory_images1 = removeArray(mandatory_images1, ["cnic-image"]);
                for_validation_array = for_validation_array.concat(['passport_no']);
                images1 = images1.concat(["passport_image1"]);
                mandatory_images1 = mandatory_images1.concat(["passport_image"]);
            }
        }

