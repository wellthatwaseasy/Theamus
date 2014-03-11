<?php

$get = filter_input_array(INPUT_GET);

$search = "";
if (isset($get['search'])) {
    $search = $tData->real_escape_string($get['search']);
}

$page = 1;
if (isset($get['page'])) {
    $page = $tData->real_escape_string($get['page']);
}

$themes_table = $tDataClass->prefix."_themes";
$s = "SELECT * FROM `".$themes_table."` WHERE "
        . "`alias` LIKE '".$search."%' || "
        . "`name` LIKE '".$search."%'";

$template_header = <<<TEMPLATE
        <ul class="header">
            <li style="width: 200px;">Name</li>
            <li style="width: 200px;">Folder</li>
            <li style="width: 100px; text-align: center;">Active</li>
        </ul>
TEMPLATE;

$template = <<<TEMPLATE
<ul>
    <li style="width: 200px;">::strlen("%name%") > 25 ? substr("%name%", 0, 25)."..." : "%name%"::<li>
    <li style="width: 200px;">%alias%<li>
    <li style="width: 100px; text-align: center;">::%active% > 0 ? "Yes" : "No"::</li>
    <li class="admin-listoptions">
        ::\$tUser->has_permission("edit_themes") ? "<a href='#' onclick=\"return admin_go('settings', 'appearance/edit&id=%id%');\">Edit</a>" : ""::
        ::\$tUser->has_permission("edit_themes") && %active% != 1 ? "<a href='#' name='make-active' data-id='%id%'>Make Active</a>" : ""::
        ::\$tUser->has_permission("remove_themes") && %permanent% == 0 ? "<a href='#' onclick=\"return remove_theme('%id%');\">Remove</a>" : ""::
    </li>
</ul>
TEMPLATE;

$tPages->set_page_data(array(
    "sql" => $s,
    "per_page" => 25,
    "current" => $page,
    "list_template" => $template,
    "template_header" => $template_header
));

$tPages->print_list();
$tPages->print_pagination();