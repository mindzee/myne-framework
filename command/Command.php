<?php

namespace myne\command;

require_once('controller/Request.php');

abstract class Command
{
    private static $_statusStrings = array(
        'COMMAND_DEFAULT'           => 0,
        'COMMAND_OK'                => 1,
        'COMMAND_ERROR'             => 2,
        'COMMAND_INSUFFICIENT_DATA' => 3,
    );
    
    final function __construct() {}
    
    public function execute(\myne\controller\Request $request)
    {
        $this->doExecute($request);
    }
    
    abstract function doExecute(\myne\controller\Request $request);
}
