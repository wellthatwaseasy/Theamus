<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/features/img/install-features.png" alt="" />
    </span>
    <div class="right" style="margin-top: -2px;">
        <input type="button" name="cancel" value="Go Back" />
    </div>
    <div class="admin_content-header-text">Install a New Feature</div>
</div>

<div class="admin_page-content">
    <div id="install-result"></div>
    <form class="admin-form" id="feature_install-form">
        <div class="admin-formrow">
            <div class="admin-formlabel">Feature Files</div>
            <div class="admin-forminput">
                <input type="file" name="file" />
            </div>
            <div class="admin-forminfo">
                Features should come in the form of zip archives.<br />
                Select the feature you want to install and everything will be handled
                automatically from there.
            </div>
        </div>

        <div id="feature_prelim-info-wrapper" style="display: none; margin-top: 50px;">
            <div class="admin-formheader">Preliminary Installation Information</div>
            <div id="prelim-notes"></div>
        </div>

        <hr />

        <div class="admin-formsubmitrow">
            <input type="submit" value="Install" id="feature_install-button" class="admin-greenbtn" />
            <input type="button" value="Cancel" name="cancel" class="admin-redbtn" />
        </div>
    </form>
</div>