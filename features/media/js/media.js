function load_media_list() {
    $("#media_list").html(working());
    theamus.ajax.run({
        url:    "media/media-list/",
        result: "media_list",
        type:   "include"
    });
}

function refresh_media_list() {
    var page = $("#current_page").val();
    $("#media_list").html(working());
    theamus.ajax.run({
        url:    "media/media-list/&page=" + page,
        result: "media_list",
        type:   "include"
    });
}

function add_media() {
    admin_scroll_top();
    $("#add_window").show();
	$("#add_window").html(working());
    theamus.ajax.run({
        url:    "media/add-media",
        result: "add_window",
        type:   "include",
        after:  {
            do_function: "dnd_listen"
        }
    });

	return false;
}

function close_add_media() {
    $("#add_window").html("");
    $("#add_window").hide();

    files = new Array;

    return false;
}

function remove_media(id) {
    theamus.ajax.run({
        url:    "media/remove-media/&id="+id,
        result: "media-result",
        after:  {
            do_function: "refresh_media_list"
        },
        hide_result: 3
    });

    return false;
}

function next_page(page) {
    $("#users_list").html(working());
    theamus.ajax.run({
        url: "media/media-list/&page=" + page,
        result: "media_list",
        type: "include"
    });
    return false;
}

$(document).ready(function() {
    load_media_list();

    $("#add_media").click(function() {
        add_media();
    });
});