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
    private static $_instance;

    private $_freezeDir = 'data';

    private $_values = array();

    private $_modificationTimes = array();

    private function __construct() {}

    public static function getInstance()
    {
        if (!isset(self::$_instance))
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    protected function _get($key)
    {
        $path = $this->_freezeDir . DIRECTORY_SEPARATOR . $key;

        if (file_exists($path))
        {
            clearstatcache();

            //patikrina kada failas buvo redaguotas
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

        $path = $this->_freezeDir . DIRECTORY_SEPARATOR . $key;

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
            
            var_dump($controllerMap);
            die();
            
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
