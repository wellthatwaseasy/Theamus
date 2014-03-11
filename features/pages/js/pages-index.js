function load_pages() {
    $("#pages_list").html(working());
    theamus.ajax.run({
        url: "pages/pages-list/",
        result: "pages_list",
        type: "include"
    });
    return false;
}

function search_pages() {
    $("#pages_list").html(working());
    theamus.ajax.run({
        url: "pages/pages-list/&search=" + $("#search").val(),
        result: "pages_list",
        type: "include"
    });
    return false;
}

// Removes a page
function remove_page(id) {
    admin_scroll_top();
    $("#remove-window").show();
	$("#remove-window").html(working());
    theamus.ajax.run({
        url: "pages/remove-page&id=" + id,
        result: "remove-window",
        type: "include"
    });

	return false;
}

function close_remove_page() {
    $("#remove-window").html("");
    $("#remove-window").hide();

    return false;
}

function submit_remove_page() {
    $("#remove_result").html(working());
    theamus.ajax.run({
        url: "pages/remove/",
        result: "remove_result",
        extra_fields: ["page_id", "remove_links"],
        hide_result: 3,
        after: {
            do_function: ["close_remove_page", "search_pages"]
        }
    });

    return false;
}

function next_page(page) {
    $("#pages_list").html(working());
    theamus.ajax.run({
        url: "pages/pages-list/&search=" + $("#search").val() + "&page=" + page,
        result: "pages_list",
        type: "include"
    });
    return false;
}

$(document).ready(function() {
    load_pages();
});