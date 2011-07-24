<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\controller;

require_once('command/Command.php');
require_once('command/DefaultCommand.php');
require_once('controller/Request.php');

/**
 * Description of ApplicationController
 *
 * @author mindzee
 */
class ApplicationController
{
    private static $_baseCommand;
    
    private static $_defaultCommand;
    
    private $_controllerMap;
    
    private $_invoked = array();
    
    /**
     * Constructor
     * 
     * @param ControllerMap $controllerMap 
     */
    public function __construct(ControllerMap $controllerMap)
    {
        $this->_controllerMap = $controllerMap;
        
        if (!self::$_baseCommand)
        {
            self::$_baseCommand = new \ReflectionClass('\myne\command\Command');
            self::$_defaultCommand = new \myne\command\DefaultCommand();
        }
    }
    
    public function getView(Request $request)
    {
        return $this->_getResource($request, 'View');
    }
    
    public function getForward(Request $request)
    {
        $forward = $this->_getResource($request, 'Forward');
        
        if ($forward)
        {
            $request->setProperty('command', $forward);
        }
        
        return $forward;
    }
    
    /**
     *
     * @param Request $request
     * @param type $resource
     * @return type 
     */
    private function _getResource(Request $request, $resourceName)
    {
        // get previous command and it's execution status
        $commandString = $request->getProperty('command');
        
        $previousCommand = $request->getLastCommand();
        
        $status = $previousCommand->getStatus();
        
        if (!$status)
        {
            $status = 0;
        }
        
        $method = "get{$resourceName}";
        
        // find resource for previous command and it's status
        $resource = $this->_controllerMap->{$method}($commandString, 0);
        
        // alternatively find resource for command and status 0
        if (!$resource)
        {
            $resource = $this->_controllerMap->{$method}($commandString, 0);
        }
        elseif (!$resource)
        {
            $resource = $this->_controllerMap->{$method}('default', $status);
        }
        elseif (!$resource)
        {
            $resource = $this->_controllerMap->{$method}('default', 0);
        }
        
        return $resource;
    }
    
    /**
     *
     * @param Request $request
     * @return void|null
     * @throws ApplicationException
     */
    public function getCommand(Request $request)
    {
        $previousCommand = $request->getLastCommand();
        
        // it's the first command in this request
        if (!$previousCommand)
        {
            $command = $request->getProperty('command');
            
            // no command property, using default 
            if (!$command)
            {
                $request->setProperty('command', 'default');
                
                return self::$_defaultCommand;
            }
        }
        // the command has alrwady been run in this request
        else
        {
            $command = $this->getForward($request);
            
            if (!$command)
            {
                return null;
            }
        }
        
        // now we have a command name in $command
        // turning it into a Command object
        $commandObject = $this->resolveCommand($command);
        
        if (!$commandObject)
        {
            throw new \myne\base\ApplicationException('Could not resolve command');
        }
        
        $commandClass = get_class($commandObject);
        
        if (isset($this->_invoked[$commandClass]))
        {
            throw new \myne\base\ApplicationException('Circular forwarding error');
        }
        
        $this->_invoked[$commandClass] = 1;
        
        return $commandObject;
    }
    
    /**
     *
     * @param type $command
     * @return Command|null 
     */
    public function resolveCommand($command)
    {
        $classroot = $this->_controllerMap->getClassroot($command);
        
        $filepath = "command/{$classroot}.php";
        
        $classname = "\\myne\\command\\{$classroot}";
        
        if (file_exists($filepath))
        {
            require_once("{$filepath}");
            
            if (class_exists($classname))
            {
                $commandClass = new \ReflectionClass($classname);
                
                if ($commandClass->isSubclassOf(self::$_baseCommand))
                {
                    return $commandClass->newInstance();
                }
            }
        }
        
        return null;
    }
}
