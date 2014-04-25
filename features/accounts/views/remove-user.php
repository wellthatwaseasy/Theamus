<?php

$get = filter_input_array(INPUT_GET);
$query_data = array("table_name" => $tData->prefix."_users");

if (isset($get['id'])) {
    $id = $get['id'];
    if (is_numeric($id)) {
        $query_user = $tData->select_from_table($query_data['table_name'], array("username"), array("operator" => "", "conditions" => array("id" => $id)));

        if ($query_user != false) {
            if ($tData->count_rows($query_user) > 0) {
                $user = $tData->fetch_rows($query_user);
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
    <?php echo empty($error) ? "Are you sure?" : "Hmmm..."; ?>
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
    <input type="hidden" name="user_id" id="user_id" value="<?=$id?>" />
    Are you sure you want to remove the user <b><?=$user['username']?></b>?
    <br/><br/>Removing a user cannot be undone.
    <div class="window-options">
        <input type="button" value="OK" onclick="return submit_remove_user();" class="admin-greenbtn" />
        <input type="button" class="admin-redbtn" onclick="close_remove_user();" value="Cancel" />
    </div>
    <?php endif; ?>
</div>