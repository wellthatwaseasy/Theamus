<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/appearance/img/home-themes.png" alt="" />
    </span>
	<div class="admin_content-header-text">Site Themes</div>
</div>

<div class="admin_page-content">
	<form id="search-form" onsubmit="return search_themes();">
        <div class="admin-listsink">
            <div class="left" style="margin-top: 5px;">
                <div class="admin-listsearch">
                    <input type="text" class="longtext" id="search" name="search"
                        maxlength="300" autocomplete="off" onkeyup="return search_themes();"
                        placeholder="Start typing to search" />
                </div>
                <input type="submit" class="inline" value="Search" />
            </div>
            <div class="right">
                <input type="button" class="admin-purpbtn" onclick="admin_go('settings', 'appearance/install/')"
                    value="Install a New Theme" />
            </div>
            <div class="clearfix"></div>
        </div>
    </form>

    <div class="theme_windows">
        <div id="remove-window" class="theme-window"></div>
    </div>
    <div id="remove_result"></div>

    <div id="appearance_index-result"></div>
	<div class="admin-list" id="themes_list" style="min-width:1050px;"></div>
</div>