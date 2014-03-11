<?php

// Error checking
$error = array();

// Site name
$_POST['name'] != "" ? $name = urldecode($_POST['name']) : $error[] = "Please fill out the 'Site Name' field.";
if (preg_match("/[^a-zA-Z ]/", @$name)) {
    $error[] = "The name can only contain alphabet letters and spaces.";
}

$_GET['home'] != '' ? $home = $_GET['home'] : $error[] = 'There\'s something wrong with the home page variable.';

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    // Define the table
    $table = $tDataClass->prefix . "_settings";

    // Define sql friendly variable
    $name = $tData -> real_escape_string($name);
    $home = $tData -> real_escape_string($home);

    // Query the database for changes
    $sql = "UPDATE `" . $table . "` SET `name`='" . $name . "', `home`='" . $home . "' WHERE `id`='1'";
    $tData -> query($sql);

    // Notify the user
    notify("admin", "success", "These customizations have been saved.");
}
?>