<script type="text/javascript">
    // Define the required steps and the completed steps to decide if the user can be here or not
    var required_steps = ["welcome", "dependencies-check", "database-configuration", "customization-and-security", "first-user-setup"],
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

<form class="form">
    <div class="content col-10">
        <div id="result"></div>
        <div class="col-6">
            <div class="form-header">Database Configuration</div>
            <div class="form-group">
                <label class="col-4 control-label">Host</label>
                <div class="col-8" id="database-host"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Login Username</label>
                <div class="col-8" id="database-login-username"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Login Password</label>
                <div class="col-8" id="database-login-password"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Database Name</label>
                <div class="col-8" id="database-name"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Table Prefixes</label>
                <div class="col-8" id="database-table-prefix"></div>
            </div>

            <div class="form-header">Customization and Security</div>
            <div class="form-group">
                <label class="col-4 control-label">Site Name</label>
                <div class="col-8" id="site-name"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Password Salt</label>
                <div class="col-8" id="password-salt"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Session Salt</label>
                <div class="col-8" id="session-salt"></div>
            </div>
        </div>

        <div class="col-6">
            <div class="form-header">First User</div>
            <div class="form-group">
                <label class="col-4 control-label">Username</label>
                <div class="col-8" id="account-username"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Password</label>
                <div class="col-8" id="account-password"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Email Address</label>
                <div class="col-8" id="account-email"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">First Name</label>
                <div class="col-8" id="account-firstname"></div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Last Name</label>
                <div class="col-8" id="account-lastname"></div>
            </div>

            <div class="form-header">Advanced Settings</div>
            <div class="form-group">
                <label class="col-4 control-label">Configure Email</label>
                <div class="col-8" id="email-config"></div>
            </div>
            <div id="email-config-wrapper" style="display: none;">
                <div class="form-group">
                    <label class="col-4 control-label">Email Host</label>
                    <div class="col-8" id="email-host"></div>
                </div>
                <div class="form-group">
                    <label class="col-4 control-label">Email Protocol</label>
                    <div class="col-8" id="email-protocol"></div>
                </div>
                <div class="form-group">
                    <label class="col-4 control-label">Email Port</label>
                    <div class="col-8" id="email-port"></div>
                </div>
                <div class="form-group">
                    <label class="col-4 control-label">Email Login Username</label>
                    <div class="col-8" id="email-login-username"></div>
                </div>
                <div class="form-group">
                    <label class="col-4 control-label">Email Login Password</label>
                    <div class="col-8" id="email-login-password"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-4 control-label">Developer Mode</label>
                <div class="col-8" id="developer-mode"></div>
            </div>
        </div>
    </div>

    <div class="next-step-wrapper col-10">
        <button type="button" id="next-step" class="btn btn-primary">Install Theamus <span class="glyphicon ion-arrow-right-c"></span></button>
    </div>
</form>

