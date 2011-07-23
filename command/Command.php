<?php

namespace myne\command;

require_once('controller/Request.php');

abstract class Command
{
    final function __construct() {}
    
    public function execute(\myne\controller\Request $request)
    {
        $this->doExecute($request);
    }
    
    abstract function doExecute(\myne\controller\Request $request);
}
