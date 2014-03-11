<?php

try {
    $Appearance->get_settings_page();
} catch (Exception $ex) {
    $Appearance->print_exception($ex);
}