<script type="text/javascript">
    function replace_with_stars(text) {
        ret = "";
        for (var i = 0; i < text.length; i++) {
            ret += "*";
        }
        return ret;
    }

    $(function() {
        // Update the fields for the database configuration if they are already defined in the local storage
        if (localStorage.getItem("database_configuration_data") !== null) {
            var db_config_data = JSON.parse(localStorage.getItem("database_configuration_data"));

            $("#database-host").html(db_config_data['database-host']);
            $("#database-login-username").html(db_config_data['database-login-username']);
            $("#database-login-password").html(replace_with_stars(db_config_data['database-login-password']));
            $("#database-name").html(db_config_data['database-name']);
            $("#database-table-prefix").html(db_config_data['database-table-prefix']);
        }


        // Update the fields for the customization and security if they are already defined in the local storage
        if (localStorage.getItem("site_name") !== null) {
            $("#site-name").html(localStorage.getItem("site_name"));
        }

        if (localStorage.getItem("salts") !== null) {
            var salts = JSON.parse(localStorage.getItem("salts"));
            $("#password-salt").html(salts['password_salt']);
            $("#session-salt").html(salts['session_salt']);
        }

        // Update the fields for the first user if they are already defined in the local storage
        if (localStorage.getItem("first_user_data") !== null) {
            var user_config_data = JSON.parse(localStorage.getItem("first_user_data"));

            $("#account-username").html(user_config_data['username']);
            $("#account-password").html(replace_with_stars(user_config_data['password']));
            $("#account-email").html(user_config_data['email']);
            $("#account-firstname").html(user_config_data['firstname']);
            $("#account-lastname").html(user_config_data['lastname']);
        }


        // Update the fields for the advanced options if they are already defined in the local storage
        if (localStorage.getItem("advanced_options") !== null) {
            var options_data = JSON.parse(localStorage.getItem("advanced_options"));

            $("#email-config").html(options_data['configure-email'] !== "false" ? "Yes" : "No");
            if (options_data['configure-email'] !== "false") {
                $("#email-config-wrapper").show();
                $("#email-host").html(options_data['email-host']);
                $("#email-protocol").html(options_data['email-protocol']);
                $("#email-port").html(options_data['email-port']);
                $("#email-login-username").html(options_data['email-login-username']);
                $("#email-login-password").html(replace_with_stars(options_data['email-login-password']));
            }

             $("#developer-mode").html(options_data['developer-mode'] !== "false" ? "On" : "Off");
        }

        $("#next-step").click(function() {
            // Let the user know what's going on and stop them from trying to submit again
            scroll_top();
            $("#result").html(alert_notify("spinner", "Initializing installation..."));
            $("#next-step").attr("disabled", true); // disable the submit button

            theamus.ajax.iterate_calls([
                function () {
                    // Define information to be accessible to all of the functions in this iteration
                    database_information    = JSON.parse(localStorage.getItem("database_configuration_data"));
                    site_name               = localStorage.getItem("site_name");
                    site_security           = JSON.parse(localStorage.getItem("salts"));
                    user_information        = JSON.parse(localStorage.getItem("first_user_data"));
                    advanced_options        = JSON.parse(localStorage.getItem("advanced_options"));

                    // Progress to the next step
                    $("#result").html(alert_notify("spinner", "Writing the configuration file..."));
                },
                function () {
                    return_true = true;

                    // Call the Theamus Installer API to create a new configuration file
                    return theamus.ajax.api({
                        type:   "post",
                        url:    theamus.base_url+"install/create-config-file/",
                        method: ["Install", "create_config_file"],
                        data:   {
                            custom: {
                                database:       JSON.stringify(database_information),
                                security:       JSON.stringify(site_security),
                            }
                        },
                        success:function(data) {
                            // Show a generic error if the data doesn't come back as an object
                            if (typeof(data) !== "object" || data.error.status !== 0) {
                                $("#result").html(alert_notify("danger", "There was an error creating the configuration file."));
                                $("#next-step").attr("disabled", false); // re-enable the submit button
                                return false;
                            } else {
                                // Define the response data more cleanly
                                var rdata = data.response.data;

                                // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                                if (rdata.error === false) {
                                    $("#result").html(alert_notify("spinner", "Creating the database structure..."));

                                    return true;

                                // Check for errors produced by the API call and show them
                                } else {
                                    $("#result").html(alert_notify("danger", rdata.data));
                                    $("#next-step").attr("disabled", false); // re-enable the submit button
                                    return false;
                                }
                            }
                        }
                    });
                },
                function() {
                    // Call the Theamus Installer API to create the database information
                    return theamus.ajax.api({
                        type:   "post",
                        url:    theamus.base_url+"install/create-database-structure/",
                        method: ["Install", "create_database_structure"],
                        data:   {
                            custom: {
                                database:   JSON.stringify(database_information)
                            }
                        },
                        success:function(data) {
                            // Show a generic error if the data doesn't come back as an object
                            if (typeof(data) !== "object" || data.error.status !== 0) {
                                $("#result").html(alert_notify("danger", "There was an error creating the database structure."));
                                $("#next-step").attr("disabled", false); // re-enable the submit button
                                return false;
                            } else {
                                // Define the response data more cleanly
                                var rdata = data.response.data;

                                // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                                if (rdata.error === false) {
                                    $("#result").html(alert_notify("spinner", "Adding the database information..."));

                                    return true;

                                // Check for errors produced by the API call and show them
                                } else {
                                    $("#result").html(alert_notify("danger", rdata.data));
                                    $("#next-step").attr("disabled", false); // re-enable the submit button
                                    return false;
                                }
                            }
                        }
                    })
                },
                function() {
                    // Call the Theamus Installer API to create the database information
                    return theamus.ajax.api({
                        type:   "post",
                        url:    theamus.base_url+"install/add-database-data/",
                        method: ["Install", "add_database_data"],
                        data:   {
                            custom: {
                                database:   JSON.stringify(database_information)
                            }
                        },
                        success:function(data) {
                            // Show a generic error if the data doesn't come back as an object
                            if (typeof(data) !== "object" || data.error.status !== 0) {
                                $("#result").html(alert_notify("danger", "There was an error adding the database information."));
                                $("#next-step").attr("disabled", false); // re-enable the submit button
                                return false;
                            } else {
                                // Define the response data more cleanly
                                var rdata = data.response.data;

                                // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                                if (rdata.error === false) {
                                    $("#result").html(alert_notify("spinner", "Creating the first user..."));

                                    return true;

                                // Check for errors produced by the API call and show them
                                } else {
                                    $("#result").html(alert_notify("danger", rdata.data));
                                    $("#next-step").attr("disabled", false); // re-enable the submit button
                                    return false;
                                }
                            }
                        }
                    })
                },
                function() {
                    // Call the Theamus Installer API to create the database information
                    return theamus.ajax.api({
                        type:   "post",
                        url:    theamus.base_url+"install/create-first-user/",
                        method: ["Install", "create_first_user"],
                        data:   {
                            custom: {
                                user:   JSON.stringify(user_information)
                            }
                        },
                        success:function(data) {
                            // Show a generic error if the data doesn't come back as an object
                            if (typeof(data) !== "object" || data.error.status !== 0) {
                                $("#result").html(alert_notify("danger", "There was an error creating the first user."));
                                $("#next-step").attr("disabled", false); // re-enable the submit button
                                return false;
                            } else {
                                // Define the response data more cleanly
                                var rdata = data.response.data;

                                // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                                if (rdata.error === false) {
                                    $("#result").html(alert_notify("spinner", "Wrapping up the installation..."));

                                    return true;

                                // Check for errors produced by the API call and show them
                                } else {
                                    $("#result").html(alert_notify("danger", rdata.data));
                                    $("#next-step").attr("disabled", false); // re-enable the submit button
                                    return false;
                                }
                            }
                        }
                    })
                },
                function() {
                    // Call the Theamus Installer API to create the database information
                    return theamus.ajax.api({
                        type:   "post",
                        url:    theamus.base_url+"install/finish-installation/",
                        method: ["Install", "finish_installation"],
                        data:   {
                            custom: {
                                site_name:  site_name,
                                options:    JSON.stringify(advanced_options)
                            }
                        },
                        success:function(data) {
                            // Show a generic error if the data doesn't come back as an object
                            if (typeof(data) !== "object" || data.error.status !== 0) {
                                console.log(data);
                                $("#result").html(alert_notify("danger", "There was an error wrapping up the installation."));
                                $("#next-step").attr("disabled", false); // re-enable the submit button
                                return false;
                            } else {
                                // Define the response data more cleanly
                                var rdata = data.response.data;

                                // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                                if (rdata.error === false) {
                                    $("#result").html(alert_notify("success", "Theamus Installation completed, everything went smoothly."));

                                    return true;

                                // Check for errors produced by the API call and show them
                                } else {
                                    $("#result").html(alert_notify("danger", rdata.data));
                                    $("#next-step").attr("disabled", false); // re-enable the submit button
                                    return false;
                                }
                            }
                        }
                    })
                },
                function() {
                    localStorage.clear();
                    window.location = theamus.base_url;
                }
            ], 1);
        });
    });
</script>