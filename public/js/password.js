const accpassword = document.querySelectorAll('input[type="password"]'),
    message = document.querySelector(".caps-lock");
let newpassform = document.getElementsByName("password"),
    passcekform = document.getElementsByName("password_confirmation"),
    pass1,
    pass2;
for (let a = 0; a < accpassword.length; a++) {
    accpassword[a].addEventListener("keydown", function (e) {
        if (e.getModifierState("CapsLock")) message.classList.remove("d-none");
        else message.classList.add("d-none");
    });
}
function checkpassword() {
    pass1 = newpassform[0].value;
    pass2 = passcekform[0].value;
    if (pass1 !== pass2)
        passcekform[0].setCustomValidity("Password konfirmasi salah");
    else passcekform[0].setCustomValidity("");
}
