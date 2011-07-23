<?php
/**
 *
 */

namespace myne\controller;

require_once('base/ApplicationRegistry.php');
require_once('base/ApplicationException.php');

/**
 *
 */
class ApplicationHelper
{
    private static $_instance;
    
    private $_configFile = 'temp/data/myne_options.xml';
    
    private function __construct() {}
    
    public static function getInstance()
    {
        if (!self::$_instance)
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    public function init()
    {
        $dsn = \myne\base\ApplicationRegistry::getDSN();
        
        if (!is_null($dsn))
        {
            return;
        }
        
        $this->_getOptions();
    }
    
    private function _getOptions()
    {
        $this->_ensure(file_exists($this->_configFile), 'Could not find options file');
        
        $options = simplexml_load_file($this->_configFile);
        
        echo get_class($options);
        
        $dsn = (string) $options->dsn;
        
        $this->_ensure($dsn, 'No DSN found');
        
        \myne\base\ApplicationRegistry::setDSN($dsn);
    }
    
    private function _ensure($expression, $message)
    {
        if (!$expression)
        {
            throw new \myne\base\ApplicationException($message);
        }
    }
}