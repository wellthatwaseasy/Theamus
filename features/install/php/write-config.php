<?php

$post = filter_input_array(INPUT_POST); // Clean the input array
$error = array(); // Error Checking

// Check for existing file
if (file_exists(path(ROOT."/config.php"))) {
	$error[] = "You've already set up your configuration file!<br />".
		"<a href='#' id='continue'>Continue Anyways</a> or ".
		"<a href='#' id='reset-config'>Reset</a><br />".js_countdown();
}

// Database host
if ($post['host'] != "") {
    $host = urldecode($post['host']);
} else {
    $error[] = "Please fill out the 'Database Host' field.";
}

// Database username
if ($post['username'] != "") {
	$username = urldecode($post['username']);
} else {
	$error[] = "Please fill out the 'Database Username' field.";
}

// Database password
if ($post['password'] != "") {
	$password = urldecode($post['password']);
} else {
	$password = "";
}

// Database name
if ($post['name'] != "") {
	$name = urldecode($post['name']);
} else {
	$error[] = "Please fill out the 'Database Name' field.";
}

// Time zone
$timezone = urldecode($post['timezone']);

// Password salt
if ($post['pass-salt'] != "") {
	if (strlen($post['pass-salt']) > "10") {
		$password_salt = urldecode($post['pass-salt']);
	} else {
		$error[] = "The password salt must be at least 10 characters in length.";
	}
} else {
	$error[] = "Please fill out the 'Password Salt' field.";
}

// Session salt
if ($post['sess-salt'] != "") {
	if (strlen($post['sess-salt']) > "10") {
		$session_salt = urldecode($post['sess-salt']);
	} else {
		$error[] = "The session salt must be at least 10 characters in length.";
	}
} else {
	$error[] = "Plesae fill out the 'Session Salt' field.";
}

// Test connection
$test = @new mysqli($host, $username, $password, $name);
if ($test->connect_errno) $error[] = "Failed to connect to the database.";

// Show Errors
if (!empty($error)) {
	notify("install", "failure", $error[0]);
    run_after_ajax("undisable_form");
} else {
	// Define the contents
	$config = "<?php\n\n";
	$config .= "\$config['Database']['Host Address'] = \"".$host."\";\n";
	$config .= "\$config['Database']['Username'] = \"".$username."\";\n";
	$config .= "\$config['Database']['Password'] = \"".$password."\";\n";
	$config .= "\$config['Database']['Name'] = \"".$name."\";\n\n";
	$config .= "\$config['timezone'] = \"".$timezone."\";\n\n";
	$config .= "\$config['salt']['password'] = \"".$password_salt."\";\n";
	$config .= "\$config['salt']['session'] = \"".$session_salt."\";\n\n";

    // Write the contents to the file
	$file = fopen(path(ROOT."/config.php"), "w");
	fwrite($file, $config);
	fclose($file);

	notify("install", "success", "The config file has been created successfully.<br />".
        js_countdown());
	run_after_ajax("go_step", '{"step":"db"}');
}