<?php

$alias = urldecode(filter_input(INPUT_GET, "page"));

// Define the pages table
$pages_table = $tDataClass->prefix."_pages";

// Query the database for all of the site's pages
$qry['find'] = $tData->query("SELECT * FROM `$pages_table`");

// Check for a valid query
if ($qry['find'] && $qry['find']->num_rows > 0) {
    // Loop through the results, printing out the options
    while ($page = $qry['find']->fetch_assoc()) {
        $s = $alias == $page['alias'] ? "selected" : "";
        echo "<option value='".$page['alias']."' $s>".$page['title']."</option>";
    }
} else {
    // Throw out an error message in the form of an option
    echo "<option>Error loading pages</option>";
}