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
    /**
     * Contructor
     * 
     * Forbidden to outside instantiation
     * 
     * @access private
     */
    private function __construct() {}
    
    /**
     * Method starts the application process
     * 
     * @return void
     * @access public
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
     * @access public
     */
    public function init()
    {
        $applicationHelper = ApplicationHelper::getInstance();
        
        $applicationHelper->init();
    }
    
    /**
     * Handles the Request
     * 
     * @return void
     * @access public
     */
    public function handleRequest()
    {
        $request = new Request();
        
        $applicationController = \myne\base\ApplicationRegistry::getApplicationController();
        
        while ($command = $applicationController->getCommand($request))
        {
            $command->execute($request);
        }
        
        $this->_invokeView($applicationController->getView($request));
    }
    
    /**
     *
     * @param type $target 
     */
    private function _invokeView($target)
    {
        include("view/{$target}.php");
        exit();
    }
}
