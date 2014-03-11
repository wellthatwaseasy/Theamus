function finish() {
    theamus.ajax.run({
        url:   "install/finish/",
        result: "result",
        after:  {
            do_function: "login"
        }
    });
    return false;
}

function login() {
    window.location.reload();
}

function show_email_config() {
    var email = document.getElementById("email-container");
    if (email.classList.contains("grown")) {
        email.classList.remove("grown");
    } else {
        email.classList.add("grown");
    }
}