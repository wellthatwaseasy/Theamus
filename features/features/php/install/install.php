<?php

try {
    $Features->install_feature();
    notify("admin", "success", "This feature has been installed. - ".js_countdown());
} catch (Exception $ex) {
    $Features->clean_temp_folder();
    $Features->remove_feature_folder();
    die(notify("admin", "failure", $ex->getMessage()));
}