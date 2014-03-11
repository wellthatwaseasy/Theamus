<?php

$settings_table = $tDataClass->prefix."_settings";
$post = filter_input_array(INPUT_POST); // Clean the input information
$error = array(); // Error checking array

// Site Name
if ($post['name'] != "") {
    $name = $tData->real_escape_string(urldecode($post['name']));
} else {
    $error[] = "Please fill out the 'Site Name' field.";
}

// Configure Email
$email = filter_var($_POST['config-email'], FILTER_VALIDATE_BOOLEAN);
if ($email == true) {
    // Email Host
    if ($post['host'] != "") {
        $host = $tData->real_escape_string(urldecode($post['host']));
    } else {
        $error[] = "Please fill out the 'Host' field.";
    }

    // Protocol
    $protocol = $tData->real_escape_string(urldecode($post['protocol']));

    // Port
    if ($post['port'] != "") {
        $port = $tData->real_escape_string(urldecode($post['port']));
        if (!is_numeric($port)) $error[] = "The port must be a number.";
    } else {
        $error[] = "Please fill out the 'Port' field.";
    }


    // Email
    if ($post['email'] != "") {
        $email = urldecode($post['email']);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = $tData->real_escape_string($email);
        } else {
            $error[] = "Please enter a valid email address.";
        }
    } else {
        $error[] = "Please fill out the 'Email' field.";
    }

    // Password
    if ($post['password'] != "") {
        $password = $tData->real_escape_string(urldecode($_POST['password']));
    } else {
        $error[] = "Please fill out the 'Password' field.";
    }
}

// Display errors
$show_errors = $post['errors'] == "true" ? 1 : 0;

// Show errors
if (!empty($error)) {
	notify("install", "failure", $error[0]);
    run_after_ajax("undisable_form");
} else {
    // Define the different queries and run them
    if ($email == true) {
        $sql['update'] = "UPDATE `$settings_table` SET ".
                "`name`='$name', `display_errors`='$show_errors', `email_host`='$host', ".
                "`email_protocol`='$protocol', `email_port`='$port', `email_user`='$email', ".
                "`email_password`='$password' WHERE `id`='1'";
    } else {
        $sql['update'] = "UPDATE `$settings_table` SET ".
                "`name`='$name', `display_errors`='$show_errors' WHERE `id`='1'";
    }
    $qry['update'] = $tData->query($sql['update']);

    // Check the query, notify the user and get out
    if ($qry['update']) {
        notify("install", "success", "These settings have been saved.<br />".js_countdown());
        run_after_ajax("go_step", '{"step":"done"}');
    } else {
        notify("install", "failure", "There was an issue saving this information.");
        run_after_ajax("undisable_form");
    }
}