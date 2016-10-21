<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class StockTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "stocks", 
            $class_name, "stocks");
		$this->dict_header = "Акции";
        $this->add_update_procedure_name = ""; 
        $this->insert_instruction_template = "insert into `stocks`(`id`,`code`,`stock_name`) values(null,:code,:stock_name);"; 
        $this->update_instruction_template = "update `stocks` SET `code`=:code,`stock_name`=:stock_name where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('stock', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
