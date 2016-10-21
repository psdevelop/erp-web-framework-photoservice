<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class StateTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "states", 
            $class_name, "states");
        
        $this->add_update_procedure_name = "add_update_state"; 
        $this->insert_instruction_template = " SET @pid=NULL; call `add_update_state`(:state_name, @pid); SET @fictive=:code;"; 
        $this->update_instruction_template = "SET @pid=:id; call `add_update_state`(:state_name, @pid); SET @fictive=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('district', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
