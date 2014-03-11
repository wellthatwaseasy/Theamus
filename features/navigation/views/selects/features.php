<?php

// Define the GET request information
$get = filter_input_array(INPUT_GET);

$get_feature = "";
if (isset($get['feature']) && $get['feature'] != "") {
    $get_feature = $get['feature'];
}

// Define the features table
$feature_table = $tDataClass->prefix."_features";

// Query the database for all available features
$qry['find'] = $tData->query("SELECT * FROM `$feature_table` ORDER BY `name` ASC");

// Check for a valid query
if ($qry['find'] && $qry['find']->num_rows > 0) {
    // Loop through all of the features echoing them as options
    while ($feature = $qry['find']->fetch_assoc()) {
        $selected = $get_feature == $feature['alias'] ? "selected" : "";
        echo "<option $selected value='".$feature['alias']."'>".$feature['name']."</option>";
    }
} else {
    // Throw out an error in the form of an option
    echo "<option>Error loading features</option>";
}