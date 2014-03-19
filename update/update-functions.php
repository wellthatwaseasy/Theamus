<?php

function update_02() {
    // Define the return array, connect and define database variables
    $return = array();
    $tDataClass = new tData();
    $tData = $tDataClass->connect();
    $prefix = $tDataClass->get_system_prefix();
    
    // Define the queries to perform
    $queries = array("CREATE TABLE IF NOT EXISTS `".$prefix."_themes-data` (`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `key` TEXT NOT NULL, `value` TEXT NOT NULL, `selector` TEXT NOT NULL, `theme` VARCHAR(50) NOT NULL);");
    
    // Perform the queries
    foreach ($queries as $query) {
        $return[] = $tData->query($query) ? true : false;
    }
    
    // Disconnect from the database and return
    $tDataClass->disconnect();
    return in_array(false, $return) ? false : true;
}

function update_version() {
    // Define the return array, connect and define database variables
    $return = array();
    $tDataClass = new tData();
    $tData = $tDataClass->connect();
    $prefix = $tDataClass->get_system_prefix();
    
    // Update the version
    $return[] = $tData->query("UPDATE `".$prefix."_settings` SET `version`='0.8'") ? true : false;
    
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