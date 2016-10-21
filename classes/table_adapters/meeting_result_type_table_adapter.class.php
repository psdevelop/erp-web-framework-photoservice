<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class MeetingResultTypeTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "meeting_results_types", 
            $class_name, "meeting_results_types");
        $this->dict_header = "Типы результатов встреч";
        $this->add_update_procedure_name = "add_update_person"; 
        $this->insert_instruction_template = "SET @fictive=:code; insert into `meeting_results_types`(`id`,`meeting_result_type_name`) values(null,:meeting_result_type_name);"; 
        $this->update_instruction_template = "SET @fictive=:code; update `meeting_results_types` SET `meeting_result_type_name`=:meeting_result_type_name where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('meeting_result_type', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
