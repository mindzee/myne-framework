<?php
/**
 *
 */

namespace myne\controller;

require_once('base/ApplicationRegistry.php');
require_once('base/ApplicationException.php');
require_once('controller/ControllerMap.php');

/**
 * Loads application configuration 
 */
class ApplicationHelper
{
    /**
     * ApplicationHelper instance
     * 
     * @var ApplicationHelper 
     * @access private
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
     * Forbidden to outside instantiated
     * 
     * @access private
     */
    private function __construct() {}
    
    /**
     * Get's ApplicationHelper instance
     * 
     * @return ApplicationHelper
     * @access public
     * @static
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
     * @access public
     */
    public function init()
    {
        $dsn = \myne\base\ApplicationRegistry::getDSN();
        
         //if DSN file already exists do nothing
        if (!is_null($dsn))
        {
            return;
        }
        
        $this->_getOptions();
    }
    
    /**
     * Gets configuration information
     */
    private function _getOptions()
    {
        $this->_ensure(file_exists($this->_configFile), 'Could not find options file');
        
        $options = simplexml_load_file($this->_configFile);
        
        $this->_ensure($options instanceof \SimpleXMLElement, 'Could not read options file');
        
        $dsn = (string) $options->dsn;
        
        $this->_ensure($dsn, 'No DSN found');
        
        \myne\base\ApplicationRegistry::setDSN($dsn);
        
        $controllerMap = new ControllerMap();
        
        foreach ($options->control->view as $defaultView)
        {
            $statusString = trim($defaultView['status']);
            
            $status = \myne\command\Command::statuses($statusString);
            
            $controllerMap->addView('default', $status, (string) $defaultView);
        }
        
        \myne\base\ApplicationRegistry::setControllerMap($controllerMap);
    }
    
    /**
     * Ensures that an expression is true
     * 
     * @param mixed $expression
     * @param string $message 
     * @throws ApplicationException
     */
    private function _ensure($expression, $message)
    {
        if (!$expression)
        {
            throw new \myne\base\ApplicationException($message);
        }
    }
}