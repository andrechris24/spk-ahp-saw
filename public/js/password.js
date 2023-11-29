const accpassword = document.querySelectorAll('input[type="password"]'),
    message = document.querySelector("#capslock");
for (let a = 0; a < accpassword.length; a++) {
    accpassword[a].addEventListener("keydown", function (e) {
        if (e.getModifierState("CapsLock")) message.classList.remove("d-none");
        else message.classList.add("d-none");
    });
}
var newpassform = document.getElementsByName("password"),
    passcekform = document.getElementsByName("password_confirmation");

function checkpassword() {
    var pass1 = newpassform[0].value,
        pass2 = passcekform[0].value;
    if (pass1 !== pass2)
        passcekform[0].setCustomValidity("Password konfirmasi salah");
    else passcekform[0].setCustomValidity("");
}
