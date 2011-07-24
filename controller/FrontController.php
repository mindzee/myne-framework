<?php
/**
 * Myne Framework 
 * 
 * PHP tiny framework that uses 5.3+ engine. This gives the advantage 
 * of using namespaces.
 */

namespace myne\controller;

require_once('controller/ApplicationHelper.php');
require_once('controller/Request.php');
require_once('command/Router.php');

/**
 * Application's FrontController
 */
class FrontController
{
    private $_applicationHelper;
    
    /**
     * Contructor
     * 
     * Inittialized when run() method get's invoked
     */
    private function __construct() {}
    
    /**
     * Method starts the application process
     * 
     * @return void
     */
    public static function run()
    {
        $instance = new self();
        
        $instance->init();
        
        $instance->handleRequest();
    }
    
    /**
     * Method instantiates ApplicationHelper and
     * calls its init() method 
     * 
     * @return void
     */
    public function init()
    {
        $applicationHelper = ApplicationHelper::getInstance();
        
        $applicationHelper->init();
    }
    
    /**
     * Instantiates new Request and Router oject's
     */
    public function handleRequest()
    {
        $request = new Request();
        
        $router = new \myne\command\Router();
        
        $command = $router->getCommand($request);
        
        $command->execute($request);
    }
}
