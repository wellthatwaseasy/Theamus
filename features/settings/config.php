<?php

// Define custom folders
$feature['scripts']['folder']	= "php";
$feature['js']['folder']		= "js";
$feature['css']['folder']       = "css";
$feature['class']['folder']     = "classes";

// Custom function file to load beforehand
//$feature['functions']['file'] = "php/functions.php";
$feature['api']['class_file'] = array("classes/settings.class.php", "classes/settings-api.class.php");

// Define the feature information
$feature['alias']       = "settings";
$feature['name']        = "Theamus Settings";
$feature['db_prefix']	= "";
$feature['version']     = "1.0";
$feature['notes']       = array();

// Define the author information
$feature['author']['name']      = "Eyrah Temet";
$feature['author']['alias']     = "Eyraahh";
$feature['author']['email']     = "eyrah.temet@theamus.com";
$feature['author']['company']   = "Theamus";

// Define configuration scripts
/*
$feature['install']['script']   = "config/install.php";
$feature['update']['script']    = "config/update.php";
$feature['remove']['script']    = "config/remove.php";
 */
