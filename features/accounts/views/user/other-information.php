<?php
$user = $tUser->user;
?>
<form class="site-form" onsubmit="return false;">
    <h2 class="form-header">General Information</h2>
    <div class="form-group">
        <label class="control-label">Member Since</label>
        <div class="form-control-static"><?php echo date("F jS, Y", strtotime($user['created'])); ?></div>
        <p class="help-block">Thank you!</p>
    </div>

    <h2 class="form-header">Other Information</h2>
    <div class="form-group">
        <label class="control-label">Groups you are a part of</label>
        <div class="form-control-static">
            <?php
            $groups = explode(",", $user['groups']);
            foreach ($groups as $group) {
                echo ucwords(str_replace("_", " ", $group)). "<br>";
            }
            ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Features you have access to</label>
        <div class="form-control-static">
            <?php
            $groups = explode(",", $user['groups']);
            foreach ($groups as $group) {
                $query = $tData->query("SELECT * FROM `".$tDataClass->get_system_prefix()."_features` WHERE `groups` LIKE '".$group."%'");

                if ($query->num_rows >= 0) {
                    while ($feature = $query->fetch_assoc()) {
                        echo $feature['name']."<br />";
                    }
                }
            }
            ?>
        </div>
    </div>
</form>