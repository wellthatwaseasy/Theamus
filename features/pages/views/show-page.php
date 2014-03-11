<?php

// Define the table
$table = $tDataClass->prefix."_pages";

// SQL friendly variables
$alias = $tData->real_escape_string($this->page_alias);

// Query the database for this page
$sql = "SELECT * FROM `".$table."` WHERE `alias`='".$alias."'";
$query = $tData->query($sql);

// Get the database values
$page = $query->fetch_assoc();

// Only allow relevant people
$groups = explode(",", $page['groups']);

foreach ($groups as $group) {
	$ingroup[] = $group == "everyone" ? "true" : "false";
	$ingroup[] = $tUser->in_group($group) ? "true" : "false";
}

if (in_array("true", $ingroup)) {
	echo $page['content'];

	// Update the page view count
	$views = $page['views'] + 1;
	$update = "UPDATE `".$table."` SET `views`='".$views."' WHERE
		`alias`='".$alias."'";
	$tData->query($update);
} else {
    echo '<div class="content-header">Hah! Caught you.</div>';
	echo "Here's your fun fact of the day: you don't belong here.";
}

?>