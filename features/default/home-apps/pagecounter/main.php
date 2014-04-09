<?php

$pages_table = $tDataClass->prefix."_pages";
$sql['find'] = "SELECT * FROM `".$pages_table."` ORDER BY `views` DESC LIMIT 5";
$qry['find'] = $tData->query($sql['find']);

if ($qry['find']) {
    if ($qry['find']->num_rows > 0) {
        while ($page = $qry['find']->fetch_assoc()) {
            $title = strlen($page['title']) > 10 ? substr($page['title'], 0, 10)."..." : $page['title'];
            $pages[$title] = $page['views'];
        }
        $pages = json_encode($pages);
?>
<canvas id="page_canvas" width="420" height="250" style="margin:0 auto; display:block;"></canvas>
<?php
    } else {
        notify("admin", "info", "You have no pages to show!");
    }
} else {
    notify("admin", "failure", "There was an issue querying the database.");
}
?>

<script type="text/javascript">
    function init(){if(typeof show_count_chart==="undefined"){setTimeout(function(){init()},50)}else{show_count_chart(<?php echo $pages; ?>)}}$(function(){init()})
</script>