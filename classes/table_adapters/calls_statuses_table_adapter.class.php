<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class CallsStatusesTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "call_statuses_rels", 
            $class_name, "call_statuses_rels"); 
        $this->add_update_procedure_name = ""; 
        $this->insert_instruction_template = "insert into `call_statuses_rels`(`id`,`call_id`,`call_status_id`, `call_date`, `comment`, `meet_datetime`) 
            values(null,:call_id,:call_status_id,:call_date, :comment, :meet_datetime); SET @code=:code; update `calls` set 
            call_status_id=:call_status_id, calls_comment=CONCAT(IFNULL(calls_comment,' '),' ',:comment) 
            where id=:call_id; SET @new_meet_id=NULL; 
            call `add_empty_call_meeting` (:call_id, @new_meet_id, :call_status_id, 
            :session_operator_id, :session_manager_id, :meet_datetime, :comment); UPDATE `calls` SET 
            `status_datetime`=ifnull(:call_date, CURRENT_TIMESTAMP), `meeting_datetime`=:meet_datetime WHERE id=:call_id;"; 
        $this->default_insert_session_params = array(":session_operator_id"=>"operator_id",
            ":session_manager_id"=>"manager_id");
        $this->update_instruction_template = "update `call_statuses_rels` SET `call_id`=:call_id,
            `call_status_id`=:call_status_id, `call_date`=:call_date, `meet_datetime`=:meet_datetime where `id`=:id;  SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` 
            ('call_statuses_rels', :id, @dcount);";
        $this->custom_action_instructions = array("append_call_repeat_status"=>"insert into `call_statuses_rels`(`id`,`call_id`,`call_status_id`, `call_date`) 
            values(null,:call_id,'{$GLOBALS['repeat_call_status_id']}',CURRENT_TIMESTAMP());");
        $this->custom_action_params = array("append_call_repeat_status"=>array("call_id"=>"call_id"));
        $this->short_name = "CSS";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>