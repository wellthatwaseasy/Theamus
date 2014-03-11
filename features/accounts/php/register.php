<?php

$post = filter_input_array(INPUT_POST); // Define filtered 'post'

$users_table = $tDataClass->prefix."_users";

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
    if ($post['repeat_password'] != "") {           // Check for input
        $repeat_pass = $post['repeat_password'];
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

// Check the phone number
$phone = "";

// Get gender
$gender = "m";

// Get birthday
$birthday = "0000-00-00";

// Get groups
$groups = "everyone";

// Get admin status
$admin = "0";

// Define the activation code
$activation_code = md5(time());

// Show errors
if (!empty($error)) {
    notify("site", "failure", $error[0]);
} else {
    // Define password sql
    if ($password != false) {
        $sql['pass'] = ", `password`='".$password."'";
    } else {
        $sql['pass'] = "";
    }

    // Define the update sql
    $sql['update'] = "INSERT INTO `".$users_table."`"
            . "(`username`, `password`, `email`, `firstname`, `lastname`, `birthday`, "
            . "`gender`, `admin`, `groups`, `permanent`, `phone`, `picture`, `created`, `active`, `activation_code`)"
            . "VALUES"
            . "('".$username."', '".$password."', '".$email."', '".$firstname."', "
            . "'".$lastname."', '".$birthday."', '".$gender."', '".$admin."', '".$groups."', 0, "
            . "'".$phone."', `default-user-picture.png`, now(), '0', '$activation_code')";
    $qry['update'] = $tData->query($sql['update']);

    if ($qry['update']) {
        // Get the site settings, for personalization
        $qry['settings'] = $tData->query("SELECT * FROM `".$tDataClass->prefix."_settings`");
        $settings = $qry['settings']->fetch_assoc();

        // Create the email message
        $activation_addy = $this->base_url."accounts/activate/&email=".$post['email']."&code=$activation_code";
        $message = "You've recently registered to ".$settings['name']."!<br /><br />";
        $message .= "Now all you have to do is activate your account before you"
            . "can log in.<br />";
        $message .= "To activate your new account, <a href='$activation_addy'>click here</a>!";

        // Send the mail
        if (tMail($email, "Activate Your Account", $message)) {
            notify("site", "success", "Congratulations! Your account has been created!<br />".
                "Any second now you should recieve an activation email.<br /><br />".
                "Once you've activated, you can <a href='accounts/login'>click here to login.</a>");
        } else {
            notify("site", "failure", "There was an error while creating your account.");
        }
    } else {
        notify("site", "failure", "There was an error while creating your account.");
    }
}