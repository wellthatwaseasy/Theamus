<?php

$get = filter_input_array(INPUT_GET);

if (isset($get['id'])) {
    $id = $get['id'];
    if (is_numeric($id)) {
        $users_table = $tDataClass->prefix."_users";
        $sql['user'] = "SELECT * FROM `".$users_table."` WHERE `id`='".$id."'";
        $qry['user'] = $tData->query($sql['user']);

        if ($qry['user']) {
            if ($qry['user']->num_rows > 0) {
                $user = $qry['user']->fetch_assoc();
            } else {
                $error[] = "There was an error when finding the user requested.";
            }
        } else {
            $error[] = "There was an issue querying the database.";
        }
    } else {
        $error[] = "The ID provided isn't valid.";
    }
} else {
    $error[] = "There's no user ID defined.";
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
        <input type="button" class="admin-purpbtn" onclick="close_remove_user();" value="Close" />
    </div>
    <?php else: ?>
    <input type="hidden" name="user_id" id="user_id" value="<?=$user['id']?>" />
    Are you sure you want to remove the user <b><?=$user['username']?></b>?
    <br/><br/>Removing a user cannot be undone.
    <div class="window-options">
        <input type="button" value="OK" onclick="return submit_remove_user();" class="admin-greenbtn" />
        <input type="button" class="admin-redbtn" onclick="close_remove_user();" value="Cancel" />
    </div>
    <?php endif; ?>
</div>