<?php

function connect() {
    // Connect to the database
    $tDataClass = new tData();
    $tData = $tDataClass->connect();

    return $tData;
}

function get_item_sql($column, $items) {
    $tData = connect();

    // Loop through all of the items
    foreach ($items as $item) {
        // Split the item to get the path/position
        $info = explode("=", $item);

        // Saftey first!
        $path = $tData->real_escape_string($info[0]);
        $pos = $tData->real_escape_string($info[1]);

        // Add this query to the sql array
        $sql[] = "UPDATE `dflt_home-apps` SET `position`='".$pos."',"
            . " `column`='".$column."' WHERE `path`='".$path."';\n";
    }

    return $sql;
}

function get_sql($column) {
    // Clean the GET
    $get = filter_input_array(INPUT_GET);

    // Make sure this variable is available to us
    if (!isset($get["column".$column])) {
        die(notify("admin", "failure", "There was an issue finding the column."));
    }

    // Don't do anything if there's nothing to do
    if ($get["column".$column] == "") {
        return false;

    // Define the column items and return the sql
    } else {
        $items = explode(",", $get["column".$column]);
        return implode("", get_item_sql($column, $items));
    }
}

$user = $tUser->user;

if ($user != false) {
    $sql[] = get_sql("1");
    $sql[] = get_sql("2");
    $sql = implode("", $sql);

    if ($tData->multi_query($sql)) {
        notify("admin", "success", "Your home page has been saved.");
    } else {
        notify("admin", "failure", "There was an error saving the home page.");
    }
}