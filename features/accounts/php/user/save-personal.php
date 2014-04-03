<?php

// Get the logged in user's info
$user = $tUser->user;

// Define an empty error array
$error = array();

if ($user != false) {
    // Check first name
    if ($_POST['firstname'] != "") {
        $firstname = $_POST['firstname'];
        if (!preg_match("/[^a-zA-Z0-9-\']/", $firstname)) {
            $firstname = $tData->real_escape_string($firstname);
        } else {
            $error[] = "First names usually don't have all those fancy characters.";
        }
    } else {
        $error[] = "Please fill out the first name field.";
    }

    // Check last name
    if ($_POST['lastname'] != "") {
        $lastname = $_POST['lastname'];
        if (!preg_match("/[^a-zA-Z0-9-\']/", $lastname)) {
            $lastname = $tData->real_escape_string($lastname);
        } else {
            $error[] = "Last names usually don't have all those fancy characters.";
        }
    } else {
        $error[] = "Please fill out the last name field.";
    }

    // Get gender
    if ($_POST['gender'] == "m" || $_POST['gender'] == "f") {
        $gender = $tData->real_escape_string($_POST['gender']);
    } else {
        $error[] = "I don't know that gender.  Try 'Male' or 'Female'";
    }

    // Get birthday
    if ($_POST['bday-m'] != "" && $_POST['bday-d'] != "" && $_POST['bday-y'] != "") {
        $month = $_POST['bday-m'];
        $day = $_POST['bday-d'];
        $year = $_POST['bday-y'];
        if (is_numeric($month) && is_numeric($day) && is_numeric($year)) {
            $birthday = $year."-".$month."-".$day;
            $birthday = $tData->real_escape_string($birthday);
        } else {
            $error[] = "Please provide the numerical values of your birthday.";
        }
    } else {
        $error[] = "Please fill out all of the birthday fields.";
    }

    // Show the errors
    if (!empty($error)) {
        alert_notify("danger", $error[0]);
    } else {
        // Define the user's table
        $users_table = $tDataClass->prefix."_users";

        // Update the database
        $sql['update'] = "UPDATE `".$users_table."` SET `firstname`='".$firstname."',"
            . " `lastname`='".$lastname."', `gender`='".$gender."', `birthday`='".$birthday."'"
            . " WHERE `id`='".$user['id']."'";
        $qry['update'] = $tData->query($sql['update']);

        // Notify the user
        run_after_ajax("update_name", '{"firstname":"'.$firstname.'","lastname":"'.$lastname.'"}');
        alert_notify("success", "Your personal information has been saved.");
    }
}