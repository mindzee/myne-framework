<?php

namespace myne\command;

require_once('command/Command.php');
require_once('command/DefaultCommand.php');
require_once('controller/Request.php');

class CommandResolver
{
    private static $_baseCommand;
    
    private static $_defaultCommand;
    
    public function __construct()
    {
        if (!self::$_baseCommand)
        {
            self::$_baseCommand = new \ReflectionClass('\myne\command\Command');
            self::$_defaultCommand = new DefaultCommand();
        }
    }
    
    public function getCommand(\myne\controller\Request $request)
    {
        $command = $request->getProperty('command');
        
        if (!$command)
        {
            return self::$_defaultCommand;
        }
        
        $command = str_replace(array('.', DIRECTORY_SEPARATOR), '', $command);
        
        $filepath = 'command' . DIRECTORY_SEPARATOR . "{$command}.php";
        
        $classname = 'myne\command\\' . $command;
        
        if (file_exists($filepath))
        {
            @require_once('{$filepath}');
            
            if (class_exists($classname))
            {
                $commandClass = new \ReflectionClass($classname);
                
                if ($commandClass->isSubclassOf(self::$_baseCommand))
                {
                    return $commandClass->newInstance();
                }
                else
                {
                    $request->addFeedback("command '{$command}' is not a Command");
                }
            }
        }
        
        $request->addFeedback("command '{$command}' not found");
        
        return clone self::$_defaultCommand;
    }
}