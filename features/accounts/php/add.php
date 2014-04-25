<?php

$post = filter_input_array(INPUT_POST); // Define filtered 'post'

$query_data = array("table_name" => $tData->prefix."_users", "data" => array());

// Username
if ($post['username'] != "") {
    $username = urldecode($post['username']);
    if (!preg_match("/[^a-zA-Z0-9_-]/", $username)) {
        if (strlen($username) >= 4 && strlen($username) <= 25) {
            $query_username = $tData->select_from_table($query_data['table_name'], array("username"), array("operator" => "", "conditions" => array("username" => $username)));

            if ($query_username != false) {
                if ($tData->count_rows($query_username) > 0) {
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
    $password = urldecode($post['password']);
    if ($post['repeat_password'] != "") {           // Check for input
        $repeat_pass = urldecode($post['repeat_password']);
        if ($password == $repeat_pass) {            // Check for match
            if (strlen($password) >= 4 && strlen($password) <= 30) {  // Check for length
                $salt = $tData->get_config_salt("password");
                $password = hash('SHA256', $password.$salt);
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
    $firstname = urldecode($post['firstname']);
} else {
    $error[] = "Please fill out the 'First Name' field.";
}
if ($post['lastname'] != "") {
    $lastname = urldecode($post['lastname']);
} else {
    $error[] = "Please fill out the 'Last Name' field.";
}

// User's email
if ($post['email'] != "") {
    $email = urldecode($post['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Please enter a valid email.";
    }
} else {
    $error[] = "Please fill out the 'Email Address' field.";
}

// Check the phone number
if ($post['phone'] != "") {
    $phone = urldecode($post['phone']);
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

// Get gender
if ($post['gender'] == "m" || $post['gender'] == "f") {
    $gender = $post['gender'];
} else {
    $error[] = "I don't know that gender.  Try 'Male' or 'Female'";
}

// Get birthday
if ($post['bday-m'] != "" && $post['bday-d'] != "" && $post['bday-y'] != "") {
    $month = $post['bday-m'];
    $day = $post['bday-d'];
    $year = $post['bday-y'];
    if (is_numeric($month) && is_numeric($day) && is_numeric($year)) {
        $birthday = $year."-".$month."-".$day;
    } else {
        $error[] = "Please provide the numerical values of your birthday.";
    }
} else {
    $error[] = "Please fill out all of the birthday fields.";
}

// Get groups
if ($post['groups'] != "") {
    $groups = urldecode($post['groups']);
} else {
    $groups = "everyone";
}

// Get admin status
$admin = $post['is_admin'] == "true" ? "1" : "0";

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    $query_data['data'] = array(
        "username"  => $username,
        "password"  => $password,
        "email"     => $email,
        "firstname" => $firstname,
        "lastname"  => $lastname,
        "birthday"  => $birthday,
        "gender"    => $gender,
        "admin"     => $admin,
        "groups"    => $groups,
        "permanent" => 0,
        "phone"     => $phone,
        "picture"   => "default-user-picture.png",
        "created"   => date('Y-m-d H:i:s'),
        "active"    => 1
    );

    $query = $tData->insert_table_row($query_data['table_name'], $query_data['data']);

    if ($query_data != false) {
        notify("admin", "success", "This information has been saved.<br />".js_countdown());
        run_after_ajax("back_to_userlist");
    } else {
        notify("admin", "failure", "There was an error saving this information.");
    }
}