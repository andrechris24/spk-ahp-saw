const accpassword = document.querySelectorAll('input[type="password"]'),
    message = document.querySelector(".caps-lock"),
    newpassform = document.getElementsByName("password"),
    passcekform = document.getElementsByName("password_confirmation");
for (let a = 0; a < accpassword.length; a++) {
    accpassword[a].addEventListener("keydown", function (e) {
        if (e.getModifierState("CapsLock")) message.classList.remove("d-none");
        else message.classList.add("d-none");
    });
}
function checkpassword() {
    for (let i = 0; i < newpassform.length; i++) {
        if (newpassform[i].value !== passcekform[i].value)
            passcekform[i].setCustomValidity("Password konfirmasi salah");
        else passcekform[i].setCustomValidity("");
    }
}
