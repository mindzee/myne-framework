<?php

namespace myne\controller;

require_once('controller/ApplicationHelper.php');
require_once('controller/Request.php');
require_once('command/CommandResolver.php');

class Controller
{
    private $_applicationHelper;
    
    private function __construct() {}
    
    public static function run()
    {
        $instance = new self();
        $instance->init();
        $instance->handleRequest();
    }
    
    public function init()
    {
        //require_once('ApplicationHelper.php');
        $applicationHelper = ApplicationHelper::getInstance();
        $applicationHelper->init();
    }
    
    public function handleRequest()
    {
        $request = new Request();
        
        $commandResolver = new \myne\command\CommandResolver();
        
        $command = $commandResolver->getCommand($request);
        
        $command->execute($request);
    }
}
