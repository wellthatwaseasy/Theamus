<?php

$post = filter_input_array(INPUT_POST); // Clean request information
$themes_table = $tDataClass->prefix."_themes"; // Define the themes table
$error = array(); // Error checking array

// Theme ID
if ($post['theme_id'] != "") {
    $id = $tData->real_escape_string($post['theme_id']);
} else {
    $error[] = "Unknown theme ID.";
}

// Query the database for this theme
$sql['theme'] = "SELECT * FROM `$themes_table` WHERE `id`='$id'";
$qry['theme'] = $tData->query($sql['theme']);
if ($qry['theme'] && $qry['theme']->num_rows == 0) {
    $error[] = "Theme not found!";
}

// Show errors
if (!empty($error)) {
	notify("admin", "failure", $error[0]);
} else {
	// Get the theme information
	$theme = $qry['theme']->fetch_assoc();

	// Define the theme folder
	$path = path(ROOT."/themes/".$theme['alias']);

	// Remove the theme folder
	$tFiles->remove_folder($path);

	// Remove the database entry
	$sql['delete'] = "DELETE FROM `".$themes_table."` WHERE `id`='".$id."'";
	$tData->query($sql['delete']);
        
        $tData->query("DELETE FROM `".$tDataClass->prefix."_themes-data` WHERE `theme`='".$theme['alias']."'");
}