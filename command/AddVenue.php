<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\command;

require_once('command/Command.php');
require_once('domain/Venue.php');

/**
 * Description of AddVenue
 *
 * @author mindzee
 */
class AddVenue extends Command
{
    public function doExecute(\myne\controller\Request $request)
    {
        $name = $request->getProperty('venue_name');
        
        if (!$name)
        {
            $request->addFeedback('No name was provided');
            
            return self::statuses('COMMAND_INSUFFICIENT_DATA');
        }
        else
        {
            $venueObject = new \myne\domain\Venue(null, $name);
            
            $request->setObject('venue', $venueObject);
            $request->addFeedback("'{$name}' added ({$venueObject->getId()})");
            
            return self::statuses('COMMAND_OK');
        }
    }
}
