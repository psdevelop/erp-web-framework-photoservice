<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class CallStatus extends DataObject  {
    public $call_status_name;
    
    function __construct($call_status)    {
        parent::__construct($call_status['id'], null);
        $this->call_status_name = $call_status['call_status_name'];
    }
}

?>