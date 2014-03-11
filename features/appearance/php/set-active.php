<?php

try {
    $Appearance->set_active_theme();
} catch (Exception $ex) {
    $Appearance->print_exception($ex);
}
