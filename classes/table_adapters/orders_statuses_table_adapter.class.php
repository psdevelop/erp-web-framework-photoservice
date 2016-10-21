<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class OrdersStatusesTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "order_statuses_rels", 
            $class_name, "order_statuses_rels"); 
        $this->add_update_procedure_name = ""; 
        $this->insert_instruction_template = "insert into `order_statuses_rels`(`id`,`order_id`,
            `order_status_id`, `order_date`, `comment`) values(null,:order_id,
            :order_status_id,:order_date, :comment); SET @code=:code; update `orders` set 
            order_status_id=:order_status_id, order_comment=
            CONCAT(IFNULL(order_comment,' '),' ',CURRENT_TIMESTAMP(),IFNULL(:comment,' ')) where id=:order_id; SET @new_shoot_id=NULL; 
            call `add_empty_order_shooting` (:order_id, @new_shoot_id, :order_status_id);"; 
        $this->update_instruction_template = "update `order_statuses_rels???` SET `call_id`=:call_id,
            `call_status_id`=:call_status_id, `call_date`=:call_date where `id`=:id;  SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('order_statuses_rels', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
