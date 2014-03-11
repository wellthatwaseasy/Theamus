<?php
if (file_exists(path(ROOT."/config.php"))) {
	notify("install", "warn", "You've already set up your configuration file!<br />".
		"<a href='#' id='continue'>Continue Anyways</a> or ".
		"<a href='#' id='config-reset'>Reset</a><br />".js_countdown());
}
?>

<div id="result"></div>
<form class="site-form" id="config-form">

	<div class="site-formheader">
		Database Connection Information
	</div>

	<div class="site-formrow">
		<div class="site-formlabel">Database Host</div>
		<div class="site-forminput">
			<input type="text" name="host" id="host" maxlength="100"
				autocomplete="off" autocapitalize="off" autocorrect="off"
				spellcheck="off" />
		</div>
		<div class="site-forminfo">
			<span>The host address used to connect to the database.</span>
		</div>
	</div>

	<div class="site-formrow">
		<div class="site-formlabel">Database Username</div>
		<div class="site-forminput">
			<input type="text" name="username" id="username" maxlength="100"
				autocomplete="off" autocapitalize="off" autocorrect="off"
				spellcheck="off" />
		</div>
		<div class="site-forminfo">
			<span>The username used to connect to the database.</span>
		</div>
	</div>

	<div class="site-formrow">
		<div class="site-formlabel">Database Password</div>
		<div class="site-forminput">
			<input type="password" name="password" id="password" maxlength="100" />
		</div>
		<div class="site-forminfo">
			<span>The password used to connect to the database.</span>
		</div>
	</div>

	<div class="site-formrow">
		<div class="site-formlabel">Database Name</div>
		<div class="site-forminput">
			<input type="text" name="name" id="name" maxlength="100"
				autocomplete="off" autocapitalize="off" autocorrect="off"
				spellcheck="off" />
		</div>
		<div class="site-forminfo">
			<span>The name of the database you want to store the
				information for this system. (Must exist and be empty)</span>
		</div>
	</div>

	<div class="site-formheader">Location Information</div>
	<div class="site-formrow">
		<div class="site-formlabel">Time Zone</div>
		<div class="site-forminput">
			<label class="site-selectlabel" for="timezone">
				<select name="timezone" id="timezone">
					<option value="America/Chicago">America/Chicago</option>
					<option value="America/New_York">America/New York</option>
					<option value="America/Los_Angeles">America/Los Angeles</option>
				</select>
			</label>
		</div>
		<div class="site-forminfo">
			<span>We use your time zone so we know how to configure the
				times used in this system.</span>
		</div>
	</div>

	<div class="site-formheader">Salt, not Pepper</div>
	<div class="site-formrow">
		<div class="site-formlabel">Password Salt</div>
		<div class="site-forminput">
			<input type="text" name="pass-salt" id="pass-salt" maxlength="150"
				autocomplete="off" autocapitalize="off" autocorrect="off"
				spellcheck="off" />
		</div>
		<div class="site-forminfo">
			<span>In order to better secure your user's passwords, add something
				unique.</span>
		</div>
	</div>
	<div class="site-formrow">
		<div class="site-formlabel">Session Salt</div>
		<div class="site-forminput">
			<input type="text" name="sess-salt" id="sess-salt" maxlength="150"
				autocomplete="off" autocapitalize="off" autocorrect="off"
				spellcheck="off" />
		</div>
		<div class="site-forminfo">
			<span>The purpose here is to better secure your user's sessions so they
				aren't hijacked.  Make it different than the password salt!</span>
		</div>
	</div>

    <hr />

	<div class="site-formsubmitrow">
		<input type="submit" value="Write Config and Go Next" class="site-greenbtn" />
	</div>

</form>