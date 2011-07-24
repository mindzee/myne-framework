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
    
    private $_status = 0;
    
    final function __construct() {}
    
    public function execute(\myne\controller\Request $request)
    {
        $this->_status = $this->doExecute($request);
        
        $request->setCommand($this);
    }
    
    public function getStatus()
    {
        return $this->_status;
    }
    
    public static function statuses($statusString = 'COMMAND_DEFAULT')
    {
        if (empty($statusString))
        {
            $statusString = 'COMMAND_DEFAULT';
        }
        
        return self::$_statusStrings[$statusString];
    }
    
    abstract function doExecute(\myne\controller\Request $request);
}
