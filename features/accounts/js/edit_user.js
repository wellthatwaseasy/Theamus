function toggle_pass() {
    var passwords = $("#passwords");

    if (passwords.is(":hidden")) {
        passwords.show();
        $("#password").focus();
    } else {
        passwords.hide();
    }
}

function save_account() {
    scroll_top();
    $("#user-result").html(working());
    theamus.ajax.run({
       url: "accounts/user/save-account/",
       result: "user-result",
       form: "user-form"
    });
    $("#picture").replaceWith($("#picture").clone(true));

    return false;
}

function save_personal() {
    scroll_top();
    $("#user-result").html(working());
    theamus.ajax.run({
        url: "accounts/user/save-personal/",
        result: "user-result",
        form: "user-form"
    });

    return false;
}

function save_contact() {
    scroll_top();
    $("#user-result").html(working());
    theamus.ajax.run({
        url: "accounts/user/save-contact/",
        result: "user-result",
        form: "user-form"
    });

    return false;
}

function save_user() {
    admin_scroll_top();
    $("#user-result").html(working());
    theamus.ajax.run({
        url: "accounts/save/",
        result: "user-result",
        form: "user-form",
        hide_result: 3
    });

    return false;
}

function add_user() {
    admin_scroll_top();
    $("#user-result").html(working());
    theamus.ajax.run({
        url: "accounts/add/",
        result: "user-result",
        form: "user-form"
    });

    return false;
}

function back_to_userlist() {
	countdown("Back to users list in", 3);
    setTimeout(function() {
        admin_go("accounts", "accounts/");
    }, 3000);
}

function update_pics(info) {
    $(".site_header-user-pic").html("<img src='media/profiles/"+info.pic+"' alt='' />");
    $(".admin_header-noicon").html("<img src='media/profiles/"+info.pic+"' alt='' />");
    $("#current-pic")[0].src = "media/profiles/"+info.pic;
}

function update_name(info) {
    $(".site_header-user-name").html(info.firstname+" "+info.lastname);
}