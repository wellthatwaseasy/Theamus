<?php

// Define the incoming information
$get = filter_input_array(INPUT_GET);

// Define the search
$search = "";
if (isset($get['search'])) {
    $search = $get['search'];
}

// Define the page number
$page = 1;
if (isset($get['page'])) {
    $page = $get['page'];
}

// Define the template
$template_header = <<<TEMPLATE
        <ul class="header">
            <li style="width: 150px;">Username</li>
            <li style="width: 200px;">Full Name</li>
        </ul>
TEMPLATE;
$template = <<<TEMPLATE
<ul>
    <li class="admin-listoptions">
        ::\$tUser->has_permission("edit_users") ? "<a href='#' onclick=\"return admin_go('accounts', 'accounts/edit&id=%id%');\">Edit</a>" : ""::
        ::\$tUser->has_permission("remove_users") && %permanent% == 0 ? "<a href='#' onclick=\"return remove_user('%id%');\">Remove</a>" : ""::
    </li>
    <li style="width: 150px;">%username%</li>
    <li style="width: 200px;">::urldecode("%firstname% %lastname%")::</li>
</ul>
TEMPLATE;

// Query the database for users
$query_data = array(
    "table_name"    => $tData->prefix."_users",
    "clause"        => array(
        "operator"  => "OR",
        "conditions"=> array(
            "[%]username" => $search."%",
            "[`%]CONCAT(`firstname`, ' ', `lastname`)" => $search."%"
        )
    ));
$query = $tData->select_from_table($query_data['table_name'], array(), $query_data['clause']);

// Check the query
if ($query != false) {
    if ($tData->count_rows($query) > 0) {
        $results = $tData->fetch_rows($query);
        $users = isset($results[0]) ? $results : array($results);

        $tPages->set_page_data(array(
            "data" => $users,
            "per_page" => 25,
            "current" => $page,
            "list_template" => $template,
            "template_header" => $template_header
        ));

        $tPages->print_list();
        $tPages->print_pagination();
    } else {
        notify("admin", "info", "There are no users to display.");
    }
} else {
    notify("admin", "failure", "There was an error finding users in the database.");
}