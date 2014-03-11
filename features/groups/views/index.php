<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/groups/img/home-groups.png" alt="" />
    </span>
	<div class="admin_content-header-text">User Groups</div>
</div>

<div id="user-result"></div>
<div class="admin_page-content">
	<form id="search-form" onsubmit="return search_groups();">
        <div class="admin-listsink">
            <div class="left" style="margin-top: 5px;">
                <div class="admin-listsearch">
                    <input type="text" class="longtext" id="search" name="search"
                        maxlength="300" autocomplete="off" onkeyup="return search_groups();"
                        placeholder="Start typing to search" />
                </div>
                <input type="submit" class="inline" value="Search" />
            </div>
            <div class="right">
                <input type="button" class="admin-purpbtn" onclick="admin_go('accounts', 'groups/create/')"
                    value="Create a New Group" />
            </div>
            <div class="clearfix"></div>
        </div>
    </form>

    <div class="group_windows">
        <div id="remove-window" class="group-window"></div>
    </div>
    <div id="remove_result"></div>
	<div class="admin-list" id="groups_list"></div>
</div>