<?php

function update_05() {
    $tDataClass = new tData();
    $tData = $tDataClass->Connect();
    $tDataClass->Prefix = $tDataClass->GetTablePrefix();

    $perm_table = $tDataClass->Prefix."_permissions";
    $feature_table = $tDataClass->Prefix."_features";
    $group_table = $tDataClass->Prefix."_groups";

    $sql['permissions'] = "INSERT INTO `$perm_table` (`feature`, `permission`) VALUES ".
        "('media', 'add_media'), ".
        "('media', 'remove_media');";

    $sql['feature'] = "INSERT INTO `$feature_table` ".
        "(`alias`, `name`, `groups`, `permanent`, `enabled`, `db_prefix`) VALUES ".
        "('media', 'Media', 'administrators', 1, 1, 'tm_');";


    $tsql['find-group'] = "SELECT `permissions` FROM `$group_table` WHERE `alias`='administrators'";
    $tqry['find-group'] = $tData->query($tsql['find-group']);
    $admin_group = $tqry['find-group']->fetch_assoc();

    $sql['group'] = "UPDATE `$group_table` SET ".
        "`permissions`='".$admin_group['permissions'].",add_media,remove_media' WHERE ".
        "`alias`='administrator`'";

    foreach ($sql as $s) {
        $tData->query($s);
    }

    $tDataClass->Disconnect();
    update_06();
}

function update_06() {
    unlink(path(ROOT."/system/install.php"));
    unlink(path(ROOT."/system/functions.php"));
    unlink(path(ROOT."/system/external/rangy.js"));
    unlink(path(ROOT."/system/js/ajax/ajax_old.js"));
    unlink(path(ROOT."/system/js/ajax/readme.txt"));
}

function cleanup() {
    deleteFolder(path(ROOT."/themes/installer/"));
    deleteFolder(path(ROOT."/features/install/"));
    deleteFolder(path(ROOT."/update/"));
}