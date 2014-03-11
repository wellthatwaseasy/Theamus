<?php

function add_homeapp_js($path, $homeapp) {
    if (isset($homeapp['js']) && is_array($homeapp['js'])) {
        foreach ($homeapp['js'] as $js) {
            if (file_exists($path."/".$js)) {
                add_js($path."/".$js);
            }
        }
    } else {
        return false;
    }
}


$user = $tUser->user;

$sql['col1'] = "SELECT * FROM `dflt_home-apps` WHERE `active`='1' && `column`='1' ORDER BY `position` ASC";
$qry['col1'] = $tData->query($sql['col1']);

$sql['col2'] = "SELECT * FROM `dflt_home-apps` WHERE `active`='1' && `column`='2' ORDER BY `position` ASC";
$qry['col2'] = $tData->query($sql['col2']);

if ($user != false) {
    if ($qry['col1'] && $qry['col2']) {
        $base_path = path(ROOT."/features/default/home-apps/");
        if ($qry['col1']->num_rows > 0 || $qry['col2']->num_rows > 0) {
            $x = 0;
?>
<ul class="col-half left" id="column1">
<?php
            while ($app = $qry['col1']->fetch_assoc()):
                if (is_dir($base_path.$app['path'])) {
                    $path = $base_path.$app['path'];
                    $web_path = "features/default/home-apps/".$app['path'];
                    include $path."/config.php";
                    $x++;
?>
    <li id="<?=$app['path']?>=<?=$x?>">
        <div class="admin_container" draggable="true">
            <div class="admin_container-header handle"><?=$homeapp['block_title']?></div>
            <div class="admin_container-content">
                <?=$this->include_file($path."/main", false, true)?>
            </div>
        </div>
    </li>
<?php
                }
                if (isset($web_path) && isset($homeapp)) {
                    add_homeapp_js($web_path, $homeapp);
                }
            endwhile;
?>
</ul>
<ul class="col-half left" id="column2">
<?php
            $x = 0;
            while ($app = $qry['col2']->fetch_assoc()):
                if (is_dir($base_path.$app['path'])) {
                    $path = $base_path.$app['path'];
                    $web_path = "features/default/home-apps/".$app['path'];
                    include $path."/config.php";
                    $x++;
?>
    <li id="<?=$app['path']?>=<?=$x?>">
        <div class="admin_container" draggable="true">
            <div class="admin_container-header handle"><?=$homeapp['block_title']?></div>
            <div class="admin_container-content">
                <?=$this->include_file($path."/main", false, true)?>
            </div>
        </div>
    </li>
<?php
                }
                if (isset($web_path) && isset($homeapp)) {
                    add_homeapp_js($web_path, $homeapp);
                }
            endwhile;
?>
</ul>
<?php
        } else {
            notify("admin", "info", "You have no home apps!");
        }
    } else {
        notify("admin", "failure", "There was an error querying the database.");
    }
}