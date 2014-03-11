<div id="result"></div>
<form class="site-form" id="custom-form">
	<div class="site-formrow" row="site-name">
		<div class="site-formlabel">Site Name</div>
		<div class="site-forminput">
			<input type="text" name="name" id="name" maxlength="100"
			 autocomplete="off" autocapitalize="off" autocorrect="off"
			 spellcheck="off" />
		</div>
		<div class="site-forminfo">
			<span>Your company's name, your name, any name, just a name for your
				site.</span>
		</div>
	</div>

    <div class="site-formheader">Site Email</div>
	<div class="site-formrow" row="config-email">
        <div class="site-formlabel ifl-float">Configure Email</div>
        <div class="site-forminput">
            <div class="site-cboxwrapper">
                <input type="checkbox" class="site-switchcbox" name="config-email"
                       id="config-email" onchange="show_email_config();">
                <label class="site-switchlabel yn" for="config-email">
                    <span class="site-switchinner"></span>
                    <span class="site-switchswitch"></span>
                </label>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="site-forminfo">
            If you choose not to do this, that's okay.  You can do it later,
            but for the time being you won't get any site notifications.
        </div>
	</div>

	<div id="email-container" class="togrow">
		<div class="site-formheader">Email Configuration</div>
		<div class="site-formrow" row="host">
			<div class="site-formlabel">Host</div>
			<div class="site-forminput">
				<input type="text" name="host" id="host" maxlength="100"
				 autocomplete="off" autocapitalize="off" autocorrect="off"
				 spellcheck="off" />
			</div>
			<div class="site-forminfo">
				<span>The host address for your email service</span>
			</div>
		</div>

		<div class="site-formrow" row="protocol">
			<div class="site-formlabel">Protocol</div>
			<div class="site-forminput">
				<label class="site-selectlabel" for="protocol">
					<select name="protocol" id="protocol">
						<option value="tcp">TCP</option>
						<option value="ssl">SSL</option>
						<option value="tls">TLS</option>
					</select>
				</label>
			</div>
			<div class="site-forminfo">
				<span>This is the security you want to use when authenticating/sending
					emails.</span>
			</div>
		</div>

		<div class="site-formrow" row="port">
			<div class="site-formlabel">Port</div>
			<div class="site-forminput">
				<input type="text" name="port" id="port" maxlength="10"
				autocomplete="off" autocapitalize="off" autocorrect="off"
				spellcheck="off" style="width: 75px;" value="25" />
			</div>
			<div class="site-forminfo">
				<span>This is the port you want to use to connect to your email
					service.</span>
			</div>
		</div>

		<hr />

		<div class="site-formrow" row="email">
			<div class="site-formlabel">Email Address</div>
			<div class="site-forminput">
				<input type="email" name="email" id="email" maxlength="150"
					autocomplete="off" autocapitalize="off" autocorrect="off"
					spellcheck="off" />
			</div>
			<div class="site-forminfo">
				<span>The email address that will send the emails out.  AKA the username
					to the email service.</span>
			</div>
		</div>

		<div class="site-formrow" row="password">
			<div class="site-formlabel">Password</div>
			<div class="site-forminput">
				<input type="password" name="password" id="password" maxlength="50" />
			</div>
			<div class="site-forminfo">
				<span>The password to login with the email address provided.</span>
			</div>
		</div>
	</div>

	<div class="site-formheader">Developer Options</div>
	<div class="site-formrow" row="errors">
        <div class="site-formlabel ifl-float">Display Errors</div>
        <div class="site-forminput">
            <div class="site-cboxwrapper">
                <input type="checkbox" class="site-switchcbox" name="errors"
                       id="errors">
                <label class="site-switchlabel yn" for="errors">
                    <span class="site-switchinner"></span>
                    <span class="site-switchswitch"></span>
                </label>
            </div>
            <div class="clearfix"></div>
        </div>
		<div class="site-forminfo">
			<span>If you choose to display errors, it will show all of the errors
				that occur on any page.</span>
		</div>
	</div>

    <hr />

	<div class="site-formsubmitrow">
		<input type="submit" value="Save and Go Next" class="site-greenbtn" />
	</div>
</form>
