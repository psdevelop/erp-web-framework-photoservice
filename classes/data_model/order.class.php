<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class Order extends DataObject  {
    //public $plot_id;
    public $kg_id;
    public $manager_id;
    public $stock_id;
    public $order_date;
    public $shooting_date;
    public $shooting_time;
    public $planned_child_count;
    public $order_comment;
    public $shooting_place;
    public $group_count;
    public $little_group_count;
    
    function __construct($order)    {
        parent::__construct($order['id'], null);
        //$this->plot_id = $order['plot_id'];
        $this->kg_id = $order['kg_id'];
        $this->manager_id = $order['manager_id'];
        $this->stock_id = $order['stock_id'];
        $this->order_date = $order['order_date'];
        $this->shooting_date = $order['shooting_date'];
        $this->shooting_time = $order['shooting_time'];
        $this->planned_child_count = $order['planned_child_count'];
        $this->order_comment = $order['order_comment'];
        $this->shooting_place = $order['shooting_place'];
        $this->group_count = $order['group_count'];
        
        $this->little_group_count = $order['little_group_count'];
        $this->relative_props['kg_name'] = $order['kg_name'];
        $this->relative_props['plot_name'] = $order['plot_name'];
        $this->relative_props['stock_name'] = $order['stock_name'];
        $this->relative_props['manager_name'] = $order['manager_name'];
        $this->relative_props['kg_status'] = $order['kg_status'];
        $this->relative_props['kg_comment'] = $order['kg_comment'];
        $this->relative_props['kg_adress'] = $order['kg_adress'];
        $this->relative_props['kg_phones'] = $order['kg_phones'];
        $this->relative_props['kg_contact_person'] = $order['kg_contact_person'];
        $this->relative_props['kg_code'] = $order['kg_code'];
        $this->relative_props['district_id'] = $order['district_id'];
        $this->relative_props['state_id'] = $order['state_id'];
        $this->relative_props['email'] = $order['email'];
        $this->relative_props['order_status_id'] = $order['order_status_id'];
        $this->relative_props['order_status_name'] = $order['order_status_name'];
        $this->relative_props['order_statuses_names'] = $order['order_statuses_names'];
        $this->relative_props['repeat_call_datetime'] = $order['repeat_call_datetime'];
        $this->relative_props['our_fault'] = $order['our_fault'];
        $this->relative_props['their_fault'] = $order['their_fault'];
        $this->relative_props['ready_to_call'] = $order['ready_to_call'];
        $this->relative_props['ready_to_call_datetime'] = $order['ready_to_call_datetime'];
        if (isset($order['shooting_status_name']))
            $this->relative_props['shooting_status_name'] = $order['shooting_status_name'];
        else
            $this->relative_props['shooting_status_name'] = "";
        $this->relative_props['identity_name'] = $order['order_name'];
        $this->relative_props['teams_items_names'] = $order['teams_items_names'];
    }
}

?>