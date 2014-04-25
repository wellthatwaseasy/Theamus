<?php

$query_data = array("table_name" => $tData->prefix."_users", "data" => array(), "clause" => array());

// Define the current user's information
$user = $tUser->user;

// Define an empty error array
$error = array();

if ($user != false) {
    // Check email
    if ($_POST['email'] != "") {
        $email = urldecode($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
            $phone = $numbers;
        }
    } else {
        $phone = "";
    }

    // Show errors
    if (!empty($error)) {
        alert_notify("danger", $error[0]);
    } else {
        // Update the database
        $query_data['data'] = array("email" => $email, "phone"  => $phone);
        $query_data['clause'] = array("operator" => "", "conditions" => array("id" => $user['id']));
        if ($tData->update_table_row($query_data['table_name'], $query_data['data'], $query_data['clause'])) {
            alert_notify("success", "Your contact information has been saved.");
        } else {
            alert_notify("danger", "There was an error saving this information.");
        }
    }
}