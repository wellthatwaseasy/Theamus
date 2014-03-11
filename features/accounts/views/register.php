<div id="register-result"></div>
<form class="site-form" id="register-form" onsubmit="return register();">
    <div class="site-formheader">Login Information</div>
    <div class="site-formrow">
        <div class="site-formlabel">Username</div>
        <div class="site-forminput">
            <input type="text" name="username" />
        </div>
    </div>

    <div class="site-formrow">
        <div class="site-formlabel">Password</div>
        <div class="site-forminput">
            <input type="password" name="password" />
        </div>
    </div>

    <div class="site-formrow">
        <div class="site-formlabel">Repeat Password</div>
        <div class="site-forminput">
            <input type="password" name="repeat_password" />
        </div>
    </div>

    <div class="site-formheader">Contact Information</div>
    <div class="site-formrow">
        <div class="site-formlabel">Email Address</div>
        <div class="site-forminput">
            <input type="text" name="email" />
        </div>
    </div>

    <div class="site-formheader">Personal Information</div>
    <div class="site-formrow">
        <div class="site-formlabel">First Name</div>
        <div class="site-forminput">
            <input type="text" name="firstname" />
        </div>
    </div>

    <div class="site-formrow">
        <div class="site-formlabel">Last Name</div>
        <div class="site-forminput">
            <input type="text" name="lastname" />
        </div>
    </div>

    <hr />

    <div class="site-formsubmitrow">
        <input type="submit" value="Register" class="site-greenbtn" />
        or <a href="accounts/login/">Login</a>
    </div>
</form>