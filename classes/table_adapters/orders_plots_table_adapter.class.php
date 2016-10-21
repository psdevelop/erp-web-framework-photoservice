<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class OrdersPlotsTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "orders_plots_rels", 
            $class_name, "orders_plots_rels"); 
        $this->add_update_procedure_name = ""; 
        $this->insert_instruction_template = "insert into `orders_plots_rels`(`id`,`order_id`,`plot_id`) values(null,:order_id,:plot_id); SET @code=:code;"; 
        $this->update_instruction_template = "update `orders_plots_rels` SET `order_id`=:order_id,`plot_id`=:plot_id where `id`=:id;  SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('orders_plots_rels', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
