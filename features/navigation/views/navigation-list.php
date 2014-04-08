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

$links_table = $tDataClass->prefix."_links";
$s = "SELECT * FROM `".$links_table."` WHERE "
        . "`text` LIKE '".$search."%' || "
        . "`path` LIKE '".$search."%'";

$template_header = <<<TEMPLATE
        <ul class="header">
            <li style="width: 175px;">Text</li>
            <li class="path" >Path</li>
            <li class="affiliated-groups" style="width: 300px;">Affiliated Groups</li>
        </ul>
TEMPLATE;

$template = <<<TEMPLATE
<ul>
    <li class="admin-listoptions">
        ::\$tUser->has_permission("edit_links") ? "<a href='#' onclick=\"return admin_go('pages', 'navigation/edit&id=%id%');\">Edit</a>" : ""::
        ::\$tUser->has_permission("remove_links") ? "<a href='#' onclick=\"return remove_link('%id%');\">Remove</a>" : ""::
    </li>
    <li style="width: 175px;">
        ::strlen("%text%") >= 20 ? substr("%text%", 0, 20)."..." : "%text%"::
    </li>
    <li class="path">%path%</li>
    <li class="affiliated-groups" style="width: 300px;" title="::ucwords(str_replace(',', ', ', str_replace('_', ' ', '%groups%')))::">::count(explode(",", "%groups%"))::</li>
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