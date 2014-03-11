<?php

$table = $tDataClass->prefix."_links"; // Define the links table
$error = array(); // Error checking array
$post  = filter_input_array(INPUT_POST); // Clean post input

$alias = ""; // Default for check query later on

// Link text
if ($post['text'] != "") {
    $text = urldecode($post['text']);
    $alias = str_replace(" ", "_", strtolower($text));

    // Check for the length of the link text
    if (strlen($text) < 2) {
        $error[] = "The link text must be at least 2 characters long.";
    } else {
        // Allow only upper/lowercase letters, numbers and spaces
        if (!preg_match("/[^a-zA-Z0-9 ]/", $text)) {
            $text  = $tData->real_escape_string($text);
            $alias = $tData->real_escape_string($alias);
        } else {
            $error[] = "The text can only contain alphabet letters and spaces.";
        }
    }
} else {
    $error[] = "Please fill out the 'Link Text' field.";
}

// Link Path
if ($post['path-type'] != "") {
    // Define the path type
    $pt_exploded = explode("-", $post['path-type']);
    $type        = $tData->real_escape_string($pt_exploded[1]);

    // Define the path based on the type selected
    switch ($type) {
        case "url":
            $path = $post['url'];
            break;
        case "page":
            $path = $post['page'];
            break;
        case "feature":
            $path = $post['feature']."/".$post['file'];
            break;
        case "js":
            $path = $post['js'];
            break;
        default:
            $path = $post['url'];
    }

    // Check for a path
    if ($path != "") {
        // Clean the path variable
        $path = trim(urldecode($path), "/");
        $path = $tData->real_escape_string($path."/");
    } else {
        $error[] = "Please choose a path for this link to go to.";
    }
}

// Position
$position = $tData->real_escape_string(urldecode($post['position']));

// Child of
$child_of = $tData->real_escape_string(urldecode($post['child_of']));

// Link weight
$weight = $tData->real_escape_string($post['weight']);

// Link groups
if ($post['groups'] != "") {
    $groups = $tData->real_escape_string(urldecode($post['groups']));
} else {
    $groups = "everyone";
}

// Query the database for an existing link
$sql['find'] = "SELECT * FROM `$table` WHERE `alias`='$alias'";
$qry['find'] = $tData->query($sql['find']);
if ($qry['find'] && $qry['find']->num_rows > 0) {
    $error[] = "A link link this one already exists.";
}

// Show errors
if (!empty($error)) {
	notify("admin", "failure", $error[0]);
} else {
	// Add the new link to the database
	$sql['add'] = "INSERT INTO `$table` ".
        "(`alias`, `text`, `path`, `weight`, `groups`, `type`, `location`, `child_of`) VALUES ".
        "('$alias', '$text', '$path', '$weight', '$groups', '$type', '$position', '$child_of')";
	$qry['add'] = $tData->query($sql['add']);

    // Check for a good query
    if ($qry['add']) {
        // Notify user and get out
        notify("admin", "success", "The link <b>$text</b> has been created.<br />".js_countdown());
        run_after_ajax("back_to_list");
    } else {
        notify("admin", "failure", "There was an error saving this information.");
    }
}