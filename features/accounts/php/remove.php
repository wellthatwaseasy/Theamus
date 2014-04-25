<?php

// Error checking
$error = array();

$query_data = array("table_name" => $tData->prefix."_users");

$post = filter_input_array(INPUT_POST);

// User id
if ($post['user_id'] != "") {
    $id = $post['user_id'];

    // Query the database for an existing user
    $query_user = $tData->select_from_table($query_data['table_name'], array("username"), array("operator" => "", "conditions" => array("id" => $id)));

    // Check for records
    if ($tData->count_rows($query_user) == 0) {
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
    $query = $tData->delete_table_row($query_data['table_name'], array("operator" => "", "conditions" => array("id" => $id)));

    if ($query != false) {
        notify("admin", "success", "This user has been deleted.");
    } else {
        notify("admin", "failure", "There was an issue querying the database.");
    }
}
