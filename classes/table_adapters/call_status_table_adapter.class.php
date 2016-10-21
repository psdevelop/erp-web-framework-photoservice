<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class CallStatusTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "call_statuses", 
            $class_name, "call_statuses"); 
        $this->dict_header = "Статусы звонков";
        $this->add_update_procedure_name = "add_update_person"; 
        $this->insert_instruction_template = "SET @fictive=:code; insert into `call_statuses`(`id`,`call_status_name`) values(null,:call_status_name);"; 
        $this->update_instruction_template = "SET @fictive=:code; update `call_statuses` SET `call_status_name`=:call_status_name where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('call_status', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
