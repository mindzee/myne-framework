<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\command;

require_once('controller/Request.php');

/**
 * Description of DefaultCommand
 *
 * @author Mindaugas Dargis
 */
class DefaultCommand extends Command
{
    public function doExecute(\myne\controller\Request $request)
    {
        $request->addFeedback('Welcome to myne');
    }
}
