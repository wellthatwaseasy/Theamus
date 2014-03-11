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
        install_feature();
    });
});
