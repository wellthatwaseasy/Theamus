<?php

// Define the current user's information
$user = $tUser->user;

// Define an empty error array
$error = array();

if ($user != false) {
    // Check email
    if ($_POST['email'] != "") {
        $email = urldecode($_POST['email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = $tData->real_escape_string($email);
        } else {
            $error[] = "Please enter a valid email.";
        }
    } else {
        $error[] = "Please fill out the email field.";
    }

    // Check the phone number
    if ($_POST['phone'] != "") {
        $phone = urldecode($_POST['phone']);
        $numbers = preg_replace("/[^0-9]/", '', $phone);        // Get rid of anything that isn't a number
        if (strlen($numbers) >= 10 && strlen($numbers) <= 11) { // If there's a leading 1
            $numbers = preg_replace("/^1/", '',$numbers);       // Remove the leading 1
        } else {
            $error[] = "There's something wrong with your phone number, "
                . "it's the wrong length.";
        }

        if (strlen($numbers) == 10 && is_numeric($numbers)) {   // If the phone number is 10 integers
            $phone = $tData->real_escape_string($numbers);
        }
    } else {
        $phone = "";
    }

    // Show errors
    if (!empty($error)) {
        alert_notify("danger", $error[0]);
    } else {
        // Define the user table
        $users_table = $tDataClass->prefix."_users";

        // Update the database
        $sql['update'] = "UPDATE `".$users_table."` SET `email`='".$email."',"
            . " `phone`='".$phone."' WHERE `id`='".$user['id']."'";
        $qry['update'] = $tData->query($sql['update']);

        // Notify the user
        alert_notify("success", "Your contact information has been saved.");
    }
}