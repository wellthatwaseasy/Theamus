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

$features_table = $tDataClass->prefix."_features";
$s = "SELECT * FROM `".$features_table."` WHERE "
        . "`name` LIKE '".$search."%' ORDER BY `name` ASC";

$template_header = <<<TEMPLATE
        <ul class="header">
            <li style="width: 175px;">Name</li>
            <li style="text-align:center; width: 200px;">Enabled</li>
        </ul>
TEMPLATE;

$template = <<<TEMPLATE
<ul>
    <li style="width: 175px;">
        ::strlen("%name%") >= 20 ? substr("%name%", 0, 20)."..." : "%name%"::
    <li>
    <li style="text-align:center; width: 200px;">::%enabled% == 1 ? "Yes" : "No"::<li>
    <li class="admin-listoptions">
        ::\$tUser->has_permission("edit_features") ? "<a href='#' onclick=\"return admin_go('features', 'features/edit&id=%id%');\">Edit</a>" : ""::
        ::\$tUser->has_permission("remove_features") && %permanent% == 0 ? "<a href='#' onclick=\"return remove_feature('%id%');\">Remove</a>" : ""::
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