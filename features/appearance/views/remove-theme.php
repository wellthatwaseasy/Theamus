<?php

$get = filter_input_array(INPUT_GET);

if (isset($get['id'])) {
    $id = $get['id'];
    if (is_numeric($id)) {
        $themes_table = $tDataClass->prefix."_themes";
        $sql['theme'] = "SELECT * FROM `".$themes_table."` WHERE `id`='".$id."'";
        $qry['theme'] = $tData->query($sql['theme']);

        if ($qry['theme']) {
            if ($qry['theme']->num_rows > 0) {
                $theme = $qry['theme']->fetch_assoc();
            } else {
                $error[] = "There was an error when finding the theme requested.";
            }
        } else {
            $error[] = "There was an issue querying the database.";
        }
    } else {
        $error[] = "The ID provided isn't valid.";
    }
} else {
    $error[] = "There's no theme ID defined.";
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
        <input type="button" class="admin-purpbtn" onclick="close_remove_theme();" value="Close" />
    </div>
    <?php else: ?>
    <input type="hidden" name="theme_id" id="theme_id" value="<?=$theme['id']?>" />
    Are you sure you want to remove the theme <b><?=$theme['name']?></b>?
    <br/><br/>Removing a theme cannot be undone.
    <div class="window-options">
        <input type="button" value="OK" onclick="return submit_remove_theme();" class="admin-greenbtn" />
        <input type="button" class="admin-redbtn" onclick="close_remove_theme();" value="Cancel" />
    </div>
    <?php endif; ?>
</div>