<div id="login-result"></div>
<form class="site-form" id="login-form" onsubmit="return Login();">
	<div class="site-formrow">
		<div class="site-formlabel">Username</div>
		<div class="site-forminput">
			<input type="text" id="username" name="username" maxlength="25"
				autocomplete="off" autocapitalize="off" autocorrect="off"
				spellcheck="off" autofocus="autofocus" />
		</div>
	</div>

	<div class="site-formrow" row="password">
		<div class="site-formlabel">Password</div>
		<div class="site-forminput">
			<input type="password" id="password" name="password" maxlength="50" />
		</div>
	</div>

    <div class="site-formrow sli">
        <div class="site-formlabel sfl-float">Stay logged in</div>
        <div class="site-forminput">
            <div class="site-cboxwrapper">
                <input type="checkbox" class="site-switchcbox" name="keep_session"
                  id="ks" checked>
                <label class="site-switchlabel yn" for="ks">
                  <span class="site-switchinner"></span>
                  <span class="site-switchswitch"></span>
                </label>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

	<div class="site-formsubmitrow">
		<input type="submit" value="Login" />
	</div>
</form>
