<?php

function get_app_folders() {
    // Get access to the folders
    $tFiles = new tFiles();

    // Get all of the app folders
    $path = path(ROOT."/features/default/home-apps");
    $folders = $tFiles->scan_folder($path, $path, "folders");

    return $folders;
}

function connect() {
    // Connect to the database and return the new resource
    $tDataClass = new tData();
    $tData = $tDataClass->connect();
    return $tData;
}

function check_existance($folders) {
    $tData = connect(); // Database!
    $ret = array();

    // Loop through all of the folders, checking which ones exist in the database
    foreach ($folders as $path) {
        $q = $tData->query("SELECT * FROM `dflt_home-apps` WHERE `path`='".$path."'");
        if ($q->num_rows == 0) {
            $ret[] = $path; // If they aren't in the database, add them to the return array
        }
    }
    return $ret;
}

function check_variables($var) {
    $ret = true;
    if (!array_key_exists("title", $var)) {
        $ret = false;
    }
    if (!array_key_exists("alias", $var)) {
        $ret = false;
    }

    return $ret;
}

function add_app_to_db($info) {
    $tData = connect(); // Database!

    if (!check_variables($info)) {
        notify("admin", "failure", "There was an error in the configuration file in one"
            . " of the home page app's configuration file. Installation aborted.");
        die();
    }

    // Sanitize the variables
    $alias = $tData->real_escape_string($info['alias']);
    $title = $tData->real_escape_string($info['title']);

    // Add the new app to the database
    $s = "INSERT INTO `dflt_home-apps` (`name`, `path`, `active`, `position`, `column`)"
            . " VALUES ('".$title."', '".$alias."', 0, 1, 1);";
    if (!$tData->query($s)) {
        return false;
    } else {
        return true;
    }
}

function check_config($app) {
    $path = path(ROOT."/features/default/home-apps/");

    if (!file_exists($path.$app."/config.php")) {
        notify("admin", "failure", "I couldn't install '".$app."'"
            . " -- there is no config file. This has prevented me from installing"
            . " other home page apps.");
        return false;
    } else {
        return true;
    }
}

function install_apps($new) {
    $path = path(ROOT."/features/default/home-apps/");
    $fail = false;
    $count = 0;

    // Loop through all of the new apps
    foreach ($new as $app) {
        if (!check_config($app)) {
            die();
        }

        include $path.$app."/config.php";
        if (!add_app_to_db($homeapp)) {
            $fail = true;
            break;
        }

        $count++;
    }

    if ($fail == true) {
        notify("admin", "failure", "There was an error installing an app. Try refreshing the page.");
        return false;
    }

    if ($count == 0) {
        return false;
    }

    return true;
}

function check_db_existance($folders) {
    $tData = connect();
    $q = $tData->query("SELECT * FROM `dflt_home-apps`");
    $ret = array();
    while ($app = $q->fetch_assoc()) {
        if (!in_array($app['path'], $folders)) {
            $ret[] = $app['path'];
        }
    }

    return $ret;
}

function remove_apps($apps) {
    $tData = connect();
    $count = 0;
    foreach($apps as $app) {
        $q = $tData->query("DELETE FROM `dflt_home-apps` WHERE `path`='".$app."'");
        if (!$q) {
            notify("admin", "failure", "There was an error removing an old app from"
                . " the database. This has prevented me from removing other old apps.");
            return false;
        }
        $count++;
    }

    if ($count == 0) {
        return false;
    }

    return true;
}

function success() {
    notify("admin", "warn",
        "I noticed a change in your home-apps folder, I've made changes to the database ".
        "and folder where I could.".
        "<span style='margin-left:20px'>".
            "<a href='#' onclick=\"$('#notify').remove(); return false;\">Dismiss</a>".
        "</span>");
}

$user = $tUser->user;

if ($user != false) {
    $folders = get_app_folders();

    $new = check_existance($folders);
    $old = check_db_existance($folders);

    if (install_apps($new) || remove_apps($old)) {
        success();
    }
}