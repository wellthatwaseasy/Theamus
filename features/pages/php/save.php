<?php

$post = filter_input_array(INPUT_POST);

// Get the page ID
if (isset($post['page_id'])) {
    $id = $post['page_id'];
    if ($id != "") {
        $id = $tData->real_escape_string($id);
    } else {
        $error[] = "Invalid ID value.";
    }
}

// Get the page title
if (isset($post['title'])) {
    $title = urldecode($post['title']);
    $alias = "";
    if ($title != "" ) {
        if (!preg_match("/[^a-zA-Z0-9 ]/", $title)) {
            $alias = $tData->real_escape_string(str_replace(" ", "_", strtolower($title)));
            $title = $tData->real_escape_string($title);
        } else {
            $error[] = "The title must be alphanumeric only.";
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
    $sql['update'] = "UPDATE `$pages_table` SET "
        . "`title`='$title', `content`='$content', `groups`='$groups',"
        . "`theme`='$theme', `navigation`='$nav' WHERE `id`=$id";

    if ($tData->query($sql['update'])) {
        notify("admin", "success", "The changes to this page have been saved.");
    } else {
        notify("admin", "failure", "There was an issue saving this information.");
    }
}