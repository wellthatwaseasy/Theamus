<?php

// Check for the configuration file
if (file_exists(path(ROOT."/config.php"))) {
    // Remove the configuration file, let the user know and get out
    if (unlink(path(ROOT."/config.php"))) {
        notify("install", "success", "The configuration file has been reset.".js_countdown());
        run_after_ajax("go_step", '{"step":"dbreset"}');
    } else {
        notify("install", "failure", "There was an issue when resetting the configuration file.");
    }
} else {
    notify("install", "failure", "There was an issue when resetting the configuration file.");
}