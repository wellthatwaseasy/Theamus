<div id="result"></div>
<form class="site-form" id="user-form">
    <?php if ($Installer->check_admin_user()): ?>
    <div class="site-formheader">Existing Users Detected</div>
    <div class="side-formrow">
        <div class="site-formlabel">Reset existing users</div>
        <div class="site-forminput">
            <div class="site-cboxwrapper">
                <input type="checkbox" class="site-switchcbox" name="reset" id="reset">
                <label class="site-switchlabel yn" for="reset">
                    <span class="site-switchinner"></span>
                    <span class="site-switchswitch"></span>
                </label>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="site-forminfo">
            This will erase all existing users to give you a fresh start.
            <br /><br />
            If you already have users registered to this site, you don't need to fill
            anything out here.
        </div>
    </div>
    <?php endif; ?>
    <div class="site-formheader">Login Credentials</div>
	<div class="site-formrow">
		<div class="site-formlabel">Username</div>
		<div class="site-forminput">
			<input type="text" name="username" id="username" maxlength="25"
			  value="admin" autocomplete="off" />
		</div>
		<div class="site-forminfo">
			<span>The username is 'admin' by default but it doesn't have to be.</span>
		</div>
	</div>

	<div class="site-formrow">
		<div class="site-formlabel">Password</div>
		<div class="site-forminput">
			<input type="password" name="password" id="password" maxlength="50" />
		</div>
		<div class="site-forminfo">
			<span>The password must be between 4 and 25 characters in length.</span>
		</div>
	</div>

	<div class="site-formrow">
		<div class="site-formlabel">Repeat Password</div>
		<div class="site-forminput">
			<input type="password" name="repeatPass" id="repeatPass" maxlength="50" />
		</div>
		<div class="site-forminfo">
			<span>This password must match the first password.</span>
		</div>
	</div>

    <div class="site-formheader">Personal Information</div>
    <div class="site-formrow">
		<div class="site-formlabel">First Name</div>
		<div class="site-forminput">
			<input type="text" name="firstname" maxlength="30"
				autocomplete="off" />
		</div>
		<div class="site-forminfo">
            Simply just your first name.
		</div>
	</div>
    <div class="site-formrow">
		<div class="site-formlabel">Last Name</div>
		<div class="site-forminput">
			<input type="text" name="lastname" maxlength="30"
				autocomplete="off" />
		</div>
		<div class="site-forminfo">
            Simply just your last name.
		</div>
	</div>

    <div class="site-formheader">Contact Information</div>
	<div class="site-formrow">
		<div class="site-formlabel">Email Address</div>
		<div class="site-forminput">
			<input type="email" name="email" id="email" maxlength="150"
				autocomplete="off" />
		</div>
		<div class="site-forminfo">
			<span>This email address will receive notifications that are related to
				this user, specific to this site, and from this site.</span>
		</div>
	</div>

    <hr />

	<div class="site-formsubmitrow">
		<input type="submit" class="site-greenbtn" value="Create User and Go Next" />
	</div>
</form>
