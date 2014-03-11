<?php

// Get the system settings
$settingsTable = $tDataClass->prefix . '_settings';
$sql['settings'] = 'SELECT * FROM `' . $settingsTable . '`';
$qry['settings'] = $tData -> query($sql['settings']);
$settings = $qry['settings'] -> fetch_assoc();

// Get the update server
$server = "http://theamus.com/releases/update/";
$info = @file_get_contents($server);

if (!$info) {
    echo 'There was an issue connecting to the update server.';
    run_after_ajax('backToCheck');
} else {
    // Get the update information
    $info = json_decode($info, true);

    if (!empty($info) && in_array($settings['version'], $info['old_versions'])):
?>
        <?=notify("admin", "success", "There's an update available for your system!
        <a href='#' onclick=\"return admin_go('settings', 'settings/settings/');\">Update Now!</a>")?>
        <div style="border: 1px solid #EEE; margin: 10px 0; padding: 5px 5px 10px;
             overflow-y: auto; max-height: 150px;">
            <?=$info['notes']?>
        </div>
<?php
    else:
        echo 'There are no updates available at this time for your system.';
    endif;
}