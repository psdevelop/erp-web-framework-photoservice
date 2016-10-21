<?php

/**24/11/2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class TeamTypeTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "team_types", 
            $class_name, "team_types"); 
        $this->dict_header = "Виды задач";
        $this->add_update_procedure_name = "add_update_person"; 
        $this->insert_instruction_template = "SET @fictive=:code; insert into `team_types`(`id`,`team_type_name`) values(null,:team_type_name);"; 
        $this->update_instruction_template = "SET @fictive=:code; update `team_types` SET `team_type_name`=:team_type_name where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `team_types` ('team_type', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
