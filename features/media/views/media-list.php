<?php

$get = filter_input_array(INPUT_GET);

$page = 1;
if (isset($get['page'])) {
    $page = $tData->real_escape_string($get['page']);
}

$images_table = $tDataClass->prefix."_images";
$s = "SELECT * FROM `".$images_table."`";

$template_header = "";
$template = <<<TEMPLATE
    <div class="media_list-img">
        <div class="media_list-options">
            <a href="#" onclick="return remove_media('%id%');" class="media_list-remove"
                title="Remove this image">X</a>
        </div>
        <img src="media/images/%path%" alt="">
    </div>
TEMPLATE;

$tPages->set_page_data(array(
    "sql" => $s,
    "per_page" => 10,
    "current" => $page,
    "list_template" => $template,
    "template_header" => $template_header
));

$tPages->print_list();
echo "<div class='clearfix'></div>";
$tPages->print_pagination("next_page");