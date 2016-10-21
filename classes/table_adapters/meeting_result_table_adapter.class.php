<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class MeetingResultTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "meetings_results_with_relative", 
            $class_name, "meetings_results_with_relative");
        $this->dict_header = "Результаты встреч";
        $this->add_update_procedure_name = "add_update_person";
        $this->filters_array = array("manager_id"=>"Person", "plot_id"=>"Plot",
            "stock_id"=>"Stock", "meeting_result_type_id"=>"MeetingResultType");
        $this->filters_values = array("manager_id"=>null, "plot_id"=>null,
            "stock_id"=>null, "meeting_result_type_id"=>null);
        $this->filters_key_filters = array("manager_id"=>"(person_type_id=2)");
        
        $this->insert_instruction_template = "SET @pid=NULL; call `add_update_meeting_result` (:meeting_id,:stock_id,:plot_id,
            :meeting_result_type_id, 0,0,:planned_shooting_date, :meeting_result_comment,@pid, :meeting_date); 
            SET @code=:code; UPDATE `meetings` SET meeting_result_type_id=:meeting_result_type_id, 
            meeting_comment=CONCAT(IFNULL(meeting_comment,' '),' ',CURRENT_TIMESTAMP(),:meeting_result_comment)  WHERE 
            id=:meeting_id; SET @new_order_id=NULL; call `add_empty_meeting_order`(:meeting_id, @new_order_id, :meeting_result_type_id, 
            :stock_id, :planned_shooting_date, :plot_id, 
            :session_operator_id, :session_manager_id, :meeting_result_comment); UPDATE orders 
            SET planned_child_count=:planned_child_count, group_count=:planned_group_count, 
            little_group_count=:planned_small_gr_count, shooting_place=:planned_shooting_place 
            WHERE (id=@new_order_id) AND NOT ISNULL(@new_order_id); "; 
        $this->default_insert_session_params = array(":session_operator_id"=>"operator_id",
            ":session_manager_id"=>"manager_id");
        $this->multi_addit_instructions = array(":plots_array"=>" INSERT INTO orders_plots_rels (order_id,
            plot_id) VALUES (@new_order_id, ***___:plots_array); ");
        $this->update_instruction_template = "---SET @pid=:id; call `add_update_meeting_result` (:meeting_id,:stock_id,:plot_id,
            :meeting_result_type_id, 0,0,:planned_shooting_date, :meeting_result_comment,@pid, :meeting_date); SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('meeting_result', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
