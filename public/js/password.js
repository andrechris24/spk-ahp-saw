const accpassword = document.querySelectorAll('input[type="password"]'),
    message = document.querySelector("#capslock");
for (let a = 0; a < accpassword.length; a++) {
    accpassword[a].addEventListener("keydown", function (e) {
        if (e.getModifierState("CapsLock")) message.classList.remove("d-none");
        else message.classList.add("d-none");
    });
}
var newpassform = document.getElementById("password"),
    passcekform = document.getElementById("confirm-password");

function checkpassword() {
    var pass1 = newpassform.value,
        pass2 = passcekform.value;
    if (pass1 !== pass2)
        passcekform.setCustomValidity("Password konfirmasi salah");
    else passcekform.setCustomValidity("");
}
