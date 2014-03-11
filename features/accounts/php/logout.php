<?php

session_destroy();
setcookie("session", "", 30, "/");
setcookie("userid", "", 30, "/");

?>