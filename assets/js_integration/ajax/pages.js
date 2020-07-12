


function getContactSection() {
    alert("method is working");

    var name=document.getElementById("name").value;
    var surname=document.getElementById("surname").value;
    var fname=document.getElementById("fname").value;
    var foccupation=document.getElementById("foccupation").value;
    var cnic=document.getElementById("cnic").value;
    var pob=document.getElementById("pob").value;
    var religion=document.getElementById("religion").value;
    var dob=document.getElementById("dob").value;
    var gender=document.getElementsByName("gander").value;
    var selection=document.getElementById("selection").value;

    req=new XMLHttpRequest();
    req.open("POST","../app/ApplicantsRegistration.php",true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");



    req.send("name="+name+"&surname="+surname+"&fname="+fname+"&foccupation="+foccupation
    +"&cnic="+cnic+"&pob="+pob+"&religion="+religion+"&dob="+dob+"&gender="+gender+"&selection="+selection);
    // req.send("name="+name);
    alert("name is "+name);
    //req.open("GET","../includes/admission_form_section_contacts.php",true);
    // req.send();
    req.onreadystatechange=function () {
        if (req.readyState==4 && req.status==200){
            document.getElementById("main_form").innerHTML=req.responseText;
            alert("if is working");
        }
    }
}

