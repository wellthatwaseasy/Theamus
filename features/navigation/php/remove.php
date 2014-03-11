<?php

$links_table = $tDataClass->prefix."_links"; // Define the table
$post = filter_input_array(INPUT_POST); // Clean the incoming information

$error = array(); // Error checking

$id = ""; // Default ID for the check query later

// Link ID
if (isset($post['link_id']) && $post['link_id'] != "") {
    $id = $tData->real_escape_string($post['link_id']);
} else {
    $error[] = "Unknown link ID.";
}

// Query the database for the link
$sql['find'] = "SELECT * FROM `$links_table` WHERE `id`='$id'";
$qry['find'] = $tData->query($sql['find']);
if ($qry['find'] && $qry['find']->num_rows == 0) {
    $error[] = "There was an issue finding the link in question.";
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    // Remove the link from the database
    $sql['delete'] = "DELETE FROM `$links_table` WHERE `id`='$id'";
    $qry['delete'] = $tData->query($sql['delete']);

    // Check the query and respond accordingly
    if (!$qry['delete']) {
        notify("admin", "failure", "There was an error deleting this link.");
    }
}
