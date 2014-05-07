<?php

// Define empty arrays for the javascript and css files
$feature['js']['file']  = array();
$feature['css']['file'] = array();

// Add the installer styling to all pages except the homepage
if ($file != "index.php") {
    $feature['css']['file'][] = "install.css";
}

// Define the feature view files
$view_files = array(
    "index.php",
    "dependencies-check.php",
    "database-configuration.php",
    "customization-and-security.php",
    "first-user-setup.php",
    "advanced-options.php",
    "review-and-install.php");

// Define the API files and only allow API calls for them
$api_files = array(
    "check-database-configuration.php",
    "check-database-connection.php",
    "check-custom-and-security.php",
    "check-first-user.php",
    "check-advanced-options.php",
    "create-config-file.php",
    "create-database-structure.php",
    "add-database-data.php",
    "create-first-user.php",
    "finish-installation.php");

// Don't show errors for the API files so we get an actual error in the returned data
if (in_array($file, $api_files)) {
    ini_set("display_errors", 0);
}

// Die on anyone trying to make a request to a file that isn't defined here
if (!in_array($file, $view_files) && !in_array($file, $api_files)) {
    $ajax === false ? back_up() : die();
}

switch ($file) {
    case "index.php":
        $feature['title']   = "Welcome";
        $feature['header']  = "";

        $feature['css']['file'][] = "homepage.css";

        $feature['theme']   = "homepage";
        break;

    case "dependencies-check.php":
        $feature['title'] = $feature['header']  = "Dependencies Check";
        break;

    case "database-configuration.php":
        $feature['title'] = $feature['header']  = "Database Configuration";
        break;

    case "customization-and-security.php":
        $feature['title'] = $feature['header']  = "Site Customization and Security";
        break;

    case "first-user-setup.php":
        $feature['title'] = $feature['header']  = "First User Setup";
        break;

    case "advanced-options.php":
        $feature['title'] = $feature['header']  = "Advanced Installation Options";
        break;

    case "review-and-install.php":
        $feature['title'] = $feature['header']  = "Review and Install";
        break;
}