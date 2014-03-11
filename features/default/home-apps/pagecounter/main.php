<?php

$pages_table = $tDataClass->prefix."_pages";
$sql['find'] = "SELECT * FROM `".$pages_table."` ORDER BY `views` DESC LIMIT 5";
$qry['find'] = $tData->query($sql['find']);

if ($qry['find']) {
    if ($qry['find']->num_rows > 0) {
        while ($page = $qry['find']->fetch_assoc()) {
            $pages[$page['title']] = $page['views'];
        }
        $pages = json_encode($pages);
?>
<input type="hidden" id="pages" value='<?=$pages?>' />
<canvas id="page_canvas" width="465" height="250" style="margin:0 auto; display:block;"></canvas>
<?php
    } else {
        notify("admin", "info", "You have no pages to show!");
    }
} else {
    notify("admin", "failure", "There was an issue querying the database.");
}