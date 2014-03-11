<?php

$get = filter_input_array(INPUT_GET); // Clean the get request
$users_table = $tDataClass->prefix."_users"; // Define the users table
$error = array(); // Empty error array

// User's email address
$email = "";
if (isset($get['email'])) {
    $email = urldecode($get['email']);

    if ($email != "") {
        $email = $tData->real_escape_string($email);
    } else {
        $error[] = "Unknown email address.";
    }
} else {
    $error[] = "There's something missing for this activation to work.";
}

// User's activation code
$code = "";
if (isset($get['code'])) {
    $code = urldecode($get['code']);

    if ($code != "") {
        $code = $tData->real_escape_string($code);
    } else {
        $error[] = "Unknown activation code.";
    }
} else {
    $error[] = "There's something missing for this activation to work.";
}

// Query the database for this user
$sql['find'] = "SELECT * FROM `$users_table` WHERE `email`='$email' && `activation_code`='$code'"
    . " && `active`='0'";
$qry['find'] = $tData->query($sql['find']);
if ($qry['find'] && $qry['find']->num_rows == 0) {
    $error[] = "There was an issue finding this user in the database.";
}

// Show errors
if (!empty($error)) {
    notify("site", "failure", $error[0]);
} else {
    // Update the database to make the user active
    $sql['update'] = "UPDATE `$users_table` SET `active`='1' WHERE `email`='$email' "
        . "&& `activation_code`='$code'";
    $qry['update'] = $tData->query($sql['update']);

    // Notify the user
    if ($qry['update']) {
        notify("site", "success", "Your account has been activated! - "
            . "<a href='accounts/login/'>You can login here</a>");
    } else {
        notify("site", "failure", "There was an issue activating your account.");
    }
}