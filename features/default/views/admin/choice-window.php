<div class="window-header">Home Page Preferences</div>
<div class="window-content">
    <div id="home-result"></div>
    <form class="admin-form" id="home-form" onsubmit="return save_home();">
    <?php
    $apps = true;
    $query = $tData->select_from_table("dflt_home-apps", array("active", "path", "name"));

    if ($query != false) {
        if ($tData->count_rows($query) > 0) {
        ?>
        <ul>
        <?php
        $results = $tData->fetch_rows($query);
        foreach ($results as $app):
            $checked = $app['active'] == 1 ? "checked" : "";
        ?>
            <li>
                <div class="admin-cboxwrapper">
                    <input type="checkbox" class="admin-switchcbox" name="homeapp"
                      id="<?=$app['path']?>" <?=$checked?>>
                    <label class="admin-switchlabel of" for="<?=$app['path']?>">
                      <span class="admin-switchinner"></span>
                      <span class="admin-switchswitch"></span>
                    </label>
                </div>
                <span><?=$app['name']?></span>
                <div class="clearfix"></div>
            </li>
        <?php endforeach; ?>
        </ul>
        <?php
        } else {
            $apps = false;
            alert_notify("info", "You have no home apps!");
        }
    } else {
        $apps = false;
        alert_notify("info", "There was an error querying the database.");
    }
    ?>
        <div class="options-row">
            <?php if ($apps === true): ?>
            <input type="submit" value="Save" class="admin-greenbtn">
            <?php endif; ?>
            <input type="button" value="Cancel" onclick="close_home_prefs();" class="admin-redbtn">
        </div>
    </form>
</div>