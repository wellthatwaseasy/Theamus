function install_feature() {
	theamus.ajax.run({
        url:   "features/upload/",
		result: "install-result",
        form: "install-form",
        upload: {
            growbar: "upload-progress",
            percentage: "upload-percentage",
            hide_time: 10,
            hide: true
        }
	});

	return false;
}

function upload_listen() {
    $("[name='file']").change(function(e) {
        theamus.ajax.run({
            url:    "features/install/prelim/",
            result: "prelim-notes",
            form:   "feature_install-form",
            after:  function() {
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

    $("#install-form").submit(function(e) {
        e.preventDefault();
        //install_feature();
    });

    upload_listen();
});
