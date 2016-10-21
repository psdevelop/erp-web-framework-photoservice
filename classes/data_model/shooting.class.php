<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class Shooting extends DataObject  {
    public $order_id;
    public $manager_id;
    public $stock_id;
    public $shooting_date;
    public $shooting_time;
    public $child_count;
    
    function __construct($shooting)    {
        parent::__construct($shooting['id'], null);
        $this->order_id = $shooting['order_id'];
        $this->manager_id = $shooting['manager_id'];
        $this->stock_id = $shooting['stock_id'];
        
        $this->shooting_date = $shooting['shooting_date'];
        $this->shooting_time = $shooting['shooting_time'];
        $this->child_count = $shooting['child_count'];
        $this->relative_props['manager_name'] = $shooting['manager_name'];
        $this->relative_props['order_name'] = $shooting['order_name'];
        $this->relative_props['stock_name'] = $shooting['stock_name'];
        $this->relative_props['plot_name'] = $shooting['plot_name'];
        $this->relative_props['kg_id'] = $shooting['kg_id'];
        $this->relative_props['kg_name'] = $shooting['kg_name'];
        $this->relative_props['real_count'] = $shooting['real_count'];
        $this->relative_props['back_count'] = $shooting['back_count'];
        $this->relative_props['shooting_status_name'] = $shooting['shooting_status_name'];
        $this->relative_props['shooting_comment'] = $shooting['shooting_comment'];
        $this->relative_props['identity_name'] = $shooting['shooting_date']."/".
                $shooting['shooting_time']." ".$shooting['kg_name']." ".$shooting['stock_name'].
            " ".$shooting['manager_name'];
        $this->relative_props['state_id'] = $shooting['state_id'];
        $this->relative_props['teams_items_names'] = $shooting['teams_items_names'];
        $this->relative_props['handling_count'] = $shooting['handling_count'];
	$this->relative_props['print_count'] = $shooting['print_count'];
	$this->relative_props['to_client_count'] = $shooting['to_client_count'];
        $this->relative_props['full_kompl_count'] = $shooting['full_kompl_count'];
        $this->relative_props['big_photos_count'] = $shooting['big_photos_count'];
        $this->relative_props['small_photos_count'] = $shooting['small_photos_count'];
    }
}

?>