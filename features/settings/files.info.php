<?php

// Define empty files to start
$feature['js']['file'] = array();
$feature['css']['file'] = array();

// Go back if this isn't an admin call
if (!isset($_POST['ajax'])) back_up();

$feature['class']['file'] = "settings.class.php";
$feature['class']['init'] = "Settings";

// Define file specifics
switch ($file) {
    case "index.php" :
        $feature['js']['file'][] = "site.js";
        $feature['js']['file'][] = 'customization.js';
        break;

    case "settings.php" :
        $feature['js']['file'][] = "site.js";
        $feature['js']['file'][] = 'settings.js';
        break;

    default :
        break;
}
?>