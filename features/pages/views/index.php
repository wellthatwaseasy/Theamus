<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/pages/img/home-pages.png" alt="" />
    </span>
	<div class="admin_content-header-text">Pages</div>
</div>

<div id="user-result"></div>
<div class="admin_page-content">
	<form id="search-form" onsubmit="return search_pages();">
        <div class="admin-listsink">
            <div class="left" style="margin-top: 5px;">
                <div class="admin-listsearch">
                    <input type="text" class="longtext" id="search" name="search"
                        maxlength="300" autocomplete="off" onkeyup="return search_pages();"
                        placeholder="Start typing to search" />
                </div>
                <input type="submit" class="inline" value="Search" />
            </div>
            <div class="right">
                <input type="button" class="admin-purpbtn" onclick="admin_go('pages', 'pages/create/')"
                    value="Create a New Page" />
            </div>
            <div class="clearfix"></div>
        </div>
    </form>

    <div class="page_windows">
        <div id="remove-window" class="pages-window"></div>
    </div>
    <div id="remove_result"></div>
	<div class="admin-list" id="pages_list" style="min-width:700px;"></div>
</div>