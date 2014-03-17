<?php

try {
    $Settings->manual_update();
} catch (Exception $ex) {
    $Settings->abort_update($ex);
}