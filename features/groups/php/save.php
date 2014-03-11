<?php

$error = array(); // Define an empty error array

$post = filter_input_array(INPUT_POST); // Define a filtered post array
$get = filter_input_array(INPUT_GET);   // Define a filtered get array

// Get group ID
if ($post['group_id'] != "") {
    $id = $post['group_id'];
} else {
    $error[] = "There was an error finding the group's ID.";
}

// Get group name
if ($post['name'] != "") {
    $name = urldecode($post['name']);
    if (!preg_match("/[^a-zA-Z ']/", $name)) { // Check for illegal characters
        // Clean and define the alias and name
        $cleaned_alias = str_replace(" ", "_", strtolower($name));
        $alias = $tData->real_escape_string($cleaned_alias);
        $name = $tData->real_escape_string($name);
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

// Get home page
if ($get['home'] != "") {
    $home = $tData->real_escape_string($get['home']);
} else {
    $error[] = "There's something wrong with the home page selection.";
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);

// Update group
} else {
    // Define the groups table
    $table = $tDataClass->prefix . "_groups";

    // Query to update
    $sql['update'] = "UPDATE `".$table."` SET "
            . "`alias`='".$alias."', "
            . "`name`='".$name."', "
            . "`permissions`='".$permissions."', "
            . "`home_override`='".$home."' "
            . "WHERE `id`='".$id."'";
    $qry['update'] = $tData->query($sql['update']);

    if ($qry['update']) {
        notify("admin", "success", "This information has been saved.");
    } else {
        notify("admin", "failure", "There was an error querying the database.");
    }
}