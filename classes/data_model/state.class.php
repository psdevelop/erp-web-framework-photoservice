<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class State extends DataObject  {
    public $state_name;
    
    function __construct($state)    {
        parent::__construct($state['id'], null);
        $this->state_name = $state['state_name'];
    }
}
?>