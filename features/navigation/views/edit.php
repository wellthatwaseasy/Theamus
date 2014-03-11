<?php

$error = false;

// Define and clean the GET request
$get = filter_input_array(INPUT_GET);

// Get the link ID
if (isset($get['id']) && $get['id'] != "") {
    $id = $tData->real_escape_string($get['id']);
} else {
    $error = "I can't find the link you're looking for.";
}

// Define the link database table
$links_table = $tDataClass->prefix."_links";

// Query the database for the link
$qry['find'] = $tData->query("SELECT * FROM `$links_table` WHERE `id`='$id'");

// Check for a valid query
if ($qry['find'] || $qry['find']->num_rows > 0) {
    $link = $qry['find']->fetch_assoc();
} else {
    $error = "This link isn't in the database.";
}

?>

<div class="admin_content-header">
    <span class="admin_content-header-img">
        <img id="admin_content-header-img" src="features/navigation/img/edit-nav.png" alt="" />
    </span>
    <div class="right" style="margin-top: -2px;">
        <input type="button" name="cancel" value="Go Back" />
    </div>
	<div class="admin_content-header-text">
        <?php
        if ($error != false):
            notify("admin", "failure", $error);
        else:
        ?>
        Edit the link "<?=$link['text']?>"
        <?php endif; ?>
    </div>
</div>

<form id="info-form" style="visiblity: hidden; position: absolute;">
    <input type="hidden" name="page-type" value="save" />
    <?php
    $cols = array("id", "alias", "text", "path", "weight", "groups", "type", "position", "child_of");
    foreach ($cols as $item):
    ?>
    <input type="hidden" name="<?=$item?>" value="<?=htmlspecialchars($link[$item])?>" />
    <?php endforeach; ?>
</form>
<div class="admin_page-content" id="form-wrapper"></div>