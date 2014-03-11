function save_theme() {
    $("#edit-result").html(working());
    theamus.ajax.run({
        url: "appearance/save/",
        result: "edit-result",
        form: "edit-form"
    });
}

function load_theme_settings() {
    $("#theme_settings").html(working());
    theamus.ajax.run({
        url:    "appearance/settings-tabs?id="+$("[name='id']").val(),
        result: "theme_settings",
        type:   "include",
        after:  initial_settings_page
    });
    return false;
}

function change_current_tab(file) {
    var tabs = $("[name='theme_settings-tab']");
    for (var i = 0; i < tabs.length; i++) {
        var tab = $(tabs[i]);
        if (tab.data("path") === file) tab.parent("li").addClass("current");
        else tab.parent("li").removeClass("current");
    }
}

function initial_settings_page() {
    var tabs = $("[name='theme_settings-tab']");
    load_settings_page($(tabs[0]).data("path"));
}

function load_settings_page(file) {
    var ele = $("#theme_settings-contents");
    
    if (ele.length > 0) {
        ele.html(working());
        theamus.ajax.run({
            url:   "appearance/settings-page&file="+file+"&id="+$("[name='id']").val(),
            result: "theme_settings-contents",
            type:   "include",
            after:  function() {
                change_current_tab(file);
                add_tab_listeners();
            }
        });
    }
    return false;
}

function save_theme(f, ext, after) {
    try { if (!f) throw "Call function not defined."; }
    catch (e) { console.log("Theme Save Error: "+e); return; }

    if (!ext) ext = [];
    if (!after) after = function(){};

    $("#appearance_edit-form").unbind("submit");
    $("#appearance_edit-form").submit(function(e) {
        e.preventDefault();

        admin_scroll_top();
        $("#appearance_edit-result").html(working());
        theamus.ajax.run({
            url:            "appearance/save&f="+f,
            result:         "appearance_edit-result",
            form:           "appearance_edit-form",
            extra_fields:   ext,
            after:          function() {
                                after();
                            }
        });
    });
}

function add_tab_listeners() {
    $("[name='theme_settings-tab']").click(function(e) {
        e.preventDefault();
        load_settings_page($(this).data("path"));
    });
}

function upload_listen() {
    $("[name='file']").change(function(e) {
        $("#appearance_update-result").html(working());
        theamus.ajax.run({
            url:    "appearance/manual-update/",
            result: "appearance_update-result",
            form:   "appearance_edit-form",
            after:  function() {
                $("[name='file']").replaceWith($("[name='file']").clone(true));
            }
        });
    });
}


$(document).ready(function() {
    $("[name='cancel']").click(function() {
        admin_go("settings", "appearance/");
    });

    load_theme_settings();
    upload_listen();
});