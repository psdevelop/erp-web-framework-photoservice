<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class PersonTypeTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "person_types", 
            $class_name, "person_types");
        $this->dict_header = "Должности";
        $this->add_update_procedure_name = "";
        
        $this->insert_instruction_template = "insert into `person_types`(`id`,`person_type_name`) values(null,:person_type_name); SET @code=:code;"; 
        $this->update_instruction_template = "update `person_types` SET `person_type_name`=:person_type_name where `id`=:id; SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('person_type', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>