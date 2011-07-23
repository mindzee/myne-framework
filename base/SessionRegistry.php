<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\base;

require_once('base/Registry.php');

/**
 * Description of SessionRegistry
 *
 * @author Mindaugas Dargis
 */
class SessionRegistry extends Registry
{
    private static $_instance;

    private function __construct()
    {
        session_start();
    }

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
        if (isset($_SESSION[__CLASS__][$key]))
        {
            return $_SESSION[__CLASS__][$key];
        }

        return null;
    }

    protected function _set($key, $value)
    {
        $_SESSION[__CLASS__][$key] = $value;
    }

    public function setComplex(Complex $complex)
    {
        self::getInstance()->_set('complex', $complex);
    }

    public function getComplex()
    {
        return self::getInstance()->_get('complex');
    }
}
