<?php


/**
 * Initiates the page.  
 * What you see on the web is a direct result of this function
 * 
 * @return boolean
 */
function initiate() {
    $params = isset($_GET['params']) ? $_GET['params'] : "";
    $tCall = new tCall($params);
    $tCall->handle_call();
    return true;
}

initiate();