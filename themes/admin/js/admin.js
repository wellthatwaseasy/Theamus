function open_admin(home) {
    $(".site_wrapper").hide();
    $("#admin-nav").addClass("admin_nav-open");
    $("#admin-content").addClass("admin_content-wrapper-open");

    if (home !== false) {
        admin_go("home", "default/adminHome/");
        update_hash("admin:home");
    }

    return false;
}

function close_admin() {
    $(".site_wrapper").show();
    $("#admin-nav").removeClass("admin_nav-open");
    $("#admin-content").removeClass("admin_content-wrapper-open");

    update_hash("");

    return false;
}

$(document).ready(function() {
    if (window.location.hash) {
        var parent;
        var hash = window.location.hash.substring(1);

        if (hash.indexOf(":") > -1) {
            hash = hash.split(":");
            parent = hash[1];
            hash = hash[0];
        } else {
            parent = "home";
        }

        hash = hash.split("/");

        if (hash[0] === "admin") {
            if (hash.length > 1) {
                open_admin(false);
            } else {
                open_admin();
            }
        }

        if (hash.length > 1) {
            hash.shift();
            hash = hash.join("/");

            admin_go(parent, hash);
        }
    }
});

function update_hash(path) {
    window.location.hash = path;
}

function admin_go(parent, path) {
    open_admin(false);
    var links = $(".admin_nav-list")[0].children;

    for (var i = 0; i < links.length; i++) {
        var link = $(links[i]);
        if (link.hasClass("current")) {
            link.removeClass("current");
        }

        if (link[0].getAttribute("parent") === parent) {
            link.addClass("current");
        }
    }

    update_hash("admin/"+path+":"+parent);
    start_loading();

    theamus.ajax.run({
        url: path,
        type: "include",
        result: "admin_page-content",
        after: {
            do_function: ["clear_loading", "add_admin_extras"]
        }
    });

    return false;
}

function start_loading() {
    $("#admin_loading-bg").addClass("admin_loading-bg-open");
    $("#admin_loading-gif").addClass("admin_loading-gif-open");
}

function clear_loading() {
    $("#admin_loading-bg").removeClass("admin_loading-bg-open");
    $("#admin_loading-gif").removeClass("admin_loading-gif-open");
}

function add_admin_extras() {
    // Get all scripts to be added
    var scripts = $('[name="addscript"]');

    // Loop through all of the scripts
    for (var i = 0; i < scripts.length; i++) {
        add_js_file(scripts[i].value);
        $(scripts[i]).remove();
    }

    var styles = $("[name='addstyle']");

    for (var i = 0; i < styles.length; i++) {
        add_css(styles[i].value);
        $(styles[i]).remove();
    }
}

function notDone() {
    alert("This part of Theamus isn't ready for you!");
    return false;
}
