function get_homepage() {
    if ($('#type').val() === 'nooverride') {
        var ret = 'false';
    } else {
        var type = $("#type").val(),
            elements = $("#"+type+" :input"),
            ret = "{t:homepage;type=\""+type+"\";";

            if (type === "page") ret += "id=\""+elements[0].value+"\";";
            if (type === "custom") ret += "url=\""+elements[0].value+"\";";
            if (type === "feature") {
                ret += "id=\""+elements[0].value+"\";";
                ret += "file=\""+elements[1].value+"\";";
            }

            ret += ":}";
    }

    return ret;
}

function save_group() {
    admin_scroll_top();
    $("#group-result").html(working());
    var home = get_homepage();
    theamus.ajax.run({
        url: "groups/save/&home=" + home,
        result: "group-result",
        form: "group-form"
    });

    return false;
}

function switch_type(to) {
    var type = $('#type')[0];
    var old = type.value;
    type.value = to;

    $('#' + old).hide();
    $('#' + to).show();

    return false;
}

function update_features(feature) {
    theamus.ajax.run({
       url: 'settings/feature-file-list/&id=' + feature,
       result: 'feature-file-list'
    });

    return false;
}

function back_to_grouplist() {
	countdown("Back to groups list in", 3);
    setTimeout(function() {
        admin_go("accounts", "groups/");
    }, 3000);
}


function create_group() {
    admin_scroll_top();
    $("#group-result").html(working());
    theamus.ajax.run({
        url: "groups/create/",
        result: "group-result",
        form: "group-form"
    });

    return false;
}