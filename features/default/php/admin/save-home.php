<?php

// Get current user's info
$user = $tUser->user;

// Only do if a user is logged in
if ($user != false) {
    // Get apps
    if (isset($_GET['apps'])) {
        $get_apps = $_GET['apps'];
        if ($get_apps != "") {
            $get_apps = explode(",", $get_apps);
            foreach ($get_apps as $app) {
                $app = explode("=", $app);
                $apps[$app[0]] = $app[1];
            }
        } else {
            $error[] = "There are no apps to save.";
        }
    } else {
        $error[] = "There is an issue with the submitted apps; there are none.";
    }

    // Show errors
    if (!empty($error)) {
        notify("admin", "failure", $error[0]);
    } else {
        // Save the apps
        foreach ($apps as $key => $val) {
            $sql['update'] = "UPDATE `dflt_home-apps` SET `active`='".$val."' WHERE `path`='".$key."'";
            $qry['update'] = $tData->query($sql['update']);
        }

        // Notify the user and get out
        notify("admin", "success", "Your apps have been saved.<br />".js_countdown());
    }
}