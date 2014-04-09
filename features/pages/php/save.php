<?php

$post = filter_input_array(INPUT_POST);

// Get the page ID
if (isset($post['page_id'])) {
    $id = $post['page_id'];
    if ($id != "") {
        $id = $tData->real_escape_string($id);

        // Get the page information
        $query = $tData->query("SELECT * FROM `".$tDataClass->prefix."_pages` WHERE `id`='$id'");
        if ($query) {
            $page = $query->fetch_assoc();
        } else {
            $error[] = "There was an error finding the page information in the database.";
        }
    } else {
        $error[] = "Invalid ID value.";
    }
}

// Get the page title
if (isset($post['title'])) {
    $title = htmlspecialchars(urldecode($post['title']));
    $alias = "";
    if ($title != "" ) {
        // Define the title and alias
        $title = $tData->real_escape_string($title);
        $clean_alias = preg_replace("/[^a-zA-Z0-9 ]/", '', htmlspecialchars_decode($title));
        $alias = $tData->real_escape_string(strtolower(str_replace(" ", "_", trim($clean_alias))));

        // Check the database for an existing page
        if ($alias != $page['alias']) {
            $query = $tData->query("SELECT * FROM `".$tDataClass->prefix."_pages` WHERE `alias`='$alias'");
            if ($query->num_rows > 0) {
                $error[] = "A page with this title/alias already exists.  Please choose another.";
            }
        }
    } else {
        $error[] = "Please fill out the 'Page Title' field.";
    }
}

// Get the page content
if (isset($post['content'])) {
    $content = urldecode($post['content']);
    if ($content != "") {
        $content = $tData->real_escape_string($content);
    } else {
        $error[] = "Please give this page some content.";
    }
}

// Get groups
if (isset($post['groups'])) {
    $groups = urldecode($post['groups']);
    if ($groups != "") {
        $groups = $tData->real_escape_string($groups);
    } else {
        $groups = "everyone";
    }
}

// Get theme
if (isset($post['layout'])) {
    $theme = $tData->real_escape_string(urldecode($post['layout']));
}

// Get navigation
if (isset($post['navigation'])) {
    $nav = $tData->real_escape_string(urldecode($post['navigation']));
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    $pages_table = $tDataClass->prefix."_pages";
    $sql['update'] = "UPDATE `$pages_table` SET `alias`='$alias', "
        . "`title`='$title', `content`='$content', `groups`='$groups',"
        . "`theme`='$theme', `navigation`='$nav' WHERE `id`=$id";

    if ($tData->query($sql['update'])) {
        notify("admin", "success", "The changes to this page have been saved.");
    } else {
        notify("admin", "failure", "There was an issue saving this information.");
    }
}