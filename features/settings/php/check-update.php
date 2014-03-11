<?php

// Get the system settings
$settingsTable = $tDataClass->prefix . '_settings';
$sql['settings'] = 'SELECT * FROM `' . $settingsTable . '`';
$qry['settings'] = $tData -> query($sql['settings']);
$settings = $qry['settings'] -> fetch_assoc();

// Get the update server
$server = "http://theamus.com/releases/update/";
$info = file_get_contents($server);

if (!$info) {
    echo 'There was an issue connecting to the update server.';
    run_after_ajax('backToCheck');
} else {
    // Get the update information
    $info = json_decode($info, true);

    if (!empty($info) && in_array($settings['version'], $info['old_versions'])) {
        echo 'There\'s an update available for your system.';
        run_after_ajax('prepareUpdate');
    } else {
        echo 'There are no updates available at this time.';
        run_after_ajax('backToCheck');
    }
}

?>