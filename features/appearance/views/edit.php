<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/appearance/img/edit-themes.png" alt="" />
    </span>
    <div class="right" style="margin-top: -2px;">
        <input type="button" name="cancel" value="Go Back" />
    </div>
	<div class="admin_content-header-text">Edit Theme</div>
</div>

<div class="admin_page-content">
    <div id="appearance_edit-result"></div>
    <form class="admin-form" id="appearance_edit-form">
        <?php
        try { $info = $Appearance->get_theme_info(); }
        catch (Exception $ex) { $Appearance->print_exception($ex); }
        ?>
        <div class="admin-formheader">Manual Update</div>
        <div class="admin-formrow">
            <div class="admin-formlabel">Select File</div>
            <div class="admin-forminput">
                <input type="file" name="file" />
            </div>
            <div class="admin-forminfo" id="appearance_update-result"></div>
        </div>

        <div class="admin-formheader">Theme Settings</div>
        <div id="theme_settings"></div>
        <hr />
        <div class="admin-formsubmitrow">
            <input type="hidden" name="id" value="<?=$info['id']?>" />
            <input type="submit" value="Save" class="admin-greenbtn" />
            <input type="button" value="Cancel" class="admin-redbtn" name="cancel" />
        </div>
    </form>
</div>