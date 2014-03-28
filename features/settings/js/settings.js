var update_button = $("#update"),
    update_result = $("#update-result");

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
    update_button.click(function() {
        if ($(this).val() === 'Update') {
            $(this).val("Updating...");
            $(this).attr('disabled', true);
            $('#update-result').html(
                    notify("admin", "info", '<img src="themes/admin/img/loading.gif" align="middle" ' +
                    'style="margin-top:-10px; height:22px" />' +
                '<span style="padding-left:10px;">Please wait while your system updates...</span>')
            );

                // Run the check for updates
                theamus.ajax.api({
                    type:   "get",
                    url:    "settings/auto-update/",
                    method: ["SettingsApi", "auto_update"],
                    success:function(data) {
                        console.log(data);
                    }
                });
        } else {
            $(this)[0].value = "Checking..."; // Update the button text

            // Run the check for updates
            theamus.ajax.api({
                type:   "get",
                url:    "settings/update-check/",
                method: ["SettingsApi", "update_check"],
                success:function(data) {
                    var error = true;
                    if (typeof data === "object") {
                        if (data.error.status === 1) {
                            update_result.html(notify("admin", "failure", "There was an error checking for updates."));
                        } else {
                            if (data.response.data === true) {
                                update_result.html(notify("admin", "success", "There is an update available for your website!"));
                                prepare_update();
                                error = false;
                            } else {
                                update_result.html(notify("admin", "info", "There are no updates available at this time."));
                            }
                        }
                    } else {
                        update_result.html(notify("admin", "failure", "There was an error checking for updates."));
                    }

                    if (error === true) {
                        back_to_check();
                    }
                }
            });
        }
    });
});

function finishUpdate() {
    $('#update')[0].value = 'Done!';
    reload(1000);
}