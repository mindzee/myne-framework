<?php

namespace myne\controller;

require_once('base/RequestRegistry.php');

class Request
{
    private $_properties;
    
    private $_feedback = array();
    
    public function __construct()
    {
        $this->init();
        
        \myne\base\RequestRegistry::setRequest($this);
    }
    
    public function init()
    {
        if (isset($_SERVER['REQUEST_METHOD']))
        {
            $this->_properties = $_REQUEST;
            
            return;
        }
        
        foreach ($_SERVER['argv'] as $arg)
        {
            if (strpos($arg, '='))
            {
                list($key, $value) = explode('=', $arg);
                
                $this->setProperty($key, $value);
            }
        }
    }
    
    public function getProperty($key)
    {
        if (isset($this->_properties[$key]))
        {
            return $this->_properties[$key];
        }
    }
    
    public function setProperty($key, $value)
    {
        $this->_properties[$key] = $value;
    }
    
    public function addFeedback($message)
    {
        array_push($this->_feedback, $message);
    }
    
    public function getFeedback()
    {
        return $this->_feedback;
    }
    
    public function getFeedbackString($separator = "\n")
    {
        return implode($separator, $this->_feedback);
    }
}

