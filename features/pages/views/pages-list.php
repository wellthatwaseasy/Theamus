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

$pages_table = $tDataClass->prefix."_pages";
$s = "SELECT * FROM `".$pages_table."` WHERE "
        . "`title` LIKE '".$search."%'";

$template_header = <<<TEMPLATE
        <ul class="header">
            <li style="width: 150px;">Title</li>
            <li style="width: 200px;">Views</li>
        </ul>
TEMPLATE;

$template = <<<TEMPLATE
<ul>
    <li style="width: 150px;">%title%<li>
    <li style="width: 200px;">%views%<li>
    <li class="admin-listoptions">
        ::\$tUser->has_permission("edit_pages") ? "<a href='#' onclick=\"return admin_go('pages', 'pages/edit&id=%id%');\">Edit</a>" : ""::
        ::\$tUser->has_permission("remove_pages") && %permanent% == 0 ? "<a href='#' onclick=\"return remove_page('%id%');\">Remove</a>" : ""::
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