<?php

/** 17.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class OrdersStatuses extends DataObject  {
    public $order_id;
    public $order_status_id;
    public $order_date;
    public $comment;
    
    function __construct($orders_statuses)    {
        parent::__construct($orders_statuses['id'], null);
        $this->order_id = $orders_statuses['order_id'];
        $this->order_status_id = $orders_statuses['order_status_id'];
        $this->order_date = $orders_statuses['order_date'];
        $this->comment = $orders_statuses['comment'];
    }
}

?>
