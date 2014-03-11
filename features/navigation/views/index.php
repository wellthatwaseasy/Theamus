<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/navigation/img/home-nav.png" alt="" />
    </span>
	<div class="admin_content-header-text">Site Navigation</div>
</div>

<div id="nav-result"></div>
<div class="admin_page-content">
	<form id="search-form" onsubmit="return search_nav();">
        <div class="admin-listsink">
            <div class="left" style="margin-top: 5px;">
                <div class="admin-listsearch">
                    <input type="text" class="longtext" id="search" name="search"
                        maxlength="300" autocomplete="off" onkeyup="return search_nav();"
                        placeholder="Start typing to search" />
                </div>
                <input type="submit" class="inline" value="Search" />
            </div>
            <div class="right">
                <input type="button" class="admin-purpbtn" onclick="admin_go('pages', 'navigation/create/')"
                    value="Create a New Link" />
            </div>
            <div class="clearfix"></div>
        </div>
    </form>

    <div class="navigation_windows">
        <div id="remove-window" class="navigation-window"></div>
    </div>
    <div id="remove_result"></div>
	<div class="admin-list" id="navigation-list" style="min-width:1050px;"></div>
</div>