<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/features/img/home-features.png" alt="" />
    </span>
	<div class="admin_content-header-text">Features</div>
</div>

<div id="nav-result"></div>
<div class="admin_page-content">
	<form id="search-form" onsubmit="return search_features();">
        <div class="admin-listsink">
            <div class="left" style="margin-top: 5px;">
                <div class="admin-listsearch">
                    <input type="text" class="longtext" id="search" name="search"
                        maxlength="300" autocomplete="off" onkeyup="return search_features();"
                        placeholder="Start typing to search" />
                </div>
                <input type="submit" class="inline" value="Search" />
            </div>
            <div class="right">
                <input type="button" class="admin-purpbtn" onclick="admin_go('features', 'features/install/')"
                    value="Install a New Feature" />
            </div>
            <div class="clearfix"></div>
        </div>
    </form>

    <div class="feature_windows">
        <div id="remove-window" class="feature-window"></div>
    </div>
    <div id="remove_result"></div>
	<div class="admin-list" id="feature-list" style="min-width:700px;"></div>
</div>