<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class District extends DataObject  {
    public $state_id;
    public $district_name;
    
    function __construct($district)    {
        parent::__construct($district['id'], null);
        $this->state_id = $district['state_id'];
        $this->district_name = $district['district_name'];
        $this->relative_props['district_st_name'] = $district['district_st_name'];
    }
}
?>
