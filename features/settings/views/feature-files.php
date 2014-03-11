<?php

$id = filter_input(INPUT_GET, "id");
echo $Settings->get_feature_files_select($id);