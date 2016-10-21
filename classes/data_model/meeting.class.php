<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class Meeting extends DataObject  {
    public $operator_id;
    public $call_id;
    public $manager_id;
    public $meeting_date;
    public $meeting_time;
    
    function __construct($meeting)    {
        parent::__construct($meeting['id'], null);
        $this->operator_id = $meeting['operator_id'];
        $this->call_id = $meeting['call_id'];
        $this->manager_id = $meeting['manager_id'];
        $this->meeting_date = $meeting['meeting_date'];
        $this->meeting_time = $meeting['meeting_time'];
        $this->relative_props['operator_name'] = $meeting['operator_name'];
        $this->relative_props['meeting_result_type_id'] = $meeting['meeting_result_type_id'];
        $this->relative_props['meeting_result_type_name'] = $meeting['meeting_result_type_name'];
        $this->relative_props['meeting_statuses_names'] = $meeting['meeting_statuses_names'];
        $this->relative_props['manager_name'] = $meeting['manager_name'];
        $this->relative_props['call_name'] = $meeting['call_name'];
        $this->relative_props['kg_id'] = $meeting['kg_id'];
        $this->relative_props['kg_status'] = $meeting['kg_status'];
        $this->relative_props['kg_comment'] = $meeting['kg_comment'];
        $this->relative_props['kg_code'] = $meeting['kg_code'];
        $this->relative_props['kg_adress'] = $meeting['kg_adress'];
        $this->relative_props['kg_phones'] = $meeting['kg_phones'];
        $this->relative_props['kg_contact_person'] = $meeting['kg_contact_person'];
        $this->relative_props['ready_to_call'] = $meeting['ready_to_call'];
        $this->relative_props['ready_to_call_datetime'] = $meeting['ready_to_call_datetime'];
        $this->relative_props['repeat_meet_call_datetime'] = $meeting['repeat_meet_call_datetime'];
        $this->relative_props['repeat_meet_datetime'] = $meeting['repeat_meet_datetime'];
        $this->relative_props['identity_name'] = $meeting['meeting_name'];
        $this->relative_props['on_control'] = $meeting['on_control'];
    }
}

?>