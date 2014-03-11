function load_features() {
    $("#feature-list").html(working());
    theamus.ajax.run({
        url: "features/features-list/",
        result: "feature-list",
        type: "include"
    });
    return false;
}

function search_features() {
    $("#feature-list").html(working());
    theamus.ajax.run({
        url: "features/features-list/&search=" + $("#search").val(),
        result: "feature-list",
        type: "include"
    });
    return false;
}

// Removes a feature
function remove_feature(id) {
    admin_scroll_top();
    $("#remove-window").show();
	$("#remove-window").html(working());
    theamus.ajax.run({
        url: "features/remove-feature&id=" + id,
        result: "remove-window",
        type: "include"
    });

	return false;
}

function close_remove_feature() {
    $("#remove-window").html("");
    $("#remove-window").hide();

    return false;
}

function submit_remove_feature() {
    $("#remove_result").html(working());
    theamus.ajax.run({
        url: "features/remove/",
        result: "remove_result",
        extra_fields: "feature_id",
        hide_result: 3,
        after: {
            do_function: ["close_remove_feature", "search_features"]
        }
    });

    return false;
}

function next_page(page) {
    $("#users_list").html(working());
    theamus.ajax.run({
        url:    "features/features-list/&search=" + $("#search").val() + "&page=" + page,
        result: "feature-list",
        type:   "include"
    });
    return false;
}

$(document).ready(function() {
    load_features();
});