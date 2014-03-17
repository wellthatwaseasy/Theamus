<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/settings/img/edit-settings.png" alt="" />
    </span>
    <div class="right" style="margin-top: -2px;">
        <input type="button" name="cancel" value="Go Back" />
    </div>
	<div class="admin_content-header-text">Manual Theamus Update</div>
</div>

<div class="admin_page-content">
    <form class="admin-form" id="settings_update-form">
        <div id="update-result"></div>
        <div class="admin-formrow">
            <div class="admin-formlabel">System Files</div>
            <div class="admin-forminput"><input type="file" name="file" /></div>
            <div class="admin-forminfo">
                All of the files should be in the root of a compressed zip file.<br />
                e.g. theamus-update.zip/features - <b>not</b> theamus-update.zip/theamus/features
            </div>
        </div>

        <div id="settings_prelim-info-wrapper" style="display: none; margin-top: 50px;">
            <div class="admin-formheader">Preliminary Update Information</div>
            <div id="prelim-notes"></div>
        </div>

        <hr />

        <div class="admin-formsubmitrow">
            <input type="submit" value="Update" class="admin-greenbtn" />
            <input type="button" value="Cancel" class="admin-redbtn" name="cancel" />
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        function add_add_listeners() {
            if (typeof add_manual_listeners === "undefined") {
                setTimeout(function() { add_add_listeners(); }, 50);
            } else { add_manual_listeners(); }
        }
        add_add_listeners();
    });
</script>