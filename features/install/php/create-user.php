<?php

$post = filter_input_array(INPUT_POST); // Clean the incoming information
$users_table = $tDataClass->prefix."_users"; // Define the users table
$error = array(); // Error checking array

$reset = isset($post['reset']) ? $post['reset'] : "false";
if ($reset == "true") $Installer->reset_users();

// Username
if ($post['username'] != "") {
    $username = $post['username'];
    if (!preg_match("/[^a-zA-Z0-9]/", $username)) {
        if (strlen($username) >= 4 && strlen($username) <= 25) {
            $username = $tData->real_escape_string($username);
            $sql['user'] = "SELECT * FROM `".$users_table."` WHERE `username`='".$username."'";
            $qry['user'] = $tData->query($sql['user']);

            if ($qry['user']) {
                if ($qry['user']->num_rows > 0) {
                    $error[] = "This username has already been taken, try another.";
                }
            } else {
                $error[] = "There was an error querying the database for usernames.";
            }
        } else {
            $error[] = "The username must be between 4 and 25 characters.";
        }
    } else {
        $error[] = "The username can only be alphanumeric.";
    }
} else {
    $error[] = "Please fill out the 'Username' field.";
}

// User's password
if ($post['password'] != "") {                      // Check for input
    $password = $post['password'];
    if ($post['repeatPass'] != "") {           // Check for input
        $repeat_pass = $post['repeatPass'];
        if ($password == $repeat_pass) {            // Check for match
            if (strlen($password) >= 4 && strlen($password) <= 30) {  // Check for length
                $salt = $tDataClass->get_config_salt("password");
                $password = $tData->real_escape_string(hash('SHA256', $password.$salt));
            } else {
                $error[] = "The password must be between 4 and 30 characters.";
            }
        } else {
            $error[] = "The passwords you've entered don't match.";
        }
    } else {
        $error[] = "Please fill out the 'Repeat Password' field.";
    }
} else {
    $error[] = "Please fill out the 'Password' field.";
}

// User's name
if ($post['firstname'] != "") {
    $firstname = $tData->real_escape_string($post['firstname']);
} else {
    $error[] = "Please fill out the 'First Name' field.";
}
if ($post['lastname'] != "") {
    $lastname = $tData->real_escape_string($post['lastname']);
} else {
    $error[] = "Please fill out the 'Last Name' field.";
}

// User's email
if ($post['email'] != "") {
    $email = urldecode($post['email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = $tData->real_escape_string($email);
    } else {
        $error[] = "Please enter a valid email.";
    }
} else {
    $error[] = "Please fill out the 'Email Address' field.";
}


// Show errors
if (!empty($error)) {
    notify("install", "failure", $error[0]);
    run_after_ajax("undisable_form");
} else {
    if (!$Installer->check_admin_user()) {
        // Create the user in the database
        $sql['create'] = "INSERT INTO `$users_table` ".
        "(`username`, `password`, `email`, `firstname`, `lastname`, `admin`, ".
        "`groups`, `permanent`, `created`, `active`) VALUES ".
        "('$username', '$password', '$email', '$firstname', '$lastname', 1, 'everyone,administrators', ".
        "1, now(), 1)";
        $qry['create'] = $tData->query($sql['create']);

        // Check the query, notify the user and get out
        if ($qry['create']) {
            notify("install", "success", "The administrator account has been created.<br />".js_countdown());
            run_after_ajax("go_step", '{"step":"custom"}');
        } else {
            notify("install", "failure", "There was an error creating the administrator account.");
            run_after_ajax("undisable_form");
        }
    } else {
        notify("install", "success", "Skipping this step.<br/>".js_countdown());
    }
}