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
                $query = $tData->select_from_table($tData->prefix."_features", array("name"), array("operator" => "", "conditions" => array("groups" => $group)));

                if ($tData->count_rows($query) > 0) {
                    $results = $tData->fetch_rows($query);
                    foreach ($results as $feature) {
                        echo $feature['name']."<br>";
                    }
                }
            }
            ?>
        </div>
    </div>
</form>