<?php

function update($system_info) {
    switch ($system_info['version']) {
        case "0.1": if (update_02() == false) return false;
    }
    
    if (update_version() == false) return false;
    update_cleanup();
    return true;
}