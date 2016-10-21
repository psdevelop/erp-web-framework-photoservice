<?php

/**
 * @author 
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class Stock extends DataObject  {
    public $stock_name;
    
    function __construct($stock)    {
        parent::__construct($stock['id'], $stock['code']);
        $this->stock_name = $stock['stock_name'];
    }
}

?>