function validateCell() {
    var cell=document.getElementById("cellno").value;
    var regx=/^[0][0-9]{10}$/;
    //+923023625088
    if(regx.test(cell)){
        alert("numbers Match")
    }else{
        alert("Invalid Number")
    }
}
function validateEmail() {
    var email=document.getElementById("email").value;
    var regx=/^([a-z A-Z 0-9 \. - ])@([a-zA-Z0-9-]+).([a-z]{2-8})(.[a-z]{2-8})$/;
    if(regx.test(email)){
        alert("email is valid");
    }else{
        alert("INVALID Email");
    }
}