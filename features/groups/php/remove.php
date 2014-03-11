<?php

$error = array(); // Define an empty error array

$post = filter_input_array(INPUT_POST); // Define filtered post

$group_table = $tDataClass->prefix."_groups"; // Define the groups table
$user_table = $tDataClass->prefix."_users"; // Define the users table

// Get group ID
$id = false;
if ($post['group_id'] != "") {
    $id = $post['group_id'];
    if (is_numeric($id)) {
        $id = $tData->real_escape_string($id);
    } else {
        $error[] = "The ID provided is invalid.";
    }
} else {
    $error[] = "There was an error finding the group ID.";
}

$group = false;
if ($id) {
    // Query the database for an existing group
    $sql['check'] = "SELECT * FROM `".$group_table."` WHERE `id`='".$id."'";
    $qry['check'] = $tData->query($sql['check']);

    if ($qry['check']) {
        if ($qry['check']->num_rows > 0) {
            $group = $qry['check']->fetch_assoc();
        } else {
            $error[] = "There was an issue finding this group in the database.";
        }
    } else {
        $error[] = "There was an error querying the database for this group.";
    }
}

if ($group) {
    $sql['users'] = "SELECT * FROM `".$user_table."` WHERE `groups` LIKE '%".$group['alias']."%'";
    $qry['users'] = $tData->query($sql['users']);
    $sql['user_update'] = array();

    if ($qry['users']) {
        if ($qry['users']->num_rows > 0) {
            while ($user = $qry['users']->fetch_assoc()) {
                $user_groups = explode(",", $user['groups']);
                $new_groups = array();
                foreach ($user_groups as $ug) {
                    if ($ug != $group['alias']) {
                        $new_groups[] = $ug;
                    }
                }
                $user_groups = implode(",", $new_groups);
                $sql['user_update'][] = "UPDATE `".$user_table."` SET "
                        . "`groups`='".$user_groups."' WHERE `id`='".$user['id']."';";
            }
        }
    } else {
        $error[] = "There was an error querying the database for users.";
    }
}

// Show errors
if (!empty($error)) {
    notify("admin", "failure", $error[0]);
} else {
    if (!empty($sql['user_update'])) {
        foreach ($sql['user_update'] as $uu) {
            $tData->query($uu);
        }
    }

    $sql['delete'] = "DELETE FROM `".$group_table."` WHERE `id`='".$id."'";
    if ($tData->query($sql['delete'])) {
        notify("admin", "success", "This group has been removed.");
    } else {
        notify("admin", "failure", "There was an issue deleting this group.");
    }
}
