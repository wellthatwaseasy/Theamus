function load_groups_select() {
    theamus.ajax.run({
        url:    "features/selects/groups/&groups="+$("#groups").val(),
        result: "group-select",
        type:   "include"
    });
}

function save_feature() {
    $("#edit-result").html(working());
    theamus.ajax.run({
        url:    "features/save/",
        result: "edit-result",
        form:   "edit-form",
        after:  function() {
            $("[name='file']").attr("disabled", true);
        }
    });
}

$(document).ready(function() {
    $("[name='cancel']").click(function(e) {
        e.preventDefault();
        admin_go("features", "features/");
    });

    $("#edit-form").submit(function(e) {
        e.preventDefault();
        save_feature();
    });

    load_groups_select();
});