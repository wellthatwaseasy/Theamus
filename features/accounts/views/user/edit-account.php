<?php
$user = $tUser->user;
?>
<div id="user-result"></div>
<form class="site-form" id="user-form" onsubmit="return save_account();">
    <div class="site-formheader">
        Login Information
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Username</div>
        <div class="site-forminput">
            <input type="text" name="username" disabled value="<?=$user['username']?>" />
        </div>
        <div class="site-forminfo">
            This is the username you log in with, it's unique to you and cannot
            be changed.
        </div>
    </div>
    <div class="site-formrow">
        <div class="site-formlabel sfl-float">Change Password</div>
        <div class="site-forminput">
            <div class="site-cboxwrapper">
                <input type="checkbox" class="site-switchcbox" name="change_pass"
                  id="changePass" onchange="toggle_pass();">
                <label class="site-switchlabel yn" for="changePass">
                  <span class="site-switchinner"></span>
                  <span class="site-switchswitch"></span>
                </label>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="passwords" style="display:none;">
        <hr />
        <div class="site-formrow">
            <div class="site-formlabel">New Password</div>
            <div class="site-forminput">
                <input type="password" id="password" maxlength="30" name="password" />
            </div>
            <div class="site-forminfo">
                What would you like your new password to be?<br />
                I bet it's something good.
            </div>
        </div>
        <div class="site-formrow">
            <div class="site-formlabel">Repeat Password</div>
            <div class="site-forminput">
                <input type="password" maxlength="30" name="repeat_password" />
            </div>
            <div class="site-forminfo">
                This should match the password above.
            </div>
        </div>
        <hr />
    </div>

    <div class="site-formheader">
        Profile Picture
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Current Picture</div>
        <div class="site-formimg">
            <?php if ($user['picture'] == ""): ?>
            <img id="current-pic" src="media/profiles/default-user-picture.png" height="150px" />
            <?php else: ?>
            <img id="current-pic" src="media/profiles/<?=$user['picture']?>" height="150px" />
            <?php endif; ?>
        </div>
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Change Picture</div>
        <div class="site-forminput">
            <input type="file" id="picture" name="picture" />
        </div>
    </div>

    <hr />

    <div class="site-formsubmitrow">
        <input type="submit" value="Save" class="site-greenbtn" />
        <input type="button" value="Cancel" class="site-redbtn" />
    </div>
</form>