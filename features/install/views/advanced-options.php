<script type="text/javascript">
    // Define the required steps and the completed steps to decide if the user can be here or not
    var required_steps = ["welcome", "dependencies-check", "database-configuration", "customization-and-security"],
        steps = JSON.parse(localStorage.getItem("step"));

    // Redirect if there are no steps done
    if (steps === null) {
        window.location = theamus.base_url+"install/first-user-setup/";
        return;
    }

    // Redirect the user if they haven't completed the steps up to this point
    for (var r = 0; r < required_steps.length; r++) {
        if (steps.indexOf(required_steps[r]) === -1) {
            window.location = theamus.base_url+"install/first-user-setup/";
            return;
        }
    }
</script>

<form class="form" id="advanced-options-form">
    <div class="content col-6">
        <div id="result"></div>
        <div class="form-header">Email Configuration</div>
        <div class="form-group">
            <label class="checkbox">
                <input type="checkbox" name="configure-email">
                Configure this sites email settings?
            </label>
        </div>
        <div id="email-config-wrapper" style="display: none;">
            <div class="form-group">
                <label for="email-host" class="col-4 control-label">Host</label>
                <div class="col-8">
                    <input type="text" class="form-control" id="email-host" name="email-host" placeholder="smtp.example.com" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="email-protocol" class="col-4 control-label">Protocol</label>
                <div class="col-8">
                    <select class="form-control" id="email-protocol" name="email-protocol">
                        <option value="tcp">TCP</option>
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="email-port" class="col-4 control-label">Port</label>
                <div class="col-8">
                    <input type="text" class="form-control form-control-inline" id="email-port" name="email-port" placeholder="25" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="email-login-username" class="col-4 control-label">Login Username</label>
                <div class="col-8">
                    <input type="text" class="form-control" id="email-login-username" name="email-login-username" placeholder="username | user@example.com" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="email-login-password" class="col-4 control-label">Login Password</label>
                <div class="col-8">
                    <input type="password" class="form-control" id="email-login-password" name="email-login-password">
                </div>
            </div>
        </div>

        <div class="form-header">Developer Mode</div>
        <div class="form-group">
            <label class="checkbox">
                <input type="checkbox" name="developer-mode">
                Turn on Developer Mode
            </label>
        </div>
        <div class="help-block">Turning on Developer Mode allows access to things that wouldn't be a great idea to have on a production site.  These things include showing errors, seeing page information, and more.</div>
    </div>

    <div class="next-step-wrapper col-6">
        <button type="button" id="next-step" class="btn btn-primary">Next Step: Install and Finish <span class="glyphicon ion-arrow-right-c"></span></button>
    </div>
</form>

<script type="text/javascript">
    $(function() {
        // Update the fields if they are already defined in the local storage
        if (localStorage.getItem("advanced_options") !== null) {
            var options_data = JSON.parse(localStorage.getItem("advanced_options"));

            $("[name='configure-email']")[0].checked = options_data['configure-email'] !== "false" ? true : false;
            if (options_data['configure-email'] !== "false") {
                $("#email-config-wrapper").show();
                $("#email-host").val(options_data['email-host']);
                $("#email-protocol").val(options_data['email-protocol']);
                $("#email-port").val(options_data['email-port']);
                $("#email-login-username").val(options_data['email-login-username']);
                $("#email-login-password").val(options_data['email-login-password']);
            }

             $("[name='developer-mode']")[0].checked = options_data['developer-mode'] !== "false" ? true : false;
        }

        options_data = [];

        // Show or hide the email configuration settings
        $("[name='configure-email']").click(function() {
            if (this.checked === true) {
                $("#email-config-wrapper").show();
            } else {
                $("#email-config-wrapper").hide();
            }
        });

        $("#next-step").click(function() {
            // Let the user know what's going on and stop them from trying to submit again
            $("#result").html(alert_notify("spinner", "Checking the provided information..."));
            $("#next-step").attr("disabled", true); // disable the submit button

            // Iterate functions to show the process of saving
            theamus.ajax.iterate_calls([
                function() {
                    theamus.ajax.api({
                        type:   "post",
                        url:    theamus.base_url+"/install/check-advanced-options/",
                        method: ["Install", "check_advanced_options"],
                        data:   {
                            form:   $("#advanced-options-form")
                        },
                        success:function(data) {
                            // Show a generic error if the data doesn't come back as an object
                            if (typeof(data) !== "object" || data.error.status !== 0) {
                                $("#result").html(alert_notify("danger", "There was an error checking the information provided."));
                                $("#next-step").attr("disabled", false); // re-enable the submit button
                            } else {
                                // Define the response data more cleanly
                                var rdata = data.response.data;

                                // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                                if (rdata.error === false) {
                                    options_data = rdata.data;
                                    $("#result").html(alert_notify("spinner", "Saving this information for later..."));

                                // Check for errors produced by the API call and show them
                                } else {
                                    $("#result").html(alert_notify("danger", rdata.data));
                                    $("#next-step").attr("disabled", false); // re-enable the submit button
                                }
                            }
                        }
                    })
                },
                function() {
                    // Die if something beforehand failed
                    if (options_data.length === 0) {
                        return;
                    }

                    // Update the local storage with the new information
                    localStorage.setItem("advanced_options", options_data);

                    // Notify the user
                    $("#result").html(alert_notify("success", "Information saved successfully."));
                },
                function() {
                    // Die if something beforehand failed
                    if (options_data.length === 0) {
                        return;
                    }

                    // Update the current step
                    localStorage.setItem("current_step", "review-and-install");

                    // Update the local storage to reflect the user has passed this step
                    var steps = JSON.parse(localStorage.getItem("step"));
                    if (steps.indexOf("advanced-options") === -1) {
                        steps.push("advanced-options");
                    }
                    localStorage.setItem("step", JSON.stringify(steps));

                    // Set the result to nothing, just cuz.
                    $("#result").html("");

                    // Move on to the next step
                    window.location = theamus.base_url+"install/review-and-install/";
                }
            ], 1);
        });
    });
</script>