<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\controller;

/**
 * Description of ControllerMap
 *
 * @author Mindaugas Dargis
 */
class ControllerMap
{
    private $_viewMap = array();
    
    private $_forwardMap = array();
    
    private $_classrootMap = array();
    
    public function addClassroot($command, $classroot)
    {
        $this->_classrootMap[$command] = $classroot;
    }
    
    public function getClassroot($command)
    {
        if (isset($this->_classrootMap[$command]))
        {
            return $this->_classrootMap[$command];
        }
        
        return $command;
    }
    
    public function addView($command = 'default', $status = 0, $view)
    {
        $this->_viewMap[$command][$status] = $view;
    }
    
    public function getView($command, $status)
    {
        if (isset($this->_viewMap[$command][$status]))
        {
            return $this->_viewMap[$command][$status];
        }
        
        return null;
    }
    
    public function addForward($command, $status = 0, $newCommand)
    {
        $this->_forwardMap[$command][$status] = $newCommand;
    }
    
    public function getForward($command, $status)
    {
        if (isset($this->_forwardMap[$command][$status]))
        {
            return $this->_forwardMap[$command][$status];
        }
        
        return null;
    }
}
