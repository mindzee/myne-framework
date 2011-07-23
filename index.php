<?php
/**
 * Main application entry file
 */

// Error reporting set to highest level available
error_reporting(E_ALL | E_STRICT);

// Adding root directory to php include path
set_include_path(
    realpath(dirname(__FILE__)) . PATH_SEPARATOR .
    get_include_path());

// FrontController starts the application process by invoking run() method
require_once('controller/FrontController.php');
\myne\controller\FrontController::run();
