<?php

try {
    $theme = $Appearance->upload_theme();
    $Appearance->extract_tmp_theme($theme);
    $config = $Appearance->get_config($theme);
    $Appearance->check_config_options($config);
    $Appearance->finalize_update($theme);
} catch (Exception $ex) {
    $Appearance->print_exception($ex);
}