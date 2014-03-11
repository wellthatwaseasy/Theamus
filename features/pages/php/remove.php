<?php

$post = filter_input_array(INPUT_POST);

$error = array();

$pages_table = $tDataClass->prefix."_pages";
$links_table = $tDataClass->prefix."_links";

// Check for a valid page ID
if (isset($post['page_id'])) {
    $id = $post['page_id'];
    if ($id != "" && is_numeric($id)) {
        $id = $tData->real_escape_string($id);

        // Get page from database
        $sql['find'] = "SELECT * FROM `$pages_table` WHERE `id`='$id'";
        $qry['find'] = $tData->query($sql['find']);

        // Check for a valid page in the database
        if ($qry['find']) {
            if ($qry['find']->num_rows > 0) {
                $page = $qry['find']->fetch_assoc(); // Grab the info
            } else {
                $error[] = "There was an issue finding this page.";
            }
        }
    } else {
        $error[] = "Invalid ID type.";
    }
} else {
    $error[] = "What page are you talking about?";
}

// Check if the user wants to remove the associated links
if ($post['remove_links'] == "true") {
    // Only proceed if we have a valid page
    if (isset($page)) {
        // Get all of the associated links related to this page
        $sql['links'] = "SELECT * FROM `$links_table` WHERE `path` LIKE '".$page['alias']."%'";
        $qry['links'] = $tData->query($sql['links']);

        // Check for links
        if ($qry['links']) {
            if ($qry['links']->num_rows > 0) {
                // Loop through all of the links, defining their removal queries
                while ($link = $qry['links']->fetch_assoc()) {
                    $qry['remove'][] = "DELETE FROM `$links_table` WHERE `id`='".$link['id']."'";
                }
            }
        }
    }
}

// Show errors, if any
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    // Set up the SQL to remove the page
    $qry['remove'][] = "DELETE FROM `$pages_table` WHERE `id`='$id'";

    // Loop through the removal queue and run the queries
    foreach ($qry['remove'] as $remove) {
        $tData->query($remove);
    }

    // Success!
    notify("admin", "success", "This page has been deleted.");
}