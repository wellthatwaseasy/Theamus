<div class="window-header">Home Page Preferences</div>
<div class="window-content">
    <div id="home-result"></div>
    <form class="admin-form" id="home-form" onsubmit="return save_home();">
    <?php
    $apps = true;
    $sql['find'] = "SELECT * FROM `dflt_home-apps`";
    $qry['find'] = $tData->query($sql['find']);

    if ($qry['find']) {
        if ($qry['find']->num_rows > 0) {
        ?>
        <ul>
        <?php
        while ($app = $qry['find']->fetch_assoc()):
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
        <?php endwhile; ?>
        </ul>
        <?php
        } else {
            $apps = false;
            notify("admin", "info", "You have no home apps!");
        }
    } else {
        $apps = false;
        notify("admin", "info", "There was an error querying the database.");
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