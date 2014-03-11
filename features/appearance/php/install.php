<?php

try {
    $Appearance->install_theme();
    notify("admin", "success", "This theme was installed successfully! ".js_countdown());
    run_after_ajax("back_to_list");
} catch (Exception $ex) {
    $Appearance->print_exception($ex);
}