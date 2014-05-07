<script type="text/javascript">
    // Define the required steps and the completed steps to decide if the user can be here or not
    var required_steps = ["welcome", "dependencies-check"],
        steps = JSON.parse(localStorage.getItem("step"));

    // Redirect if there are no steps done
    if (steps === null) {
        window.location = theamus.base_url+"install/dependencies-check/";
        return;
    }

    // Redirect the user if they haven't completed the steps up to this point
    for (var r = 0; r < required_steps.length; r++) {
        if (steps.indexOf(required_steps[r]) === -1) {
            window.location = theamus.base_url+"install/dependencies-check/";
            return;
        }
    }
</script>

<form class="form" id="database-configuration-form">
    <div class="content col-6">
        <div id="result"></div>
        <div class="form-group">
            <label for="database-host" class="col-4 control-label">Host</label>
            <div class="col-8">
                <input type="text" class="form-control" id="database-host" name="database-host" placeholder="localhost | 192.168.1.1" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="database-login-username" class="col-4 control-label">Login Username</label>
            <div class="col-8">
                <input type="text" class="form-control" id="database-login-username" name="database-login-username" placeholder="root" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="database-login-password" class="col-4 control-label">Login Password</label>
            <div class="col-8">
                <input type="password" class="form-control" id="database-login-password" name="database-login-password">
            </div>
        </div>
        <div class="form-group">
            <label for="database-name" class="col-4 control-label">Database Name</label>
            <div class="col-8">
                <input type="text" class="form-control" id="database-name" name="database-name" placeholder="theamus_db" autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="database-table-prefix" class="col-4 control-label">Table Prefix</label>
            <div class="col-8">
                <input type="text" class="form-control" id="database-table-prefix" name="database-table-prefix" value="tm_">
            </div>
        </div>
    </div>

    <div class="next-step-wrapper col-6">
        <button type="submit" id="next-step" class="btn btn-primary">Next Step: Site Customization and Security <span class="glyphicon ion-arrow-right-c"></span></button>
    </div>
</form>

<script type="text/javascript">
$(function() {
    // Update the fields if they are already defined in the local storage
    if (localStorage.getItem("database_configuration_data") !== null) {
        var db_config_data = JSON.parse(localStorage.getItem("database_configuration_data"));

        $("#database-host").val(db_config_data['database-host']);
        $("#database-login-username").val(db_config_data['database-login-username']);
        $("#database-login-password").val(db_config_data['database-login-password']);
        $("#database-name").val(db_config_data['database-name']);
        $("#database-table-prefix").val(db_config_data['database-table-prefix']);
    }

    $("#next-step").click(function() {
        database_configuration_data = [];
        database_connection = false;

        // Let the user know what's going on and stop them from trying to submit again
        $("#result").html(alert_notify("spinner", "Checking database configuration information..."));
        this.setAttribute("disabled", "disabled");

        // Iterate functions to show the process of saving
        theamus.ajax.iterate_calls([
            function() {
                // Check the given database to make sure it all works
                return theamus.ajax.api({
                    type:   "post",
                    url:    theamus.base_url+"install/check-database-configuration/",
                    method: ["Install", "check_database_configuration"],
                    data:   {
                        form:   $("#database-configuration-form")
                    },
                    success:function(data) {
                        // Show a generic error if the data doesn't come back as an object
                        if (typeof(data) !== "object" || data.error.status !== 0) {
                            $("#result").html(alert_notify("danger", "There was an error checking the database information provided."));
                            $("#next-step").attr("disabled", false); // re-enable the submit button
                            return false;
                        } else {
                            // Define the response data more cleanly
                            var rdata = data.response.data;

                            // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                            if (rdata.error === false) {
                                database_configuration_data = rdata.data;
                                $("#result").html(alert_notify("spinner", "Attempting to connect to the database..."));

                                return true;

                            // Check for errors produced by the API call and show them
                            } else {
                                $("#result").html(alert_notify("danger", rdata.data));
                                $("#next-step").attr("disabled", false);
                                return false;
                            }
                        }
                    }
                });
            },
            function() {
                // Check the database information against actually connecting to the database
                return theamus.ajax.api({
                    type:   "post",
                    url:    theamus.base_url+"install/check-database-connection/",
                    method: ["Install", "check_database_connection"],
                    data:   {
                        custom: {
                            config: database_configuration_data
                        }
                    },
                    success:function(data) {
                        // Show a generic error if the data doesn't come back as an object
                        if (typeof(data) !== "object" || data.error.status !== 0) {
                            $("#result").html(alert_notify("danger", "There was an error trying to connect to the database."));
                            $("#next-step").attr("disabled", false); // re-enable the submit button
                            return false;
                        } else {
                            // Define the response data more cleanly
                            var rdata = data.response.data;

                            // Define the data to be used later on and set up a message to let the user know we're going on to the next step
                            if (rdata.error === false) {
                                database_connection = true;
                                $("#result").html(alert_notify("spinner", "Saving the database information for later..."));

                                return true;

                            // Check for errors produced by the API call and show them
                            } else {
                                $("#result").html(alert_notify("danger", rdata.data));
                                $("#next-step").attr("disabled", false);
                                return false;
                            }
                        }
                    }
                });
            },
            function() {
                // Update the local storage with the new information
                localStorage.setItem("database_connection", true);
                localStorage.setItem("database_configuration_data", database_configuration_data);

                // Notify the user
                $("#result").html(alert_notify("success", "Information saved successfully."));

                return true;
            },
            function() {
                // Update the current step
                if (localStorage.getItem("current_step") === null) {
                    localStorage.setItem("current_step", "customization-and-security");
                }

                // Update the local storage to reflect the user has passed this step
                var steps = JSON.parse(localStorage.getItem("step"));
                if (steps.indexOf("database-configuration") === -1) {
                    steps.push("database-configuration");
                }
                localStorage.setItem("step", JSON.stringify(steps));

                // Set the result to nothing, just cuz.
                $("#result").html("");

                // Move on to the next step
                window.location = theamus.base_url+"install/customization-and-security/";
            }
        ], 1);
    });
});
</script>