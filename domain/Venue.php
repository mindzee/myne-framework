<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace myne\domain;

/**
 * Description of Venue
 *
 * @author mindzee
 */
class Venue
{
    private $_id;
    
    private $_name;
    
    public function __construct($id, $name)
    {
        $this->_id = $id;
        $this->_name = $name;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function getId()
    {
        return $this->_id;
    }
}
