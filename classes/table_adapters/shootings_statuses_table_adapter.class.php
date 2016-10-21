<?php

/**14.12.2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class ShootingsStatusesTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "shooting_statuses_rels", 
            $class_name, "shooting_statuses_rels"); 
        $this->add_update_procedure_name = ""; 
        $this->insert_instruction_template = "insert into `shooting_statuses_rels`(`id`,`shooting_id`,
            `shooting_status_id`, `shooting_date`, `comment`) values(null,:shooting_id,
            :shooting_status_id,:shooting_date, :comment); SET @code=:code; update `shootings` set 
            shooting_status_id=:shooting_status_id, shooting_comment=
            CONCAT(IFNULL(shooting_comment,' '),' ',CURRENT_TIMESTAMP(),:comment) where id=:shooting_id; 
            SET @sh_order_id=NULL; SELECT order_id INTO @sh_order_id FROM shootings WHERE id=:shooting_id; 
            UPDATE orders SET shooting_status_id=:shooting_status_id WHERE id=@sh_order_id;"; 
        $this->update_instruction_template = "update `order_statuses_rels???` SET `call_id`=:call_id,
            `call_status_id`=:call_status_id, `call_date`=:call_date where `id`=:id;  SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('shooting_statuses_rels', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
