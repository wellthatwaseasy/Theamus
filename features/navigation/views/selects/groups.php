<?php

$get = filter_input_array(INPUT_GET);

// Groups
if (isset($get['groups']) && $get['groups'] != "") {
    $groups = explode(",", urldecode($get['groups']));
} else {
    $groups = array();
}

// Define the groups table
$groups_table = $tDataClass->prefix."_groups";

// Query the database for groups
$qry['find'] = $tData->query("SELECT * FROM `".$groups_table."`");

// Loop through all groups, showing as options
while ($group = $qry['find']->fetch_assoc()) {
    if (empty($groups)) {
        $selected = $group['alias'] == "everyone" ? "selected" : "";
    } else {
        $selected = in_array($group['alias'], $groups) ? "selected" : "";
    }
    echo "<option ".$selected." value='".$group['alias']."'>"
            .$group['name']."</option>";
}