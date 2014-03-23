<?php

try {
    $Features->remove_feature();
} catch (Exception $ex) {
    die(notify("admin", "failure", $ex->getMessage()));
}