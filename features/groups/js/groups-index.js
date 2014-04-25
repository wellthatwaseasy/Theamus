function load_groups() {
    $("#groups_list").html(working());
    theamus.ajax.run({
        url: "groups/groups-list/",
        result: "groups_list",
        type: "include"
    });

    return false;
}

function search_groups() {
    $("#groups_list").html(working());
    theamus.ajax.run({
        url: "groups/groups-list/&search=" + $("#search").val(),
        result: "groups_list",
        type: "include"
    });
    return false;
}

function remove_group(id) {
    admin_scroll_top();
    $("#remove-window").show();
	$("#remove-window").html(working());
    theamus.ajax.run({
        url: "groups/remove-group&id=" + id,
        result: "remove-window",
        type: "include"
    });

	return false;
}

function close_remove_group() {
    $("#remove-window").html("");
    $("#remove-window").hide();

    return false;
}

function submit_remove_group() {
    $("#remove_result").html(working());
    theamus.ajax.run({
        url: "groups/remove/",
        result: "remove_result",
        extra_fields: "group_id",
        after: {
            do_function: ["close_remove_group", "search_groups"]
        }
    });

    return false;
}

function groups_next_page(page) {
    $("#groups_list").html(working());
    theamus.ajax.run({
        url:    "groups/groups-list&search="+ $("#search").val()+"&page="+page,
        result: "groups_list",
        type:   "include"
    });
    return false;
}

$(document).ready(function() {
    load_groups();
});