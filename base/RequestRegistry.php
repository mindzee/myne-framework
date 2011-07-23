<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\base;

require_once('base/Registry.php');
require_once('controller/Request.php');

/**
 * Description of Registry
 *
 * @author Mindaugas Dargis
 * @uses \myne\base\Registry
 */
class RequestRegistry extends Registry
{
    /**
     *
     * @var array
     * @access private
     */
    private $_values = array();

    /**
     * Holds the Registry instance
     *
     * @var RequestRegistry
     */
    private static $_instance;

    /**
     * Constructor forbidden
     */
    private function __construct() {}

    /**
     * Instanciates new RequestRegistry object
     *
     * @return RequestRegistry
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
     * @return <type>
     */
    protected function _get($key)
    {
        if (isset($this->_values[$key]))
        {
            return $this->_values[$key];
        }

        return null;
    }

    protected function _set($key, $value)
    {
        $this->_values[$key] = $value;
    }

    /**
     *
     * @return string 
     */
    public static function getRequest()
    {
        return self::getInstance()->_get('request');
    }

    /**
     *
     * @param \myne\controller\Request $request
     */
    public static function setRequest(\myne\controller\Request $request)
    {
        self::getInstance()->_set('request', $request);
    }
}

