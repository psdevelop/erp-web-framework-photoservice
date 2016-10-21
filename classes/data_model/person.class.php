<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once(dirname(__FILE__)."/data_object.class.php");
require_once(dirname(__FILE__)."/person_type.class.php");

class Person extends DataObject  {
    public $person_type_id;
    public $first_name;
    public $last_name;
    public $sur_name;
    public $stationare_phones;
    public $mobile_phones;
    public $employment_date;
    public $dismissal_date;
    
    function __construct($person)    {
        if ($person==null)  {
            
        }
        else    {
            parent::__construct($person['id'],$person['person_code']);
            $this->person_type_id = $person['person_type_id'];
            $this->first_name = $person['first_name'];
            $this->last_name = $person['last_name'];
            $this->sur_name = $person['sur_name'];
            $this->stationare_phones = $person['stationare_phones'];
            $this->mobile_phones = $person['mobile_phones'];
            $this->employment_date = $person['employment_date'];
            $this->dismissal_date = $person['dismissal_date'];
            $this->relative_props['person_type_name'] = $person['person_type_name'];
        }
    }

}

?>