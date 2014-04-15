<?php

$error = array(); // Empty error array
$media_table = $tDataClass->prefix."_media"; // Define the images database table
$get = filter_input_array(INPUT_GET); // Define and clean the GET information

// Image ID
$id = 0; // Default for the table check later
if (isset($get['id'])) {
    if ($get['id'] != "" && is_numeric($get['id'])) {
        $id = $tData->real_escape_string($get['id']);
    } else {
        $error[] = "Invalid ID type.";
    }
} else {
    $error[] = "Unknown ID.";
}

// Check for database existance
$sql['find'] = "SELECT * FROM `$media_table` WHERE `id`='$id'";
$qry['find'] = $tData->query($sql['find']);
if ($qry['find'] && $qry['find']->num_rows == 0) {
    $error[] = "Cannot find image in the database.";
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    // Get the image information from the database
    $media = $qry['find']->fetch_assoc();

    // Remove the image, if it exists
    $path = ROOT."/media/images/".$media['path'];
    if (file_exists($path)) {
        unlink($path);
    }

    // Remove the image from the database
    $sql['remove'] = "DELETE FROM `$media_table` WHERE `id`='$id'";
    $qry['remove'] = $tData->query($sql['remove']);

    // Check query
    if (!$qry['remove']) {
        notify("admin", "failure", "There was an error removing the image from the database.");
    } else {
        notify("admin", "success", "Image removed.");
    }
}