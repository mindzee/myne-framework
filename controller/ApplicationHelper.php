<?php
/**
 *
 */

namespace myne\controller;

require_once('base/ApplicationRegistry.php');
require_once('base/ApplicationException.php');

/**
 * Loads application configuration 
 */
class ApplicationHelper
{
    /**
     * ApplicationHelper instance
     * 
     * @var ApplicationHelper 
     */
    private static $_instance;
    
    /**
     * Path to the configuration file
     * 
     * @var string
     */
    private $_configFile = 'temp/data/myne_options.xml';
    
    /**
     * Constructor 
     * 
     * Forbidden to outside classes
     */
    private function __construct() {}
    
    /**
     * Get's ApplicationHelper instance
     * 
     * @return ApplicationHelper
     */
    public static function getInstance()
    {
        if (!self::$_instance)
        {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     *
     * @return void
     */
    public function init()
    {
        $dsn = \myne\base\ApplicationRegistry::getDSN();
        
        // if DSN file already exists do nothing
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