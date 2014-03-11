<?php

// Define the salts
$session_salt = $tDataClass->get_config_salt("session");

// Error checking
$error = array();

// Username
$_POST['username'] != "" ? $username = $_POST['username'] : $error[] = "Please fill out the 'Username' field.";

// Password
$_POST['password'] != "" ? $password = $_POST['password'] : $error[] = "Please fill out the 'Password' field.";
if (!isset($password))
    $password = "";

// SQL friendly variables
$username = $tData -> real_escape_string(@$username);
$password_salt = $tDataClass->get_config_salt("password");
$password = hash('SHA256', $password.$password_salt);

// Query the database for existing user
$table = $tDataClass->prefix . "_users";
$sql['user'] = "SELECT * FROM `" . $table . "` WHERE `username`='" . $username
               . "'" . " && `password`='" . $password . "'";
$qry['user'] = $tData -> query($sql['user']);
if ($qry['user'] -> num_rows == 0)
    $error[] = "Invalid credentials.";

// Define query results
$results = $qry['user'] -> fetch_assoc();

// Define the passwords
$hashedPassword = $results['password'];

// Show errors
if (!empty($error)) {
    notify("site", "failure", $error[0]);
} else {
    // Session value
    $session = md5(time().$session_salt);

    $s = "UPDATE `".$table."` SET `session`='".$session."' WHERE `id`='".$results['id']."'";
    $q = $tData->query($s);

    // Cookie expiration time
    $expire = time() + 3600;
    if (isset($_POST['keep_session'])) {
        if ($_POST['keep_session'] == "true") {
            $expire = time() + (60 * 60 * 24 * 14);
        }
    }

    // Set the cookie for the user id and session id
    setcookie("userid", $results['id'], $expire, "/");
    setcookie("session", $session, $expire, "/");

    run_after_ajax("go_to", '{"loc":"base"}');
}
?>
