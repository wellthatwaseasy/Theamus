<?php

$query_data = array("table_name" => $tData->prefix."_users", "data" => array(), "clause" => array());

// Get the logged in user's info
$user = $tUser->user;

// Define an empty error array
$error = array();

if ($user != false) {
    // Check first name
    if ($_POST['firstname'] != "") {
        $firstname = $_POST['firstname'];
        if (preg_match("/[^a-zA-Z0-9-\']/", $firstname)) {
            $error[] = "First names usually don't have all those fancy characters.";
        }
    } else {
        $error[] = "Please fill out the first name field.";
    }

    // Check last name
    if ($_POST['lastname'] != "") {
        $lastname = $_POST['lastname'];
        if (preg_match("/[^a-zA-Z0-9-\']/", $lastname)) {
            $error[] = "Last names usually don't have all those fancy characters.";
        }
    } else {
        $error[] = "Please fill out the last name field.";
    }

    // Get gender
    if ($_POST['gender'] == "m" || $_POST['gender'] == "f") {
        $gender = $_POST['gender'];
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
        $query_data['data'] = array(
            "firstname" => $firstname,
            "lastname"  => $lastname,
            "gender"    => $gender,
            "birthday"  => $birthday
        );
        $query_data['clause'] = array("operator" => "", "conditions" => array("id" => $user['id']));

        // Update the database
        if ($tData->update_table_row($query_data['table_name'], $query_data['data'], $query_data['clause'])) {
            run_after_ajax("update_name", '{"firstname":"'.$firstname.'","lastname":"'.$lastname.'"}');
            alert_notify("success", "Your personal information has been saved.");
        } else {
            alert_notify("danger", "There was an error saving this information.");
        }
    }
}