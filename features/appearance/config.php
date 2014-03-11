<?php

// Define custom folders
$feature['scripts']['folder']	= "php";
$feature['js']['folder']		= "js";
$feature['css']['folder']		= "css";
$feature['class']['folder']     = "classes";

// Define feature information
$feature['folder']			= "appearance";
$feature['name']			= "Appearance";
$feature['groups']			= array("administrators");
$feature['permissions']     = array("install_themes", "edit_themes",
	"remove_themes");