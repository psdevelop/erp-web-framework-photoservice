<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class Call extends DataObject  {
    public $operator_id;
    public $kg_id;
    //public $call_status_id;
    public $call_date;
    public $calls_comment;
    public $stock_id;
    //public $repeat_call_datetime;
    
    function __construct($call)    {
        parent::__construct($call['id'], null);
        $this->operator_id = $call['operator_id'];
        $this->kg_id = $call['kg_id'];
        //$this->call_status_id = $call['call_status_id'];
        $this->relative_props['call_status_id'] = $call['call_status_id'];
        $this->call_date = $call['call_date'];
        $this->calls_comment = $call['calls_comment'];
        $this->stock_id = $call['stock_id'];
        $this->relative_props['repeat_call_datetime'] = $call['repeat_call_datetime'];
        $this->relative_props['operator_name'] = $call['operator_last_name']." ".
                $call['operator_first_name']." ".$call['operator_surname'];
        $this->relative_props['kg_name'] = $call['kg_name'];
        $this->relative_props['call_status_name'] = $call['call_status_name'];
        $this->relative_props['kg_status'] = $call['kg_status'];
        $this->relative_props['kg_comment'] = $call['kg_comment'];
        $this->relative_props['kg_code'] = $call['kg_code'];
        $this->relative_props['kg_adress'] = $call['kg_adress'];
        $this->relative_props['kg_phones'] = $call['kg_phones'];
        $this->relative_props['kg_contact_person'] = $call['kg_contact_person'];
        $this->relative_props['district_id'] = $call['district_id'];
        $this->relative_props['all_call_statuses_names'] = $call['all_call_statuses_names'];
        $this->relative_props['stock_name'] = $call['stock_name'];
        $this->relative_props['ready_to_call'] = $call['ready_to_call'];
        $this->relative_props['ready_to_call_datetime'] = $call['ready_to_call_datetime'];
        $this->relative_props['meeting_status_id'] = $call['meeting_status_id'];
        $this->relative_props['identity_name'] = $call['call_name'];
        $this->relative_props['status_datetime'] = $call['status_datetime'];
        $this->relative_props['meeting_datetime'] = $call['meeting_datetime'];
        $this->relative_props['last_meet_comment'] = $call['last_meet_comment'];
        $this->relative_props['mrepeat_datetime'] = $call['mrepeat_datetime'];
    }
}

?>