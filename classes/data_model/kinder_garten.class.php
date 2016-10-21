<?php

/**
 * @author 
 * @copyright 2011
 */

include_once("classes/data_model/data_object.class.php");

class KinderGarten extends DataObject  {
    public $kg_area;
    public $kg_adress;
    public $kg_phones;
    public $kg_contact_person;
    public $kg_status;
    public $kg_comment;
    public $sector_id;
    public $email;
    
    function __construct($kg)    {
        parent::__construct($kg['id'], $kg['code']);
        $this->kg_area = $kg['kg_area'];
        $this->kg_adress = $kg['kg_adress'];
        $this->kg_phones = $kg['kg_phones'];
        $this->sector_id = $kg['sector_id'];
        $this->kg_contact_person = $kg['kg_contact_person'];
        $this->kg_status = $kg['kg_status'];
        $this->email = $kg['email'];
        $this->kg_comment = $kg['kg_comment'];
        $this->relative_props['stocks_info'] = $kg['kg_stocks_info'];
        $this->relative_props['sector_full_name'] = $kg['sector_full_name'];
        $this->relative_props['ready_to_call'] = $kg['ready_to_call'];
        $this->relative_props['ready_to_call_datetime'] = $kg['ready_to_call_datetime'];
        $this->relative_props['kg_childs_middle_count'] = 
                (isset($kg['kg_childs_middle_count'])?
                $kg['kg_childs_middle_count']:null);
    }
}

?>