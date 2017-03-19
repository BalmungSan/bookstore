function search(){

    var in0 = document.getElementById("categorynewbook");
    var in1 = document.getElementById("author");
    var in2 = document.getElementById("name");
    var in3 = document.getElementById("prices");
    var r0 = document.getElementById("r0");
    var r1 = document.getElementById("r1");
    var r2 = document.getElementById("r2");
    var r3 = document.getElementById("r3");

    if(r0.checked){
        in1.style.display = "none";
        in2.style.display = "none";
        in3.style.display = "none";

        if(in0.style.display=="none"){
            in0.style.display="block"
        }else{
            in0.style.display="none"
        }
    }else if(r1.checked){
        in0.style.display = "none";
        in2.style.display = "none";
        in3.style.display = "none";

        if(in1.style.display=="none"){
            in1.style.display="block"
        }else{
            in1.style.display="none"
        }
    }else if(r2.checked){
        in0.style.display = "none";
        in1.style.display = "none";
        in3.style.display = "none";

        if(in2.style.display=="none"){
            in2.style.display="block"
        }else{
            in2.style.display="none"
        }
    }else{
        in0.style.display = "none";
        in1.style.display = "none";
        in2.style.display = "none";

        if(in3.style.display=="none"){
            in3.style.display="block"
        }else{
            in3.style.display="none"
        }
    }
}

function changepass(){
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "user/changePassword");
    
    var currentpass = prompt("Please enter your current password", "Current Password");
    var currentPassword = document.createElement("input");
    currentPassword.setAttribute("type", "hidden");
    currentPassword.setAttribute("name", "password");
    currentPassword.setAttribute("value", currentpass);
    form.appendChild(currentPassword);
    
    var newpass = prompt("Please enter your new password", "New Password");
    var newPassword = document.createElement("input");
    newPassword.setAttribute("type", "hidden");
    newPassword.setAttribute("name", "newpassword");
    newPassword.setAttribute("value", newpass);
    form.appendChild(newPassword);
    
    document.body.appendChild(form);
    form.submit();
}

function deleteprofile(){
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "user/delete");
    
    var email = prompt("Please enter your current email Address", "Email");
    var emailAddress = document.createElement("input");
    emailAddress.setAttribute("type", "hidden");
    emailAddress.setAttribute("name", "email");
    emailAddress.setAttribute("value", email);
    form.appendChild(emailAddress);
    
    var pass = prompt("Please enter your password", "Password");
    var password = document.createElement("input");
    password.setAttribute("type", "hidden");
    password.setAttribute("name", "password");
    password.setAttribute("value", pass);
    form.appendChild(password);
    
    document.body.appendChild(form);
    form.submit();
}