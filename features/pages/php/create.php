<?php

$post = filter_input_array(INPUT_POST);

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
        $query = $tData->query("SELECT * FROM `".$tDataClass->prefix."_pages` WHERE `alias`='$alias'");
        if ($query->num_rows > 0) {
            $error[] = "A page with this title/alias already exists.  Please choose another.";
        }
    } else {
        $error[] = "Please fill out the 'Page Title' field.";
    }
}

// Get the page content
if (isset($post['content'])) {
    $content = strip_tags(urldecode($post['content']));
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

// Get new link
if (isset($post['create_link'])) {
    if ($post['create_link'] == "true") {
        $links_table = $tDataClass->prefix."_links";
        $sql['create_link'] = "INSERT INTO `".$links_table."`"
                . "(`alias`, `text`, `path`, `weight`, `groups`, `type`, `location`, `child_of`) VALUES "
                . "('".$alias."', '".$title."', '".$alias."', 0,  '".$groups."', 'page', 'main', 0)";
    } else {
        $sql['create_link'] = false;
    }
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    if ($sql['create_link'] != false) {
        if (!$tData->query($sql['create_link'])) {
            die(notify("admin", "failure", "There was an issue creating the link for this page."));
        }
    }

    $pages_table = $tDataClass->prefix."_pages";
    $sql['create'] = "INSERT INTO `".$pages_table."` "
            . "(`alias`, `title`, `content`, `views`, `permanent`, `groups`, `theme`, `navigation`) "
            . "VALUES "
            . "('".$alias."', '".$title."', '".$content."', 0, 0, '".$groups."', "
            . "'".$theme."', '".$nav."')";
    if ($tData->query($sql['create'])) {
        notify("admin", "success", "This page has been created.<br />".js_countdown());
        run_after_ajax("back_to_pagelist");
    } else {
        notify("admin", "failure", "There was an issue creating this page.");
    }
}