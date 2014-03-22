function install_feature() {
    $("#install-result").html(working());
    theamus.ajax.run({
        url:    "features/install/install/",
        result: "install-result",
        form:   "feature_install-form",
        after:  function() {
            $("#feature_install-button").attr("disabled", true);
            countdown("Back to list of features in", 3);
            setTimeout(function() {
                admin_go("features", "features/");
            }, 3000);
        }
    });
}

function upload_listen() {
    $("[name='file']").change(function(e) {
        theamus.ajax.run({
            url: "features/install/prelim/",
            result: "prelim-notes",
            form: "feature_install-form",
            after: function() {
                $("#feature_prelim-info-wrapper").show();
                $("[name='file']").prop("disabled", "true");
            }
        });
    });
}

function back_to_list() {
    countdown("Back to list in", 3);
    setTimeout(function() {
        admin_go("features", "features/");
    }, 3000);
}

$(document).ready(function() {
    $("[name='cancel']").click(function() {
        admin_go('features', 'features/');
    });

    $("#feature_install-form").submit(function(e) {
        e.preventDefault();
        install_feature();
    });

    upload_listen();
});
