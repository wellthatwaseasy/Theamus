<?php

try {
    $Features->update_feature();
    $Features->save_feature_information();
} catch (Exception $ex) {
    $Features->clean_temp_folder();
    die(notify("admin", "failure", $ex->getMessage()));
}