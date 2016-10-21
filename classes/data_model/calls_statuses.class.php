<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class CallsStatuses extends DataObject  {
    public $call_id;
    public $call_status_id;
    public $call_date;
    public $comment;
    public $meet_datetime;
    
    function __construct($calls_statuses)    {
        parent::__construct($calls_statuses['id'], null);
        $this->call_id = $calls_statuses['call_id'];
        $this->call_status_id = $calls_statuses['call_status_id'];
        $this->call_date = $calls_statuses['call_date'];
        $this->comment = $calls_statuses['comment'];
        $this->meet_datetime = $calls_statuses['meet_datetime'];
    }
}

?>
