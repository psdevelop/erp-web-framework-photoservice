<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class PlotTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "plots", 
            $class_name, "plots"); 
		$this->dict_header = "Сюжеты";
        $this->add_update_procedure_name = ""; 
        $this->insert_instruction_template = "insert into `plots`(`id`,`code`,`plot_name`) values(null,:code,:plot_name);"; 
        $this->update_instruction_template = "update `plots` SET `code`=:code,`plot_name`=:plot_name where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('plot', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
