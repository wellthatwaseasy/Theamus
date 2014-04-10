<?php

function update($system_info) {
    switch ($system_info['version']) {
        case "0.1":
        case "0.7":
            if (update_02() == false) {
                return false;
            }
            break;
        case "1.0":
            if (update_11() == false) {
                return false;
            }
    }

    if (update_version() == false) return false;
    update_cleanup();
    return true;
}