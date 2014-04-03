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
<form class="form" id="user-form" onsubmit="return save_contact();">
    <h2 class="form-header">Contact Information</h2>
    <div class="form-group">
        <label class="control-label" for="email">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
        <p class="help-block">Looks something like: "roadrunner@acme.org"</p>
    </div>

    <div class="form-group">
        <label class="control-label" for="phone">Phone Number</label>
        <input type="text" class="form-control" id="phone" name="phone" maxlength="17" value="<?php echo format_phone($user['phone']); ?>">
        <p class="help-block">Can be your cell, work, home, fax, or anything else you can think of.<p>
    </div>

    <hr class="form-split">

    <div class="site-formsubmitrow">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>