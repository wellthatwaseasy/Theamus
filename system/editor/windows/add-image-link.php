<form class="editor_link-form" style="margin-top: 20px;">
    <div class="editor_link-form-row">
        <div class="editor_link-form-label">Image URL</div>
        <div class="editor_link-form-input">
            <input type="text" id="image-url" />
        </div>
    </div>
</form>
<script>
    document.getElementById("image-url").focus();
    if ($("#add-image-url").length === 0) $(".editor_window-submit").prepend("<input type='submit' value='Add' class='site-greenbtn' id='add-image-url' />");
    $("#image-url").keyup(function() {
        $("#add-image-url")[0].dataset['path'] = $(this).val();
    });
    editor.add_event_listener({
        element: $("#add-image-url")[0],
        action: "click",
        "do": function() {
            editor.create_img(this);
            editor.close_window(this.element);
        }
    });
</script>
