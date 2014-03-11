<?php

$get = filter_input_array(INPUT_GET);

if (isset($get['id'])) {
    $id = $get['id'];
    if (is_numeric($id)) {
        $groups_table = $tDataClass->prefix."_groups";
        $sql['group'] = "SELECT * FROM `".$groups_table."` WHERE `id`='".$id."'";
        $qry['group'] = $tData->query($sql['group']);

        if ($qry['group']) {
            if ($qry['group']->num_rows > 0) {
                $group = $qry['group']->fetch_assoc();

                $users_table = $tDataClass->prefix."_users";
                $sql['users'] = "SELECT * FROM `".$users_table."` WHERE `groups` LIKE '%".$group['alias']."%'";
                $qry['users'] = $tData->query($sql['users']);

                if ($qry['users']) {
                    $affected = $qry['users']->num_rows;
                    $affected = $affected == 1 ? $affected." user" : $affected." users";
                } else {
                    $error[] = "There was an issue querying the users database.";
                }
            } else {
                $error[] = "There was an error when finding the group requested.";
            }
        } else {
            $error[] = "There was an issue querying the database.";
        }
    } else {
        $error[] = "The ID provided isn't valid.";
    }
} else {
    $error[] = "There's no group ID defined.";
}

?>
<div class="window-header">
    <?php if (empty($error)): ?>
    Are you sure?
    <?php else: ?>
    Hmmm...
    <?php endif; ?>
</div>
<div class="window-content">
    <?php
    if (!empty($error)):
        notify("admin", "failure", $error[0]);
    ?>
    <div class="window-options">
        <input type="button" class="admin-purpbtn" onclick="close_remove_group();" value="Close" />
    </div>
    <?php else: ?>
    <input type="hidden" name="group_id" id="group_id" value="<?=$group['id']?>" />
    Are you sure you want to remove the group <b><?=$group['name']?></b>?
    <ul>
        <li>This will affect <?=$affected?>.</li>
    </ul>
    Removing a group cannot be undone.
    <div class="window-options">
        <input type="button" value="OK" onclick="return submit_remove_group();" class="admin-greenbtn" />
        <input type="button" class="admin-redbtn" onclick="close_remove_group();" value="Cancel" />
    </div>
    <?php endif; ?>
</div>