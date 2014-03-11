<?php
$user = $tUser->user;
?>
<form class="site-form" onsubmit="return false;">
    <div class="site-formheader">
        General Information
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Member Since</div>
        <div class="site-formtext">
            <?php
            $since = date("F jS, Y", strtotime($user['created']));
            echo $since;
            ?>
        </div>
        <div class="site-forminfo">
            Thank you!
        </div>
    </div>

    <div class="site-formheader">
        Other Information
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Groups you're a part of</div>
        <div class="site-formtext">
            <?php
            $groups = explode(",", $user['groups']);
            foreach ($groups as $group) {
                $group = ucwords(str_replace("_", " ", $group));
                echo $group."<br />";
            }
            ?>
        </div>
    </div>
    <div class="site-formrow">
        <div class="site-formlabel">Features you have access to</div>
        <div class="site-formtext">
            <?php
            $groups = explode(",", $user['groups']);
            $feature_table = $tDataClass->get_system_prefix()."_features";
            foreach ($groups as $group) {
                $sql['features'] = "SELECT * FROM `".$feature_table."` WHERE `groups` LIKE '".$group."%'";
                $qry['features'] = $tData->query($sql['features']);
                
                if ($qry['features']->num_rows >= 0) {
                    while ($feature = $qry['features']->fetch_assoc()) {
                        echo $feature['name']."<br />";
                    }
                }
            }
            ?>
        </div>
    </div>
</form>