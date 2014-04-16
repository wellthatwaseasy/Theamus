<?php

function update_02() {
    // Define the return array, connect and define database variables, define the file class
    $return = array();
    $tDataClass = new tData();
    $tData = $tDataClass->connect();
    $prefix = $tDataClass->get_system_prefix();
    $tFiles = new tFiles();

    // Define the queries to perform
    $queries = array("CREATE TABLE IF NOT EXISTS `".$prefix."_themes-data` (`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `key` TEXT NOT NULL, `value` TEXT NOT NULL, `selector` TEXT NOT NULL, `theme` VARCHAR(50) NOT NULL);");

    // Perform the queries
    foreach ($queries as $query) {
        $return[] = $tData->query($query) ? true : false;
    }

    // Remove the old system folders
    $tFiles->remove_folder(path(ROOT."/system/rta_old/"));
    $tFiles->remove_folder(path(ROOT."/system/rta/"));
    $tFiles->remove_folder(path(ROOT."/system/js/legacy/"));

    // Disconnect from the database and return
    $tDataClass->disconnect();
    return in_array(false, $return) ? false : true;
}

function update_11() {
    // Define the return array, connect and define database variables, define the file class
    $return     = array();
    $tData      = new tData();
    $tData->db  = $tData->connect();
    $prefix     = $tData->get_system_prefix();

    // Define the queries to perform
    $queries = array(
        "CREATE TABLE IF NOT EXISTS `".$prefix."_user-sessions` (`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `key` TEXT NOT NULL, `value` TEXT NOT NULL, `ip_address` TEXT NOT NULL, `user_id` INT NOT NULL);",
        "RENAME TABLE `".$prefix."_images` TO `".$prefix."_media`"
    );
    $drop_queries = array(
        "session" => array("SELECT * FROM `".$prefix."_users`", "ALTER TABLE `".$prefix."_users` DROP COLUMN `session`;"),
        "type"  => array("SELECT * FROM `".$prefix."_media`", "ALTER TABLE `".$prefix."_media` ADD `type` TEXT NOT NULL;")
    );

    // Define the drop queries
    foreach ($drop_queries as $key => $value) {
        $drop_test_query = $tData->db->query($value[0]);
        if (!$drop_test_query) {
            $queries[] = $value[1];
        } else {
            $drop_test = $drop_test_query->fetch_assoc();
            if (isset($drop_test[$key])) {
                $queries[] = $value[1];
            }
        }
    }
    
    // Define more queries
    $queries[] = "UPDATE `".$prefix."_media` SET `type`='image'";

    // Perform the queries
    $tData->db->autocommit(false);
    foreach ($queries as $query) {
        $return[] = $tData->db->query($query) ? true : false;
    }

    // Define files to remove
    $files = array(
        "themes/default/blank.html",
        "themes/default/body.html",
        "themes/default/empty.html",
        "themes/default/error.html",
        "themes/default/extra-nav.html",
        "themes/default/header.html",
        "themes/default/homepage.html",
        "themes/default/index.html",
        "themes/default/login.html",
        "features/accounts/php/login.php",
        "features/accounts/php/register.php"
    );

    // Remove the files
    foreach ($files as $file) {
        $file_path = path(ROOT."/$file");
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    if (in_array(false, $return)) {
        $tData->db->rollback();
        $tData->disconnect();
        return false;
    } else {
        $tData->db->commit();
        $tData->disconnect();
        return true;
    }
}

function update_version() {
    // Define the return array, connect and define database variables
    $return = array();
    $tDataClass = new tData();
    $tData = $tDataClass->connect();
    $prefix = $tDataClass->get_system_prefix();

    // Update the version
    $return[] = $tData->query("UPDATE `".$prefix."_settings` SET `version`='1.1'") ? true : false;

    // Disconnect from the database and return
    $tDataClass->disconnect();
    return in_array(false, $return) ? false : true;
}

function update_cleanup() {
    // Define the file management class
    $tFiles = new tFiles();

    // Remove the unnecessary folders
    $tFiles->remove_folder(path(ROOT."/themes/installer/"));
    $tFiles->remove_folder(path(ROOT."/features/install/"));
    $tFiles->remove_folder(path(ROOT."/update/"));
}