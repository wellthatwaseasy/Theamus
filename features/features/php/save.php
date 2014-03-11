<?php

$post = filter_input_array(INPUT_POST); // Clean the post request
$error = array(); // Error checking array
$features_table = $tDataClass->prefix."_features";

// Feature ID
if ($post['id'] != "") {
    $id = $tData->real_escape_string($post['id']);
} else {
    $error[] = "Unknown feature ID.";
}

// Feature groups
if ($post['groups'] != "") {
    $groups = $tData->real_escape_string(urldecode($post['groups']));
} else {
    $groups = "everyone";
}

// Feature enabled
$enabled = $post['enabled'] == 'true' ? 1 : 0;

// Show errors
if (!empty($error)) {
    notify('admin', 'failure', $error[0]);
} else {
    // Update the database
    $sql['update'] = "UPDATE `$features_table` SET ".
        "`groups`='$groups', `enabled`='$enabled' ".
        "WHERE `id`='$id'";
    $qry['update'] = $tData->query($sql['update']);

    // Check the query, respond accordingly
    if ($qry['update']) {
        notify('admin', 'success', 'This information has been saved.');
    } else {
        notify('admin', 'failure', 'There was an error when saving this information.');
    }
}