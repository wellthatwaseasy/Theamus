<?php

/**
 * Theamus -- a modular content management system that makes websites easy.
 *
 * PHP Version 5.5.3
 * Version 0.6
 * @package Theamus
 * @link http://www.theamus.com/
 * @author Matthew Tempinski (Eyraahh) <matt.tempinski@theamus.com>
 * @copyright 2014 Matthew Tempinski
 */

// Define any ini_set variables
ini_set("session.gc_maxlifetime", 7*24*60*60);

session_start(); // Start the session!
define("ROOT", dirname(__FILE__)); // Define the root of the system

$params = isset($_GET['params']) ? $_GET['params'] : ""; // Define the given parameters

require "system/bootstrap.php"; // Require the bootstrap to load the page