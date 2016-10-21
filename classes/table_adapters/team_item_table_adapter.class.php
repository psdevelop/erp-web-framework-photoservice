<?php

/**25/11/2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class TeamItemTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "team_items", 
            $class_name, "team_items_with_relative"); 
        $this->dict_header = "Исполнители задач";
        $this->add_update_procedure_name = ""; 
        
        $this->insert_instruction_template = "SET @fictive=:code; insert into `team_items`(`id`,`person_type_id`, `person_id`, 
            `team_object_id`, `team_type_id`, `action_datetime`) values(null,:person_type_id, :person_id, :team_object_id,
            :team_type_id, :action_datetime);"; 
        $this->update_instruction_template = "----SET @fictive=:code; update `team_items` SET `person_type_id`=:person_type_id,
            `person_id`=:person_id, `team_object_id`=:team_object_id, `team_type_id`=:team_type_id where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('team_item', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
