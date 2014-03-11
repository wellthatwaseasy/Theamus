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

$users_table = $tDataClass->prefix."_users";
$s = "SELECT * FROM `".$users_table."` WHERE "
        . "`username` LIKE '".$search."%' || "
        . "CONCAT(`firstname`, ' ', `lastname`) LIKE '".$search."%' || "
        . "`email` LIKE '".$search."%' ||"
        . "`phone` LIKE '".$search."%'";

$template_header = <<<TEMPLATE
        <ul class="header">
            <li style="width: 150px;">Username</li>
            <li style="width: 200px;">Full Name</li>
            <li style="width: 175px; text-align: center;">Administrator</li>
            <li style="width: 100px; text-align: center;">Active User</li>
            <li>Birthday</li>
        </ul>
TEMPLATE;

$template = <<<TEMPLATE
<ul>
    <li style="width: 150px;">%username%<li>
    <li style="width: 200px;">::urldecode("%firstname% %lastname%")::<li>
    <li style="width: 100px; text-align: center;">::%admin% > 0 ? "Yes" : "No"::</li>
    <li style="width: 100px; text-align: center;">::%active% > 0 ? "Yes" : "No"::</li>
    <li>::date('l, F jS, Y', strtotime('%birthday%'))::</li>
    <li class="admin-listoptions">
        ::\$tUser->has_permission("edit_users") ? "<a href='#' onclick=\"return admin_go('accounts', 'accounts/edit&id=%id%');\">Edit</a>" : ""::
        ::\$tUser->has_permission("remove_users") && %permanent% == 0 ? "<a href='#' onclick=\"return remove_user('%id%');\">Remove</a>" : ""::
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