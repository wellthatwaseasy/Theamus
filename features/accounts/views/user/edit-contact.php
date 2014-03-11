<?php
$user = $tUser->user;

function format_phone($number) {
    if ($number != "") {
        $phone = "1 ";
        $phone .= "(".substr($number, 0, 3).")";
        $phone .= " ".substr($number, 3, 3);
        $phone .= "-".substr($number, 6, 10);
    } else {
        $phone = "";
    }

    return $phone;
}

?>
<div id="user-result"></div>
<form class="site-form" id="user-form" onsubmit="return save_contact();">
    <div class="site-formheader">
        Contact Information
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Email Address</div>
        <div class="site-forminput">
            <input type="text" name="email" maxlength="100" value="<?=$user['email']?>" />
        </div>
        <div class="site-forminfo">
            Looks something like: "roadrunner@acme.org"
        </div>
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Phone Number</div>
        <div class="site-forminput">
            <input type="text" name="phone" maxlength="17" value="<?=format_phone($user['phone'])?>" />
        </div>
        <div class="site-forminfo">
            Can be your cell, work, home, fax, or anything else you can think of.
        </div>
    </div>

    <hr />

    <div class="site-formsubmitrow">
        <input type="submit" value="Save" class="site-greenbtn" />
        <input type="button" value="Cancel" class="site-redbtn" />
    </div>
</form>