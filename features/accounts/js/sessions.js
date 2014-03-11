function Login() {
    theamus.ajax.run({
        url : "accounts/login/",
        result : "login-result",
        form : "login-form"
    });
    return false;
}

function register() {
    $("#register-result").html(working());
    theamus.ajax.run({
        url: "accounts/register/",
        result: "register-result",
        form: "register-form"
    });
    return false;
}