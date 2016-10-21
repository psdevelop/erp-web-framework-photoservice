<?php

/**
 * @author 
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class Plot extends DataObject  {
    public $plot_name;
    
    function __construct($plot)    {
        parent::__construct($plot['id'], $plot['code']);
        $this->plot_name = $plot['plot_name'];
    }
}

?>