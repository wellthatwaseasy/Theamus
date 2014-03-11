<?php

$alias = ''; // Alias by default is blank

// Clean the get input
$get = filter_input_array(INPUT_GET);

// Check for a valid alias
if (isset($get['alias']) && $get['alias'] != "") {
    $alias = $get['alias'];
}

// Check for a valid file
$get_file = "";
if (isset($get['file']) && $get['file'] != "") {
    $get_file = trim($get['file'], "/");
}

// Define the path to the feature's view files
$featurePath = path(ROOT."/features/$alias/views");

// Get all of the view files
$featureFiles = $tFiles->scan_folder($featurePath, $featurePath);

// Make sure there are files
if (count($featureFiles) > 0) {
    // Loop through all of the files
    foreach ($featureFiles as $fFile) {
        $hfile = array();
        // Remove the file type
        $file = explode(".", $fFile);

        // Clean up the file name
        $fileName = str_replace('.php', '', $fFile);
        $fileName = str_replace("/", ": ", $fileName);
        $fileName = str_replace("\\", ": ", $fileName);
        $fileName = str_replace("_", " ", $fileName);
        $fileName = str_replace("-", " ", $fileName);
        $fileName = ucwords($fileName);

        // Define the option that should be selected
        $selected = "";
        if ($get_file != "") {
            $selected = "$get_file.php" == $fFile ? "selected" : "";
        } else if ($fFile == "index.php") {
            $selected = "selected";
        }

        // Create the option
        echo "<option value='".$file[0]."' ".$selected.">$fileName</option>";
    }
} else {
    // Throw error in the form of an option
    echo "<option>Error loading feature files</option>";
}