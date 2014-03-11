<?php

$sql['update'] = "UPDATE `".$settingsTable."` SET `version`='0.7'";
$tData->query($sql['update']);
    
include path($tempPath.$tempFolder."/update-functions.php");

if ($version == "0.5") update_05();
if ($version == "0.6") update_06();

cleanup();