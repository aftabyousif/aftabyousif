function saveChange(){
    //alert("register Form");
   var req = new XMLHttpRequest();
    req.open("POST","../app/addData.php",true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//    req.send();
    
    req.onreadystatechange = function()
    {
        if(req.readyState == 4 && req.status == 200)
         {
              document.getElementById('breadcome-item').innerHTML="Register";
             document.getElementById("main-body").innerHTML = req.responseText;
             
         }
    };
    
    var program= getValue("program");
    var year= getValue("year");
    var name= getValue("name");
    var fname= getValue("fname");
    var cnic= getValue("cnic");
    var home_address= getValue("home-address");
    var cell= getValue("cell");
    var email= getValue("email");
    var is_employee= getValue("is-employee");
    var employed_adress= getValue("employed-adress");
    var enrollment_date= getValue("enrollment-date");
    var topic_proposed= getValue("topic-proposed");
    var course_work_result= getValue("course-work-result");
    var attendence_in_course= getValue("attendence-in-course");
    var remarks= getValue("remarks");
    var equipment= getValue("equipment");
    var source_of_fund= getValue("source-of-fund");
    var total_fund= getValue("total-fund");
    var guide= getValue("guide");
    var co_guide= getValue("co-guide");
    var dean= getValue("dean");
    var image= getValue("image");
    
    //req.send("program="+program); 
    req.send("program="+program+"&year="+year+"&name="+name+"&fname="+fname+"&cnic="+cnic+"&home_address="+home_address+"&cell="+cell+"&email="+email+"&is_employee="+is_employee+"&employed_adress="+employed_adress+"&enrollment-date="+enrollment_date+"&topic_proposed="+topic_proposed+"&course-work-result="+course_work_result+"&attendence_in_course="+attendence_in_course+"&remarks="+remarks+"&equipment="+equipment+"&source_of_fund="+source_of_fund+"&total_fund="+total_fund+"&guide="+guide+"&co_guide="+co_guide+"&image="+image); 
//    req.send(");
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send();
//    req.send("dean"+dean);
//    req.send();
}


function getValue(id){
    
    return document.getElementById(id).value;
}

function submitForm(){
    alert("hello again");
}

