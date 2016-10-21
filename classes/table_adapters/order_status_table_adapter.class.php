<?php

/**17.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class OrderStatusTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "order_statuses", 
            $class_name, "order_statuses");
        $this->dict_header = "Статусы заказов";
        $this->add_update_procedure_name = "add_update_person"; 
        $this->insert_instruction_template = "SET @fictive=:code; insert into `order_statuses`(`id`,`order_status_name`) values(null,:order_status_name);"; 
        $this->update_instruction_template = "SET @fictive=:code; update `order_statuses` SET `order_status_name`=:order_status_name where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('order_status', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>