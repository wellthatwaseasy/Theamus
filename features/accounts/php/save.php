<?php

$post = filter_input_array(INPUT_POST); // Define filtered 'post'

// User's ID
if (isset($post['id'])) {           // If it is set
    if (is_numeric($post['id'])) {  // If it is a number
        $id = $tData->real_escape_string($post['id']);
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
        $error[] = "Please fill out the 'New Password' field.";
    }
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
        $phone = $tData->real_escape_string($numbers);
    }
} else {
    $phone = "";
}

// Get gender
if ($post['gender'] == "m" || $post['gender'] == "f") {
    $gender = $tData->real_escape_string($post['gender']);
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
        $birthday = $tData->real_escape_string($birthday);
    } else {
        $error[] = "Please provide the numerical values of your birthday.";
    }
} else {
    $error[] = "Please fill out all of the birthday fields.";
}

// Get groups
if ($post['groups'] != "") {
    $groups = $tData->real_escape_string(urldecode($post['groups']));
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
    // Define password sql
    if ($password != false) {
        $sql['pass'] = ", `password`='".$password."'";
    } else {
        $sql['pass'] = "";
    }

    $users_table = $tDataClass->prefix."_users";

    // Define the update sql
    $sql['update'] = "UPDATE `".$users_table."` SET "
            . "`firstname`='".$firstname."', "
            . "`lastname`='".$lastname."', "
            . "`email`='".$email."', "
            . "`phone`='".$phone."', "
            . "`gender`='".$gender."', "
            . "`birthday`='".$birthday."', "
            . "`groups`='".$groups."', "
            . "`admin`='".$admin."', "
            . "`active`='".$active."'"
            . $sql['pass']
            . " WHERE `id`='".$id."'";
    $qry['update'] = $tData->query($sql['update']);

    if ($qry['update']) {
        notify("admin", "success", "This information has been saved.");
    } else {
        notify("admin", "failure", "There was an error saving this information.");
    }
}