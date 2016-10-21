<?php

/**20.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class ShootingStatusTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "shooting_statuses", 
            $class_name, "shooting_statuses"); 
        $this->dict_header = "Статусы съемок";
        $this->add_update_procedure_name = "add_update_person"; 
        $this->insert_instruction_template = "SET @fictive=:code; insert into `shooting_statuses`(`id`,`shooting_status_name`) values(null,:shooting_status_name);"; 
        $this->update_instruction_template = "SET @fictive=:code; update `shooting_statuses` SET `shooting_status_name`=:shooting_status_name where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('shooting_status', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
