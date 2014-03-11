<?php $h = $Settings->get_home_info(); ?>

<div class="admin_content-header">
    <span class="admin_content-header-img"><img id="admin_content-header-img" src="features/settings/img/edit-settings.png" alt="" /></span>
	<div class="admin_content-header-text">Site Customization</div>
</div>

<div class="admin_page-content">
	<div id="custom-result"></div>
    <div id="values-wrapper">
        <input type="hidden" id="setting-session" value="<?php if ($h['type'] == "session") echo 'true'; ?>" />
        <input type="hidden" id="out" value='<?=$Settings->get_session_value($h, "before")?>' />
        <input type="hidden" id="in" value='<?=$Settings->get_session_value($h, "after")?>' />
        <input type="hidden" id="type" value="<?=$h["type"]?>" />
    </div>

	<form class="admin-form" id="custom-form">
		<div class="admin-formrow">
			<div class="admin-formlabel">Site Name</div>
			<div class="admin-forminput">
				<input type="text" class="longtext" name="name" value="<?=$Settings->get_site_name()?>" maxlength="100" autocomplete="off" />
			</div>
            <div class="admin-forminfo">
                <span>This is what shows up on the header of your page, as well as the title of your site.</span>
            </div>
		</div>

        <div class='admin-formheader'>Home Page</div>
		<div>
            <div id='session-notify' class='admin-fade'>
                <div class='admin-notifywarn'>
                      You are now setting user's logged <span id='setsesstype'></span> page.
                    <div style='float:right;margin-top:-8px;'>
                        <input type='button' class='admin-redbtn inline' value='Cancel' id="cancel-sessions" />
                        <input type='button' value='Save' class="inline" id='sessSave' />
                    </div>
                </div>
            </div>
            <?php $class = $h['type'] == "session" ? "admin-faded" : ""; ?>
            <div id='sessionsAreSet' class='admin-fade <?=$class?>'>
                <div class='admin-notifyinfo'>
                    You have set your home page to respond via sessions.
                    <div style='float:right;margin-top:-8px;'>
                        <input type='button' class="inline admin-redbtn" value='Clear' name="reset-sessions" />
                    </div>
                </div>
            </div>

    		<div class="admin-formcolumn" style="width:150px;">
    		    <ul class="admin-columnlist">
    		        <li><a href="#" name="type" data-for="page">Page</a></li>
                    <li><a href="#" name="type" data-for="feature">Feature</a></li>
                    <li><a href="#" name="type" data-for="custom">Custom URL</a></li>
    		        <li><a href="#" name="type" data-for="require-login">Require Login</a></li>
    		        <li><a href="#" name="type" data-for="session">Session Views</a></li>
    		    </ul>
    		</div>

    		<div class="admin-formcolumn admin-fade" id="login-notify">
    		    <input type="hidden" id="required-login" value="" />
    		    <div class="admin-notifyinfo">A user will be prompted to login when they first visit this site.
    		        <a href="#" name="type" data-for="require-login">Don't want this?</a>
    		    </div>
    		</div>

    		<!-- Pages -->
            <?php $display = $h['type'] == "page" ? "display:block;" : "display:none;"; ?>
    		<div class="admin-formcolumn" id="page-wrapper" style="width:auto; <?=$display?>">
    		    <div class="admin-formrow">
    		        <div class="admin-formlabel">Home Page</div>
    		        <div class="admin-forminput"><?=$Settings->get_pages_select($h)?></div>
    		        <hr />
    		        <div class="afi-col-nopad">
    		            <p>Choosing this option will direct your users to a static page that you've created with the Pages feature within the Theamus system.</p>
    		            <p>If you're looking to have a separate view for users that are logged in and logged out, check out the Session Views tab.</p>
    		        </div>
    		    </div>
    		</div>

    		<!-- Features -->
            <?php $display = $h['type'] == "feature" ? "display:block;" : "display:none;"; ?>
    		<div class="admin-formcolumn" id="feature-wrapper" style="width:auto;<?=$display?>">
    		    <div class="admin-formrow">
                    <div class="admin-formlabel">Feature</div>
                    <div class="admin-forminput"><?=$Settings->get_features_select($h)?></div>
                </div>
                <div class="admin-formrow">
                    <div class="admin-formlabel">Feature File</div>
                    <div class="admin-forminput" id="feature-file-list"></div>
    		    </div>
                <hr />
                <div class="afi-col-nopad">
                    <p>If you really want to go to a feature, you just have to select it from the top selection box. That will take you to the index page by default. If you want or need to go to a specific page in the feature, just select a different selection.</p>
                </div>
    		</div>

    		<!-- Custom URL -->
            <?php
            $display = $h['type'] == "custom" ? "display:block;" : "display:none;";
            $h['url'] = array_key_exists("url", $h) ? $h['url'] : "";
            ?>
            <div class="admin-formcolumn" id="no-custom" style="display:none; width:auto;">
                <div class="afi-col-nopad">
                    You can't require a login to a custom url, that's just silly.
                    If you want to go to a custom url, you need to turn off the
                    required login. To do that,
                    <a href="#" name="type" data-for="require-login">click here</a>.
                </div>
            </div>
    		<div class="admin-formcolumn" id="custom-wrapper" style="width:auto; <?=$display?>">
    		    <div class="admin-formrow">
    		        <div class="admin-formlabel">Custom URL</div>
    		        <div class="admin-forminput">
    		            <input type="text" class="longtext" name="custom-url" maxlength="100" autocomplete="off" value="<?=$h['url']?>" />
    		        </div>
    		        <hr />
    		        <div class="afi-col-nopad">
    		            <p>The Custom URL that you're inputting here is to a specific page within your site. It <b>cannot</b> go to an external site.</p>
    		            <p>For example, you have a blog and you want to link to a specific post. Your URL would look like: http://www.theamus.com/blog/posts/this-is-a-post</p>
    		            <p>All you need to input is: blog/posts/this-is-a-post</p>
    		            <p>Everything else, like the base of the path, is assumed.</p>
    		        </div>
    		    </div>
    		</div>

    		<!-- Require Login -->
            <?php
            $display = $h['type'] == "require-login" ? "display:block;" : "display:none;";
            $check = $h['type'] == "require-login" ? "checked" : "";
            ?>
            <div class="admin-formcolumn" id="nologin" style="display:none; width:auto;">
                <div class="afi-col-nopad">
                    You're setting your session home pages.  If you want to require a login, then you need to cancel the current process and continue from there.
                </div>
            </div>
    		<div class="admin-formcolumn" id="require-login-wrapper" style="width:auto; <?=$display?>">
    		    <div class="admin-formrow">
    		        <div class="admin-formlabel afl-float">Require Login</div>
    		        <div class="admin-forminput">
                        <div class="admin-cboxwrapper">
                            <input type="checkbox" class="admin-switchcbox" name="login" id="reqlogin" <?=$check?> />
                            <label class="admin-switchlabel yn" for="reqlogin"><span class="admin-switchinner"></span><span class="admin-switchswitch"></span></label>
                        </div>
    		        </div>
    		        <hr />
    		        <div class="afi-col-nopad">
    		            <p>Requiring a login will prompt a user for their credentials. This is only for the home page, though. User's will still be able to view things that have no permissions set on them or are specifically made to be viewable by anyone.</p>
    		            <p><b>*NOTE:</b> one you select this, just go choose the view what you want to show up. What you're looking at is what will be saved. If you're not looking at anything, it will default to the default home page.</p>
    		        </div>
    		    </div>
    		</div>

    		<!-- Session Control -->
            <?php $display = $h['type'] == "session" ? "display:block;" : "display:none;"; ?>
            <div class='admin-formcolumn' id='unsetsession' style='width:auto; <?=$display?>'>
                <div class='afi-col-nopad'>
                    You've already set your home page up to work with sessions,
                    so you can't do this. If you want to be able to do this,
                    <a href='#' name="reset-sessions">click here</a>.
                </div>
            </div>
    		<div class='admin-formcolumn' id='no-session' style='display:none;width:auto;'>
    		    <div class='afi-col-nopad'>
    		        You are already requiring a login, all you have to do now is choose a page or feature that users will go to once they've logged in!
    		        <a href="#" name="type" data-for="require-login">Turn of the required login here</a>.
    		    </div>
    		</div>
    		<div class='admin-formcolumn' id='session-wrapper' style='width:auto;display:none;'>
                <div class='admin-formrow'>
                    <div class='admin-formlabel'>Session Pages</div>
                    <div class='admin-forminput'>
                        <input type='button' value='Set them Now' id="set-sessions">
                    </div>
                </div>
                <hr />
                <div class='afi-col-nopad'>
                    <p>Setting the session views works exactly how you think it would. When you go to click on 'Set them Now' the website will capture the pages that you set. The logged in page first, then the logged out page.</p>
                </div>
    		</div>
    		<!-- End Session Control -->
    		<div class='clearfix'></div>
		</div>

		<hr />

		<div class="admin-formsubmitrow">
			<input type="submit" value="Save Information" class="admin-greenbtn" />
		</div>
	</form>
</div>