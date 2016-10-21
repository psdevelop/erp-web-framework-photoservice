<?php

/**
 * @author 
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class MeetingResultType extends DataObject  {
    public $meeting_result_type_name;
    
    function __construct($meeting_result_type)    {
        parent::__construct($meeting_result_type['id'], null);
        $this->meeting_result_type_name = $meeting_result_type['meeting_result_type_name'];
    }
}

?>