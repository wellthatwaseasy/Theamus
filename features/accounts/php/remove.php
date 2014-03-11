<?php

// Error checking
$error = array();

$post = filter_input_array(INPUT_POST);

// User id
if ($post['user_id'] != "") {
    $id = $tData->real_escape_string($post['user_id']);

    // Define the users table
    $table = $tDataClass->prefix."_users";

    // Query the database for an existing user
    $sql['find'] = "SELECT * FROM `".$table."` WHERE `id`='".$id."'";
    $qry['find'] = $tData->query($sql['find']);

    // Check for records
    if ($qry['find']->num_rows == 0) {
        $error[] = "This user doesn't exist.";
    }
} else {
    $error[] = "No user ID defined.";
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);

// Delete the user from the database
} else {
    // Query to delete
    $sql['delete'] = "DELETE FROM `".$table."` WHERE `id`='".$id."'";
    $qry['delete'] = $tData->query($sql['delete']);

    if ($qry['delete']) {
        notify("admin", "success", "This user has been deleted.");
    } else {
        notify("admin", "failure", "There was an issue querying the database.");
    }
}
