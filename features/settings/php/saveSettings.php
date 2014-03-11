<?php

// Error checking
$error = array();

// Config email
$configEmail = filter_var($_POST['config-email'], FILTER_VALIDATE_BOOLEAN);
if ($configEmail == true)
{
// Host
       $_POST['host'] != "" ? $host = urldecode($_POST['host'])
              : $error[] = "Please fill out the 'Host' field.";
 
       // Protocol
       $protocol = $_POST['protocol'];
 
       // Port
       $_POST['port'] != "" ? $port = $_POST['port']
              : $error[] = "Please fill out the 'Port' field.";
       if (!is_numeric(@$port)) $error[] = "The port must be a number.";
 
       // Email
       $_POST['email'] != "" ? $email = urldecode($_POST['email'])
              : $error[] = "Please fill out the 'Email' field.";
       if (!filter_var(@$email, FILTER_VALIDATE_EMAIL)) {
              $error[] = "Please enter a valid email address.";
       }
 
       // Password
       $_POST['password'] != "" ? $password = urldecode($_POST['password'])
              : $error[] = "Please fill out the 'Password' field.";
}

// Display errors
$displayErrors = filter_var($_POST['errors'], FILTER_VALIDATE_BOOLEAN);

// Show errors
if (!empty($error)) {
	notify("admin", "failure", $error[0]);
} else {
	// Define the table
	$table = $tDataClass->prefix."_settings";

	// Sanitize the variables
	$displayErrors = $displayErrors == true ? "1" : "0";
	if ($configEmail == true) {
		$host = $tData->real_escape_string($host);
		$protocol = $tData->real_escape_string($protocol);
		$port = $tData->real_escape_string($port);
		$email = $tData->real_escape_string($email);
		$password = $tData->real_escape_string($password);

		$sql = "UPDATE `".$table."` SET
			`display_errors`='".$displayErrors."', `email_host`='".$host."',
			`email_protocol`='".$protocol."', `email_port`='".$port."',
			`email_user`='".$email."', `email_password`='".$password."'
			WHERE `id`='1'";
	} else {
		$sql = "UPDATE `".$table."` SET
			`display_errors`='".$displayErrors."' WHERE `id`='1'";
	}

	// Run query
	$tData->query($sql);

	notify("admin", "success", "These settings have been saved!");
}


?>