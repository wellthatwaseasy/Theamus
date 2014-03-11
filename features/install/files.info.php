<?php

$feature['js']['file'] = array();
$feature['css']['file'] = array();

switch ($file) {
    case "create-config.php":
        $feature['js']['file'][] = "main.js";
        break;

    case "create-db.php":
    case "create-database.php":
    case "create-user.php":
        $feature['class']['file'] = "install.class.php";
        $feature['class']['init'] = "Installer";
        break;

    default:
        $feature = true;
}