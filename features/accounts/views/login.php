<div id="login-result"></div>

<form class="form" id="login-form">
    <!-- Username -->
    <div class="form-group">
        <label class="control-label" for="username">Username</label>
        <input type="text" id="username" name="username" class="form-control">
    </div>

    <!-- Password -->
    <div class="form-group">
        <label class="control-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control">
    </div>

    <hr class="form-split">

    <!-- Session Preserve -->
    <div class="form-group">
        <label>
            Stay logged in
            <input type="checkbox" name="keep_session">
        </label>
    </div>

	<div class="form-button-group">
        <button type="submit" class="form-control btn btn-primary">Login</button>
	</div>
</form>

<script type="text/javascript">
    $(function() {
        $("#login-form").submit(function(e) {
            e.preventDefault();

            var login_result = $("#login-result");

            theamus.ajax.api({
                type: "get",
                url: "accounts/user-login/",
                method: ["AccountsApi", "login"],
                data: {
                    form: $("#login-form")
                },
                success: function(data) {
                    console.log(data);
                    if (typeof data !== "object") {
                        login_result.html(alert_notify("danger", "There was an issue logging in."));
                    } else {
                        if (data.error.status === 1) {
                            login_result.html(alert_notify("danger", data.error.message));
                        } else {
                            var response = data.response.data;
                            if (response === true) {
                                window.location = theamus.base_url;
                            } else {
                                login_result.html(alert_notify("danger", response.error.message));
                            }
                        }
                    }
                }
            });
        });
    });
</script>