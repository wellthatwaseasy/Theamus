function change_header(text) {
    $("#content-header").html(text);
    return false;
}

function change_content(path) {
    theamus.ajax.run({
        url: path,
        result: "content",
        type: "include",
        after: {
            do_function: [
                "add_install_js",
                "install_scroll_top",
                "add_listeners"
            ]
        }
    });
    return false;
}

function changeLink(to) {
    var nav = document.getElementById("nav");

    var li = nav.children;

    for (var i = 0; i < li.length; i++) {
        if (li[i].classList.contains("current")) {
            li[i].classList.remove("current");
        }

        if (li[i].getAttribute("parent") === to) {
            li[i].classList.add("current");
        }
    }
}

function add_install_js() {
    // Get all scripts to be added
    var scripts = $("[name='addscript']");

    // Loop through all of the scripts
    for (var i = 0; i < scripts.length; i++) {
        // Add the script to the header
        add_js_file(scripts[i].value);
        scripts[i].remove();
    }
}

function install_scroll_top() {
    $("html, body").animate({
        scrollTop: 0
    }, "slow");
}

function go_step(args) {
    var text;

    // Custom step
    if (args['step'] === "dbreset") {
        args['step'] = "config";
        text = "<br />- Reloading the page in";
    } else {
        text = "<br />- Next step in";
    }

    // Count down to next step
    countdown(text, 3);

    // Pause, then go to next step
    setTimeout(function() {
        install_step({where: args['step']});
    }, 3000);
}

function undisable_form() {
    $("#config-form input[type='submit']").prop("disabled", false);
}

function add_listeners() {
    $("#config-reset").click(function(e) {
        e.preventDefault();
        countdown("<br />Resetting the config file", 3, false);

        setTimeout(function() {
            theamus.ajax.run({
                url: "install/reset-config/",
                result: "result"
            });
        }, 3000);
    });

    $("#continue").click(function(e) {
        e.preventDefault();
        install_step({"where": "db"});
    });

    $("#config-form").submit(function(e) {
        e.preventDefault();
        install_scroll_top();
        $("#config-form input[type='submit']").prop("disabled", "disabled");
        $("#result").html(working());
        setTimeout(function() {
            theamus.ajax.run({
                url: "install/write-config/",
                result: "result",
                form: "config-form"
            });
        }, 1000);
    });

    $("#create-form").submit(function(e) {
        e.preventDefault();
        install_scroll_top();
        $("#create-form input[type='submit']").prop("disabled", "disabled");
        $("#result").html(working());
        setTimeout(function() {
            theamus.ajax.run({
                url: "install/create-database/",
                result: "result",
                form: "create-form"
            });
        }, 1000);
    });

    $("#user-form").submit(function(e) {
        e.preventDefault();
        install_scroll_top();
        $("#user-form input[type='submit']").prop("disabled", "disabled");
        $("#result").html(working());
        setTimeout(function() {
            theamus.ajax.run({
                url: "install/create-user/",
                result: "result",
                form: "user-form"
            });
        }, 1000);
    });

    $("#custom-form").submit(function(e) {
        e.preventDefault();
        install_scroll_top();
        $("#config-form input[type='submit']").prop("disabled", "disabled");
        $("#result").html(working());
        setTimeout(function() {
            theamus.ajax.run({
                url: "install/save-custom/",
                result: "result",
                form: "custom-form"
            });
        }, 1000);
    });
}

function install_step(args) {
    if (args['where'] === "config") {
        change_header("Set Up Your Configuration File");
        change_content("install/create-config/");
        changeLink("config");
    }

    if (args['where'] === "db") {
        change_header("Create the Database Structure and Data");
        change_content("install/create-db/");
        changeLink("db");
    }

    if (args['where'] === "user") {
        change_header("Create Your First User");
        change_content("install/create-user/");
        changeLink("user");
    }

    if (args['where'] === "custom") {
        change_header("Site Customization");
        change_content("install/customize/");
        changeLink("custom");
    }

    if (args['where'] === "done") {
        change_header("Finishing Up");
        change_content("install/done/");
        changeLink("done");
    }

    return false;
}

function welcome() {
    change_header("Welcome to Theamus!");
    change_content("install/");
    return false;
}


$(document).ready(function() {
    welcome();
});