<?php

/**
 * @author 
 * @copyright 2011
 */

abstract class DataObject   {
    public $id;
    public $code;
    public $relative_props;
    
    function __construct($id, $code)    {
        $this->id = $id;
        $this->code = $code;
        $this->relative_props = array();    
    }
    
    function getId()    {
        return $this->id;
    }
    
    function getPropArray() {
        $prop_array = (array)$this;
        unset($prop_array['relative_props']);
        return $prop_array;
    }
    
    function getFullPropArray() {
        return array_merge($this->getPropArray(), $this->relative_props);
    }
}

?>