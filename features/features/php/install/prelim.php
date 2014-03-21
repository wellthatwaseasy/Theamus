<?php

try {
    $Features->prelim_install();
} catch (Exception $ex) {
    $Features->clean_temp_folder();
    notify("site", "failure", $ex->getMessage());
}