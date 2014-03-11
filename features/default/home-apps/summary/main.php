<?php

$prefix = $tDataClass->prefix;

$info = array("pages", "users", "features", "links", "groups", "images");
foreach($info as $i) {
    $table = $prefix."_".$i;
    $qry = $tData->query("SELECT * FROM `$table`");
    $total[$i] = $qry->num_rows;
}

foreach ($total as $key => $val):
?>
<div style="border:1px solid #EEE; padding: 5px 10px; margin: 5px 0 0;">
    <span style="font-size:14pt; color: #AAA;"><?=$val?></span>
    <span style="padding: 0 5px;"><?=ucfirst($key)?></span>
</div>
<?php endforeach; ?>