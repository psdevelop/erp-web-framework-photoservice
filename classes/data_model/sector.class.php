<?php

/**27.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class Sector extends DataObject  {
    public $operator_id;
    public $district_id;
    public $manager_id;
    public $sector_name;
    
    function __construct($sector)    {
        parent::__construct($sector['id'], null);
        $this->operator_id = $sector['operator_id'];
        $this->district_id = $sector['district_id'];
        $this->manager_id = $sector['manager_id'];
        $this->sector_name = $sector['sector_name'];
        $this->relative_props['operator_name'] = $sector['operator_name'];
        $this->relative_props['manager_name'] = $sector['manager_name'];
        $this->relative_props['district_name'] = $sector['district_name'];
    }
}

?>
