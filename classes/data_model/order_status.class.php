<?php

/**17.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class OrderStatus extends DataObject  {
    public $order_status_name;
    
    function __construct($order_status)    {
        parent::__construct($order_status['id'], null);
        $this->order_status_name = $order_status['order_status_name'];
    }
}

?>
