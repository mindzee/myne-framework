<?php

error_reporting(E_ALL | E_STRICT);

set_include_path(
    realpath(dirname(__FILE__)) . PATH_SEPARATOR .
    get_include_path());

require_once('controller/Controller.php');

\myne\controller\Controller::run();
