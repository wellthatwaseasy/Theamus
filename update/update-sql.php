<?php

function no() {
    $sql['update'] = "UPDATE `".$settingsTable."` SET `version`='0.7'";
    $tData->query($sql['update']);
        
    include path($tempPath.$tempFolder."/update-functions.php");

    if ($version == "0.5") update_05();
    if ($version == "0.6") update_06();

    cleanup();
}

function say_something() {
    return "This is something from update-sql.php!";
}