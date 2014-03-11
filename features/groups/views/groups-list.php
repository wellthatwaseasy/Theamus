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

$groups_table = $tDataClass->prefix."_groups";
$s = "SELECT * FROM `".$groups_table."` WHERE "
        . "`alias` LIKE '".$search."%' || "
        . "`name` LIKE '".$search."%'";

$template_header = <<<TEMPLATE
        <ul class="header">
            <li style="width: 150px;">Group Alias</li>
            <li style="width: 200px;">Group Name</li>
        </ul>
TEMPLATE;

$template = <<<TEMPLATE
<ul>
    <li style="width: 150px;">%alias%<li>
    <li style="width: 200px;">%name%<li>
    <li class="admin-listoptions">
        ::\$tUser->has_permission("edit_groups") ? "<a href='#' onclick=\"return admin_go('accounts', 'groups/edit&id=%id%');\">Edit</a>" : ""::
        ::\$tUser->has_permission("remove_groups") && %permanent% == 0 ? "<a href='#' onclick=\"return remove_group('%id%');\">Remove</a>" : ""::
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
$tPages->print_pagination("groups_next_page");