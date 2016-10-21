<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class OrdersPlots extends DataObject  {
    public $plot_id;
    public $order_id;
    
    function __construct($orders_plots)    {
        parent::__construct($orders_plots['id'], null);
        $this->plot_id = $orders_plots['plot_id'];
        $this->order_id = $orders_plots['order_id'];
    }
}

?>
