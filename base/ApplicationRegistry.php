<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\base;

require_once('base/Registry.php');
require_once('controller/ApplicationController.php');

/**
 * Description of ApplicationRegistry
 *
 * @author Mindaugas Dargis
 */
class ApplicationRegistry extends Registry
{
    /**
     *
     * @var ApplicationRegistry
     * @access private 
     */
    private static $_instance;
    
    /**
     *
     * @var string 
     */
    private $_dataDir = 'data';
    
    /**
     *
     * @var array 
     */
    private $_values = array();
    
    /**
     *
     * @var array
     */
    private $_modificationTimes = array();
    
    /**
     * Constructor
     * 
     * Forbidden to outside instantiation
     * 
     * @access private
     */
    private function __construct() {}
    
    /**
     * Get's ApplicationRegistry instance
     * 
     * @return ApplicationRegistry
     * @access public
     * @static
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     *
     * @param string $key
     * @return type 
     */
    protected function _get($key)
    {
        $path = $this->_dataDir . DIRECTORY_SEPARATOR . $key;

        if (file_exists($path))
        {
            clearstatcache();

            // when the file was modified
            $modificationTime = filemtime($path);

            if (!isset($this->_modificationTimes[$key]))
            {
                $this->_modificationTimes[$key] = 0;
            }

            if ($modificationTime > $this->_modificationTimes[$key])
            {
                $data = file_get_contents($path);

                $this->_modificationTimes[$key] = $modificationTime;

                return $this->_values[$key] = unserialize($data);
            }
        }

        if (isset($this->_values[$key]))
        {
            return $this->_values[$key];
        }

        return null;
    }

    protected function _set($key, $value)
    {
        $this->_values[$key] = $value;

        $path = $this->_dataDir . DIRECTORY_SEPARATOR . $key;

        file_put_contents($path, serialize($value));

        $this->_modificationTimes[$key] = time();
    }

    public static function getDSN()
    {
        return self::getInstance()->_get('dsn');
    }

    public static function setDSN($dsn)
    {
        self::getInstance()->_set('dsn', $dsn);
    }
    
    public static function getApplicationController()
    {
        $instance = self::getInstance();
        
        if (!isset($instance->applicationController))
        {
            $controllerMap = $instance->getControllerMap();
            
            $instance->applicationController = new \myne\controller\ApplicationController($controllerMap);
        }
        
        return $instance->applicationController;
    }
    
    public static function setControllerMap(\myne\controller\ControllerMap $controllerMap)
    {
        self::getInstance()->_set('ControllerMap', $controllerMap);
    }
    
    public static function getControllerMap()
    {
        return self::getInstance()->_get('ControllerMap');
    }
}
