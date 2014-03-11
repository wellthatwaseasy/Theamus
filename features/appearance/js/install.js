function install_theme() {
    $("#upload-result").html(working());
    theamus.ajax.run({
        url:    "appearance/install/",
        result: "upload-result",
        form:   "appearance_install-form"
    });
}

function back_to_list() {
    countdown("Back to list in", 3);
    setTimeout(function() {
            admin_go("settings", "appearance/");
    }, 3000);
}

function upload_listen() {
    $("[name='file']").change(function(e) {
        theamus.ajax.run({
            url:    "appearance/prelim-install/",
            result: "prelim-notes",
            form:   "appearance_install-form",
            after:  function() {
                $("#appearance_prelim-info-wrapper").show();
                $("[name='file']").prop("disabled", "true");
            }
        });
    });
}

$(document).ready(function() {
    upload_listen();
    
    $("[name='cancel']").click(function() {
        admin_go("settings", "appearance/");
    });

    $("#appearance_install-form").submit(function(e) {
        e.preventDefault();
        install_theme();
    });
});