<?php

try {
    $Settings->save_customization();
    notify("admin", "success", "Information saved.");
} catch (Exception $ex) {
    die(notify("admin", "failure", $ex->getMessage()));
}