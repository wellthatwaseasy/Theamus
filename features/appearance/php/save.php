<?php

try {
    $function = urldecode(filter_input(INPUT_GET, "f"));
    $Appearance->load_theme_function($function);
} catch (Exception $ex) { $Appearance->print_exception($ex); }