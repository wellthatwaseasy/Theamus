<?php

$post = filter_input_array(INPUT_POST); // Define filtered 'post'

// User's ID
if (isset($post['id'])) {           // If it is set
    if (is_numeric($post['id'])) {  // If it is a number
        $id = urldecode($post['id']);
    } else {
        $error[] = "Invalid ID.";
    }
} else {
    $error[] = "There was an issue finding the user's ID.";
}

// User's password
$password = false;
if ($post['change_pass'] == "true") {                   // Check for password change
    if ($post['password'] != "") {                      // Check for input
        $password = urldecode($post['password']);
        if ($post['repeat_password'] != "") {           // Check for input
            $repeat_pass = urldecode($post['repeat_password']);
            if ($password == $repeat_pass) {            // Check for match
                if (strlen($password) >= 4) {  // Check for length
                    $salt = $tData->get_config_salt("password");
                    $password = hash('SHA256', $password.$salt);
                } else {
                    $error[] = "The password must be at least 4 characters.";
                }
            } else {
                $error[] = "The passwords you've entered don't match.";
            }
        } else {
            $error[] = "Please fill out the 'Repeat Password' field.";
        }
    } else {
        $error[] = "Please fill out the 'New Password' field.";
    }
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
        $birthday = $birthday;
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

// Get active status
$active = $post['active'] == "true" ? "1" : "0";

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    // Define query data
    $query_data = array(
        "table_name"    => $tData->prefix."_users",
        "data"          => array(
            "firstname" => $firstname,
            "lastname"  => $lastname,
            "email"     => $email,
            "phone"     => $phone,
            "gender"    => $gender,
            "birthday"  => $birthday,
            "groups"    => $groups,
            "admin"     => $admin,
            "active"    => $active,
        ),
        "clause"        => array(
            "operator"  => "",
            "conditions"=> array("id" => $id)
        )
    );

    // Define password sql
    if ($password != false) {
        $query_data['data']['password'] = $password;
    }

    // Query the database, update the user's information
    $query = $tData->update_table_row($query_data['table_name'], $query_data['data'], $query_data['clause']);

    if ($query != false) {
        notify("admin", "success", "This information has been saved.");
    } else {
        notify("admin", "failure", "There was an error saving this information.");
    }
}