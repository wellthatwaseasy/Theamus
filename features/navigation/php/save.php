<?php

$table = $tDataClass->prefix."_links"; // Define the links table
$error = array(); // Error checking array
$post  = filter_input_array(INPUT_POST); // Clean post input

$id = ""; // Default for check query later on

// Link ID
if ($post['link-id'] != "") {
    $id = $tData->real_escape_string($post['link-id']);

    // Get the link information
    $query = $tData->query("SELECT * FROM `".$tDataClass->prefix."_links` WHERE `id`='$id'");
    if ($query) {
        $link = $query->fetch_assoc();
    } else {
        $error[] = "There was an error finding the link information in the database.";
    }
} else {
    $error[] = "Unkown link ID.";
}

// Link text
if ($post['text'] != "") {
    $text = htmlspecialchars(urldecode($post['text']));

    // Check for the length of the link text
    if (strlen($text) < 2) {
        $error[] = "The link text must be at least 2 characters long.";
    } else {
        // Define the text and the alias
        $text = $tData->real_escape_string($text);
        $clean_alias = preg_replace("/[^a-zA-Z0-9 ]/", '', htmlspecialchars_decode($text));
        $alias = $tData->real_escape_string(strtolower(str_replace(" ", "_", trim($clean_alias))));

        // Check the database for an existing link
        if ($alias != $link['alias']) {
            $query = $tData->query("SELECT * FROM `".$tDataClass->prefix."_links` WHERE `alias`='$alias'");
            if ($query->num_rows > 0) {
                $error[] = "A link with this text/alias already exists.  Please choose another.";
            }
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
        if ($type === "js") {
            $path = $tData->real_escape_string($path);
        } else {
            $path = $tData->real_escape_string($path."/");
        }
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
$sql['find'] = "SELECT * FROM `$table` WHERE `id`='$id'";
$qry['find'] = $tData->query($sql['find']);
if ($qry['find'] && $qry['find']->num_rows == 0) {
    $error[] = "The link that you are trying to edit cannot be found.";
}

// Show errors
if (!empty($error)) {
	notify("admin", "failure", $error[0]);
} else {
	// Update the database with new link information
	$sql['update'] = "UPDATE `$table` SET".
        "`alias`='$alias', ".
        "`text`='$text', ".
        "`path`='$path', ".
        "`weight`='$weight', ".
        "`groups`='$groups', ".
        "`type`='$type', ".
        "`location`='$position', ".
        "`child_of`='$child_of' ".
        "WHERE `id`='$id'";
	$qry['update'] = $tData->query($sql['update']);

    // Check for a good query and notify the user good/bad/otherwise
    if ($qry['update']) {
        notify("admin", "success", "This information has been saved.");
    } else {
        notify("admin", "failure", "There was an error saving this information.");
    }
}