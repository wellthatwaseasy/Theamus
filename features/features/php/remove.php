<?php

$post = filter_input_array(INPUT_POST); // Clean the request info
$error = array(); // Error checking
$features_table = $tDataClass->prefix."_features"; // Define the features table
$perm_table = $tDataClass->prefix."_permissions"; // Define the permissions table

$id = ""; // Default ID for check later

// Feature ID
if ($post['feature_id'] != "") {
    $id = $tData->real_escape_string($post['feature_id']);
} else {
    $error[] = "Unknown feature ID.";
}

// Query the database for an existing group
$sql['find'] = "SELECT * FROM `$features_table` WHERE `id`='$id'";
$qry['find'] = $tData->query($sql['find']);
if ($qry['find'] && $qry['find']->num_rows == 0) {
    $error[] = "This feature doesn't exist.";
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    // Get the feature db information
    $feature = $qry['find']->fetch_assoc();

    // Define the alias and database prefix
    $alias 		= $tData->real_escape_string($feature['alias']);
    $dbprefix 	= $tData->real_escape_string($feature['db_prefix']);

    if ($dbprefix != "") {
        // Generate the query to delete relevant queries
        $qry['gen'] = $tData->query("SELECT CONCAT('DROP TABLE ', ".
            "GROUP_CONCAT(table_name), ';') AS statement FROM ".
            "information_schema.tables WHERE table_name LIKE '$dbprefix%';");
        if ($qry['gen']->num_rows > 0) {
            $generated = $qry['gen']->fetch_assoc();
            if ($generated['statement'] != "") {
                $tData->query($generated['statement']);
            }
        }
    }

    // Define the feature folder and delete it
    $path = path(ROOT."/features/".$feature['alias']);
    $tFiles->remove_folder($path);

    // Query to delete feature
    $sql['remove'] = "DELETE FROM `$features_table` WHERE `id`='$id'";
    $tData->query($sql['remove']);

    // Query to delete permissions
    $sql['perm'] = "DELETE FROM `".$perm_table."` WHERE `feature`='$alias'";
    $tData->query($sql['perm']);
}