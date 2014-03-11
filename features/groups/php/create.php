<?php

$error = array(); // Define an empty error array

$post = filter_input_array(INPUT_POST); // Define a filtered post array
$get = filter_input_array(INPUT_GET);   // Define a filtered get array

$groups_table = $tDataClass->prefix."_groups";

// Get group name
if ($post['name'] != "") {
    $name = urldecode($post['name']);
    if (!preg_match("/[^a-zA-Z ']/", $name)) { // Check for illegal characters
        // Clean and define the alias and name
        $cleaned_alias = str_replace(" ", "_", strtolower($name));
        $alias = $tData->real_escape_string($cleaned_alias);
        $name = $tData->real_escape_string($name);

        // Query the database for a group with this name
        $sql['check'] = "SELECT * FROM `".$groups_table."` WHERE `alias`='".$alias."'";
        $qry['check'] = $tData->query($sql['check']);

        if ($qry['check']) {
            if ($qry['check']->num_rows > 0) {
                $error[] = "A group with that name already exists";
            }
        } else {
            $error[] = "There was an error querying the database to check the name.";
        }
    } else {
        $error[] = "The group's name can only contain alphabet letters and spaces.";
    }
} else {
    $error[] = "Please fill out the 'Group Name' field.";
}

// Get permissions
$permissions = "";
if ($post['permissions'] != "" || $post['name'] == "Everyone") {
    $permissions = $tData->real_escape_string(urldecode($post['permissions']));
}

if (!empty($error)) { // Show errors
	notify("admin", "failure", $error[0]);
} else {
	// Query to create
	$sql['create'] = "INSERT INTO `".$groups_table."` "
            . "(`alias`, `name`, `permissions`, `home_override`) "
            . "VALUES "
            . "('".$alias."', '".$name."', '".$permissions."', 'false');";
	$qry['create'] = $tData->query($sql['create']);

	// Notify user and get out
    if ($qry['create']) {
        notify("admin", "success", "This group has been created.<br/>".js_countdown());
        run_after_ajax("back_to_grouplist");
    } else {
        notify("admin", "failure", "There was an error querying the database to create.");
    }
}