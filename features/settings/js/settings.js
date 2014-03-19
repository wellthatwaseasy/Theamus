var update_button = $("#update");

function prepare_update() {
    this.update_button.val("Update");
};

function back_to_check() {
    this.update_button.val("Check for Updates");
};

function add_manual_listeners() {
    // Cancel/go back listener
    $("[name='cancel']").click(function(e) {
        e.preventDefault();
        admin_go("settings", "settings/settings/");
    });


    // Preliminary system update listener
    $("[name='file']").change(function(e) {
        e.preventDefault();

        // Disable the file input, show the preliminary area
        $("#settings_prelim-info-wrapper").show();
        $("[name='file']").prop("disabled", "true");

        // Show the working gif in the information wrapper then run the prelim update
        $("#prelim-notes").html(working());
        theamus.ajax.run({
            url:    "settings/prelim-update/",
            result: "prelim-notes",
            form:   "settings_update-form"
        });
    });


    // System update listener
    $("#settings_update-form").submit(function(e) {
        e.preventDefault();

        // Show the working gif in the result wrapper, then run the update script
        $("#update-result").html(working());
        theamus.ajax.run({
            url:    "settings/manual-update/",
            result: "update-result",
            form:   "settings_update-form"
        });
    });
}

$(document).ready(function() {
    $('#update').click(function() {
        if ($(this)[0].value == 'Update!') {
            $(this)[0].value = 'Updating...';
            $(this)[0].setAttribute('disabled', 'disabled');
            $('#update-result').html(
                '<img src="themes/admin/img/loading.gif" align="middle" ' +
                    'style="margin-top:-10px; height:24px" />' +
                '<span style="padding-left:10px;">Please wait while your system updates...</span>'
            );

            setTimeout(function() {
                theamus.ajax.run({
                   url: 'settings/update/',
                   result : 'update-result'
                });
            }, 1000);
        } else {
            $(this)[0].value = "Checking..."; // Update the button text

            // Run the check for updates
            setTimeout(function() {
                theamus.ajax.run({
                    url:        "settings/check-update/",
                    result :    "update-result"
                });
            }, 1000);
        }
    });
});

function finishUpdate() {
    $('#update')[0].value = 'Done!';
    reload(1000);
}