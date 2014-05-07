<script type="text/javascript">
    // Define the required steps and the completed steps to decide if the user can be here or not
    var required_steps = ["welcome", "dependencies-check", "database-configuration"],
        steps = JSON.parse(localStorage.getItem("step"));

    // Redirect if there are no steps done
    if (steps === null) {
        window.location = theamus.base_url+"install/database-configuration/";
        return;
    }

    // Redirect the user if they haven't completed the steps up to this point
    for (var r = 0; r < required_steps.length; r++) {
        if (steps.indexOf(required_steps[r]) === -1) {
            window.location = theamus.base_url+"install/database-configuration/";
            return;
        }
    }
</script>

<form class="form" id="custom-secure-form">
    <div class="content col-6">
        <div id="result"></div>
        <div class="form-header">Customization</div>
        <div class="form-group">
            <label for="site-name" class="col-3 control-label">Site Name</label>
            <div class="col-9">
                <input type="text" class="form-control" id="site-name" name="site-name" placeholder="My New Website" autocomplete="off">
            </div>
        </div>

        <div class="form-header">Security</div>
        <div class="form-group">
            <label for="password-salt" class="col-3 control-label">Password Salt</label>
            <div class="col-9">
                <div class="input-group">
                    <input type="text" class="form-control" id="password-salt" name="password-salt" placeholder="" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" id="generate-password-salt">Generate</button>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="session-salt" class="col-3 control-label">Session Salt</label>
            <div class="col-9">
                <div class="input-group">
                    <input type="text" class="form-control" id="session-salt" name="session-salt" placeholder="" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-default" id="generate-session-salt">Generate</button>
                    </span>
                </div>
            </div>
        </div>
        <span class="help-block">
            The salts that are defined here are specific to your web site, and your web site won't work without them.  The purpose of this
            is to increase the security of your web site and data.
        </span>
    </div>

    <div class="next-step-wrapper col-6">
        <button type="button" id="next-step" class="btn btn-primary">Next Step: First User Setup <span class="glyphicon ion-arrow-right-c"></span></button>
    </div>
</form>

<script type="text/javascript">
    $(function() {
        // Update the fields if they are already defined in the local storage
        if (localStorage.getItem("site_name") !== null) {
            $("#site-name").val(localStorage.getItem("site_name"));
        }

        if (localStorage.getItem("salts") !== null) {
            var salts = JSON.parse(localStorage.getItem("salts"));
            $("#password-salt").val(salts['password_salt']);
            $("#session-salt").val(salts['session_salt']);
        }

        function generate_salt() {
            var return_salt = "",
                possible_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i=0; i < 25; i++) {
                return_salt += possible_characters.charAt(Math.floor(Math.random() * possible_characters.length));
            }

            return return_salt;
        }

        $("#generate-password-salt").click(function() {
            $("#password-salt").val(generate_salt());
        });

        $("#generate-session-salt").click(function() {
            $("#session-salt").val(generate_salt());
        });

        $("#next-step").click(function() {
            cs_data = [];

            // Let the user know what's going on and stop them from trying to submit again
            $("#result").html(alert_notify("spinner", "Checking the information provided..."));
            this.setAttribute("disabled", "disabled");

            theamus.ajax.iterate_calls([
                function() {
                    theamus.ajax.api({
                        type:   "post",
                        url:    theamus.base_url+"install/check-custom-and-security/",
                        method: ["Install", "check_custom_security"],
                        data:   {
                            form:   $("#custom-secure-form")
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
                                    cs_data = JSON.parse(rdata.data);
                                    $("#result").html(alert_notify("spinner", "Saving this information for later..."));

                                // Check for errors produced by the API call and show them
                                } else {
                                    $("#result").html(alert_notify("danger", rdata.data));
                                    $("#next-step").attr("disabled", false);
                                }
                            }
                        }
                    });
                },
                function() {
                    // Don't do anything if there's nothing to do
                    if (cs_data.length === 0) {
                        return;
                    }

                    // Define the salts for the local storage
                    var salts = JSON.stringify({"password_salt": cs_data['password-salt'], "session_salt": cs_data['session-salt']});

                    // Update the localstorage with the information
                    localStorage.setItem("site_name", cs_data['site-name']);
                    localStorage.setItem("salts", salts);

                    // Notify the user
                    $("#result").html(alert_notify("success", "Information saved."));
                },
                function() {
                    // Don't do anything if there's nothing to do
                    if (cs_data.length === 0) {
                        return;
                    }

                    // Update the current step
                    if (localStorage.getItem("current_step") === null) {
                        localStorage.setItem("current_step", "first-user-setup");
                    }

                    // Update the local storage to reflect the user has passed this step
                    var steps = JSON.parse(localStorage.getItem("step"));
                    if (steps.indexOf("customization-and-security") === -1) {
                        steps.push("customization-and-security");
                    }
                    localStorage.setItem("step", JSON.stringify(steps));

                    // Set the result to nothing, just cuz.
                    $("#result").html("");

                    // Move on to the next step
                    window.location = theamus.base_url+"install/first-user-setup/";
                }
            ], 1);
        });
    });
</script>