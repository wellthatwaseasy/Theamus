function load_themes() {
    $("#themes_list").html(working());
    theamus.ajax.run({
        url:    "appearance/themes-list/",
        result: "themes_list",
        type:   "include",
        after:  function() {
            active_listeners();
        }
    });
    return false;
}

function active_listeners() {
    $("[name='make-active']").click(function(e) {
        e.preventDefault();
        theamus.ajax.run({
            url:            "appearance/set-active&id="+$(this).data("id"),
            result:         "appearance_index-result",
            after:          load_themes,
            hide_result:    3
        });
    });
}

function search_themes() {
    $("#themes_list").html(working());
    theamus.ajax.run({
        url: "appearance/themes-list/&search=" + $("#search").val(),
        result: "themes_list",
        type: "include"
    });
    return false;
}

// Removes a theme
function remove_theme(id) {
    admin_scroll_top();
    $("#remove-window").show();
    $("#remove-window").html(working());
    theamus.ajax.run({
        url: "appearance/remove-theme&id=" + id,
        result: "remove-window",
        type: "include"
    });

    return false;
}

function close_remove_theme() {
    $("#remove-window").html("");
    $("#remove-window").hide();

    return false;
}

function submit_remove_theme() {
    $("#remove_result").html(working());
    theamus.ajax.run({
        url: "appearance/remove/",
        result: "appearance_index-result",
        extra_fields: "theme_id",
        hide_result: 3,
        after: function() {
            $("#remove_result").html("");
            close_remove_theme();
            search_themes();
        }
    });

    return false;
}

function next_page(page) {
    $("#themes_list").html(working());
    theamus.ajax.run({
        url: "appearance/themes-list&search=" + $("#search").val() + "&page=" + page,
        result: "themes_list",
        type: "include"
    });
    return false;
}

$(document).ready(function() {
    load_themes();
});