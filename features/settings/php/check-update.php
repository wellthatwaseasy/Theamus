<?php

try {
    $Settings->check_for_updates();
} catch (Exception $ex) {
    run_after_ajax("back_to_check");
    die($ex->getMessage());
}