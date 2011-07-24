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
    
    private function _getResource(Request $request, $resource)
    {
        // get previous command and it's execution status
        $commandString = $request->getProperty('command');
        
        $previousCommand = $request->getLastCommand();
        
        $status = $previousCommand->getStatus();
        
        if (!$status)
        {
            $status = 0;
        }
        
        $method = "get{$resource}";
        
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
    
    public function getCommand(Request $request)
    {
        $previousCommand = $request->getLastCommand();
        
        if (!$previousCommand)
        {
            $command = $request->getProperty('command');
            
            if (!$command)
            {
                $request->setProperty('command', 'default');
                
                return self::$_defaultCommand;
            }
        }
        else
        {
            $command = $this->getForward($request);
            
            if (!$command)
            {
                return null;
            }
        }
    }
}
