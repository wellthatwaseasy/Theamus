<?php
$user = $tUser->user;
?>
<div id="user-result"></div>
<form class="form" id="user-form" onsubmit="return save_account();">
    <h2 class="form-header">Login Information</h2>
    <div class="form-group">
        <label class="control-label">Username</label>
        <div class="form-control-static"><?php echo $user['username']; ?></div>
        <p class="help-block">This is the username you log in with, it's unique to you and cannot be changed.</p>
    </div>


    <div class="form-group">
        <label class="control-label checkbox">
            Change Password
            <input type="checkbox" name="change_pass" id="changePass" onchange="toggle_pass();">
        </label>
    </div>

    <div id="passwords" style="display:none;">
        <hr class="form-split">

        <div class="form-group">
            <label class="control-label" for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control">
            <p class="help-block">What would you like your new password to be?<br>I bet it's something good.</p>
        </div>
        <div class="form-group">
            <label class="control-label" for="repeat-password">Repeat Password</label>
            <input type="password" id="repeat-password" name="repeat_password" class="form-control">
            <p class="help-block">This should match the password above.</p>
        </div>

        <hr class="form-split">
    </div>

    <h2 class="form-header">Profile Picture</h2>
    <div class="form-group">
        <label class="control-label">Current Picture</label>
        <div class="form-control-static">
            <?php if ($user['picture'] == ""): ?>
            <img id="current-pic" src="media/profiles/default-user-picture.png" alt="" height="150">
            <?php else: ?>
            <img id="current-pic" src="media/profiles/<?=$user['picture']?>" alt="" height="150">
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="picture">Change Picture</label>
        <input type="file" class="form-control" id="picture" name="picture" />
    </div>

    <hr class="form-split">

    <div class="site-formsubmitrow">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>