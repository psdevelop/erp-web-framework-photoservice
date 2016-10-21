<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once(dirname(__FILE__)."/data_object.class.php");

class PersonType extends DataObject  {
    public $person_type_name;
    
    function __construct($person_type)    {
        parent::__construct($person_type['id'], null);
        $this->person_type_name = $person_type['person_type_name'];
    }
}
?>