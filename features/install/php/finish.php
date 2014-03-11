<?php

// Remove the update file
$tFiles->remove_folder(path(ROOT."/update"));

// Remove the installer folders
$tFiles->remove_folder(path(ROOT."/features/install/"));
$tFiles->remove_folder(path(ROOT."/themes/installer/"));

// Update the SQL database
$tData->query("UPDATE `".$tDataClass->prefix."_settings` SET `installed`='1'");

