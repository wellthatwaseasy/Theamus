<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/appearance/img/add-themes.png" alt="" />
    </span>
    <div class="right" style="margin-top: -2px;">
        <input type="button" name="cancel" value="Go Back" />
    </div>
    <div class="admin_content-header-text">Install a New Theme</div>
</div>

<div class="admin_page-content">
    <form class="admin-form" id="appearance_install-form">
        <div id="upload-result"></div>
        <div class="admin-formrow">
            <div class="admin-formlabel">Theme Files</div>
            <div class="admin-forminput"><input type="file" name="file" /></div>
            <div class="admin-forminfo">
                Themes should come in the form of zip archives.<br />
                Select the theme you want to install and everything will be handled
                automatically from there.
            </div>
        </div>
        
        <div id="appearance_prelim-info-wrapper" style="display: none; margin-top: 50px;">
            <div class="admin-formheader">Preliminary Installation Information</div>
            <div id="prelim-notes"></div>
        </div>

        <hr />

        <div class="admin-formsubmitrow">
            <input type="submit" value="Install" class="admin-greenbtn" />
            <input type="button" value="Cancel" class="admin-redbtn" name="cancel" />
        </div>
    </form>
</div>