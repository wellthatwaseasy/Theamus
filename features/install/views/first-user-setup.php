<script type="text/javascript">
    // Define the required steps and the completed steps to decide if the user can be here or not
    var required_steps = ["welcome", "dependencies-check", "database-configuration", "customization-and-security"],
        steps = JSON.parse(localStorage.getItem("step"));

    // Redirect if there are no steps done
    if (steps === null) {
        window.location = theamus.base_url+"install/customization-and-security/";
        return;
    }

    // Redirect the user if they haven't completed the steps up to this point
    for (var r = 0; r < required_steps.length; r++) {
        if (steps.indexOf(required_steps[r]) === -1) {
            window.location = theamus.base_url+"install/customization-and-security/";
            return;
        }
    }
</script>

<form class="form" id="first-user-form">
    <div class="content col-6">
        <div id="result"></div>
        <div class="form-header">Account</div>
        <div class="form-group">
            <label for="username" class="col-4 control-label">Username</label>
            <div class="col-8">
                <input type="text" class="form-control" id="username" name="username" placeholder="admin" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-4 control-label">Password</label>
            <div class="col-8">
                <input type="password" class="form-control" id="password" name="password">
            </div>
        </div>
        <div class="form-group">
            <label for="repeat-password" class="col-4 control-label">Repeat Password</label>
            <div class="col-8">
                <input type="password" class="form-control" id="repeat-password" name="repeat-password">
            </div>
        </div>

        <div class="form-header">Contact</div>
        <div class="form-group">
            <label for="email" class="col-4 control-label">Email Address</label>
            <div class="col-8">
                <input type="text" class="form-control" id="email" name="email" placeholder="user@example.com" autocomplete="off">
            </div>
        </div>

        <div class="form-header">Personal</div>
        <div class="form-group">
            <label for="firstname" class="col-4 control-label">First Name</label>
            <div class="col-8">
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="John" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="lastname" class="col-4 control-label">Last Name</label>
            <div class="col-8">
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Doe" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="next-step-wrapper col-6">
        <button type="button" id="advanced-next-step" class="btn btn-default">Advanced Installation Settings</button>
        <button type="submit" id="next-step" class="btn btn-primary">Next Step: Install and Finish <span class="glyphicon ion-arrow-right-c"></span></button>
    </div>
</form>

<script type="text/javascript">
    function check_user_information() {
        // Check the given database to make sure it all works
        theamus.ajax.api({
            type:   "post",
            url:    theamus.base_url+"install/check-first-user/",
            method: ["Install", "check_first_user"],
            data:   {
                form:   $("#first-user-form")
            },
            success:function(data) {
                // Show a generic error if the data doesn't come back as an object
                if (typeof(data) !== "object" || data.error.status !== 0) {
                    $("#result").html(alert_notify("danger", "There was an error checking the information provided."));
                    $("#next-step").attr("disabled", false); // re-enable the submit button
                    $("#advanced-next-step").attr("disabled", false); // re-enable the advanced options submit button
                } else {
                    // Define the response data more cleanly
                    var rdata = data.response.data;

                    // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                    if (rdata.error === false) {
                        first_user_data = rdata.data;
                        $("#result").html(alert_notify("spinner", "Saving this information for later..."));

                    // Check for errors produced by the API call and show them
                    } else {
                        $("#result").html(alert_notify("danger", rdata.data));
                        $("#next-step").attr("disabled", false); // re-enable the submit button
                        $("#advanced-next-step").attr("disabled", false); // re-enable the advanced options submit button
                    }
                }
            }
        });
    }

    function save_user_information() {
        // Die if something beforehand failed
        if (first_user_data.length === 0) {
            return;
        }

        // Define the default advanced options (in case the user is skipping them)
        if (localStorage.getItem("advanced_options") === null) {
            localStorage.setItem("advanced_options", JSON.stringify({
                "configure-email":"false",
                "email-host":"",
                "email-protocol":"",
                "email-port":"",
                "email-login-username":"",
                "email-login-password":"",
                "developer-mode":"false"
            }));
        }

        // Update the local storage with the new information
        localStorage.setItem("first_user_data", first_user_data);

        // Notify the user
        $("#result").html(alert_notify("success", "Information saved successfully."));
    }

    function next_step(where) {
        // Die if something beforehand failed
        if (first_user_data.length === 0) {
            return;
        }

        // Update the current step
        localStorage.setItem("current_step", where);

        // Update the local storage to reflect the user has passed this step
        var steps = JSON.parse(localStorage.getItem("step"));
        if (steps.indexOf("first-user-setup") === -1) {
            steps.push("first-user-setup");
        }
        localStorage.setItem("step", JSON.stringify(steps));

        // Set the result to nothing, just cuz.
        $("#result").html("");

        // Move on to the next step
        window.location = theamus.base_url+"install/"+where+"/";
    }

    $(function() {
        // Update the fields if they are already defined in the local storage
        if (localStorage.getItem("first_user_data") !== null) {
            var user_config_data = JSON.parse(localStorage.getItem("first_user_data"));

            $("#username").val(user_config_data['username']);
            $("#password").val(user_config_data['password']);
            $("#repeat-password").val(user_config_data['repeat-password']);
            $("#email").val(user_config_data['email']);
            $("#firstname").val(user_config_data['firstname']);
            $("#lastname").val(user_config_data['lastname']);
        }


        first_user_data = [];

        $("#advanced-next-step").click(function() {
            // Let the user know what's going on and stop them from trying to submit again
            $("#result").html(alert_notify("spinner", "Checking the provided information..."));
            $("#next-step").attr("disabled", true); // disable the submit button
            $("#advanced-next-step").attr("disabled", true); // disnable the advanced options submit button

            // Iterate functions to show the process of saving
            theamus.ajax.iterate_calls(["check_user_information", "save_user_information",
                function() {
                    next_step("advanced-options");
                }
            ], 1);
        });

        $("#next-step").click(function() {
            // Let the user know what's going on and stop them from trying to submit again
            $("#result").html(alert_notify("spinner", "Checking the provided information..."));
            $("#next-step").attr("disabled", true); // disable the submit button
            $("#advanced-next-step").attr("disabled", true); // disnable the advanced options submit button

            // Iterate functions to show the process of saving
            theamus.ajax.iterate_calls(["check_user_information", "save_user_information",
                function() {
                    next_step("review-and-install");
                }
            ], 1);
        });
    });
</script>