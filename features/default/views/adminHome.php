<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/default/img/admin-home.png" alt="" />
    </span>
	<div class="admin_content-header-text">Administration Home</div>
</div>

<div class="admin_page-content">
    <div class="admin-home-sink">
        <input type="button" value="Customize Admin Home" onclick="return open_home_prefs();" />
        <div id="choice-window"></div>
        <div id="adminhome-result" class="inline"></div>
        <div id="notify" class="inline"></div>
    </div>
    <div id="apps">
        <?=$this->include_file("admin/apps")?>
    </div>
</div>