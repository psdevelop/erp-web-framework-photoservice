<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");
require_once("classes/action_classess/operation.class.php");
require_once("classes/action_classess/action.class.php");

class MeetingTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "meetings", 
            $class_name, "meetings_with_relative"); 
        $this->dict_header = "Встречи";
        $this->add_update_procedure_name = "add_update_person";
        //(meeting_result_type_id<>{$GLOBALS['order_meet_status']}) 
        //AND 
        $this->base_filter = "((meeting_result_type_id<>{$GLOBALS['back_to_operator_status']}) OR ISNULL(meeting_result_type_id) OR (meeting_result_type_id<1))";
        
        $this->filters_array = array("operator_id"=>"Person", //"call_id"=>"Call",
            "manager_id"=>"Person","meeting_result_type_id"=>"MeetingResultType");
        $this->values_filter_array = array("start_meeting_date"=>"IF(ISNULL(meeting_date),(1=1),(meeting_date>='***___start_meeting_date'))",
            "end_meeting_date"=>"IF(ISNULL(meeting_date),(1=1),(meeting_date<='***___end_meeting_date'))", 
            "district_id"=>"(district_id=***___district_id)",
            "sector_operator_id"=>"sector_operator_id=***___sector_operator_id",
            "sector_manager_id"=>"sector_manager_id=***___sector_manager_id",
            "meet_empty_status"=>"(ISNULL(meeting_result_type_id) OR (meeting_result_type_id<1) OR (***___meet_empty_status<>1)) ");
        $this->filters_values = array("operator_id"=>null, //"call_id"=>null, 
            "manager_id"=>null,"meeting_result_type_id"=>null);
        $this->filter_values_select_keys = array("district_id"=>"District",
            "sector_operator_id"=>"Person","sector_manager_id"=>"Person");
        $this->values_filter_values = array("end_meeting_date"=>date('Y-m-d',$this->DateAdd('d', 30, time())),
            "start_meeting_date"=>null,//date('Y-m-d',$this->DateAdd('d', -30, time())), 
            "district_id"=>null,
            "sector_operator_id"=>null,"sector_manager_id"=>null,
            "meet_empty_status"=>null);
        $this->filters_key_filters = array("operator_id"=>"(person_type_id=1)", "manager_id"=>"(person_type_id=2)");
        //$this->linked_entities_keys = array("MeetingResult"=>array("id"=>"meeting_id"));
        //$this->linked_entities_names = array("MeetingResult"=>"Результаты");
        //$this->linked_form_heights = array("MeetingResult"=>190);
        $this->linked_detail_entities = array("Shooting"=>array("kg_id"=>"kg_id=***___kg_id"),
            "KinderGarten"=>array("kg_id"=>"id=***___kg_id"));
        $this->detail_info_template = "<table style=\"width:600px;\"><tr><td colspan=\"2\"><table><tr><td style=\"font-size:16px;\"><b>Детальная информация о встрече:</b></td></tr><tr><td>***___SELF</td></tr></table></td></tr>
            <tr>
            <td><table><tr><td style=\"font-size:16px;\"><b>Информация о ДС:</b></td></tr><tr><td>***___KinderGarten</td></tr></table></td>
            <td><table><tr><td style=\"font-size:16px;\"><b>Съемки по этому ДС:</b></td></tr><tr><td>***___Shooting</td></tr></table></td></tr></table>";
        
        $this->insert_instruction_template = "SET @pid=NULL; call `add_update_meeting` (:operator_id,:call_id,:manager_id,:meeting_date,
		      :meeting_time,@pid); SET @code=:code;"; 
        $this->update_instruction_template = "SET @pid=:id; call `add_update_meeting` (:operator_id,:call_id,:manager_id,:meeting_date,
		      :meeting_time,@pid); SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('meeting', :id, @dcount);";
    
        $SetKGReadyStatusOperation = new Operation($dbconnector, 
                "set_kg_ready_status_operation", 
                "KinderGarten", 
                $GLOBALS['external_params_update_type'], 
                "Операция установки готовности ДС", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("kg_id"=>"id", "ready_to_call"=>"ready_to_call",
                    "ready_to_call_datetime"=>"ready_to_call_datetime"), //inline_props
                null
                );
        $AbortMeetingSetStatusOperation = new Operation($dbconnector, 
                "abort_meeting_operation", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса отказа от встречи", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['abort_meet_status']), 
                array(), //inline_props
                null
                );
        $AbortShootSetStatusOperation = new Operation($dbconnector, 
                "abort_shoot_meet_operation", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса отказа от съемки", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['shoot_abort_meet_status']), 
                array(), //inline_props
                null
                );
        $OrderSetStatusOperation = new Operation($dbconnector, 
                "order_meet_operation", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса заказа", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['order_meet_status']), 
                array(), //inline_props
                null
                );
        $RepeatCallSetDateOperation = new Operation($dbconnector, 
                "repeat_meet_call_date_operation", 
                "Meeting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки даты перезвона по встрече", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("repeat_meet_call_datetime"=>"repeat_meet_call_datetime"), //inline_props
                $this
                );
        $RepeatCallMeetSetStatusOperation = new Operation($dbconnector, 
                "repeat_call_meet_operation", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса перезвона по встрече", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['recall_meet_status']), 
                array(), //inline_props
                null
                );
        $RepositionMeetSetDateOperation = new Operation($dbconnector, 
                "rpsmdo", 
                "Meeting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки даты переноса встречи", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("repeat_meet_datetime"=>"repeat_meet_datetime"), //inline_props
                $this
                );
        $RepositionMeetSetStatusOperation = new Operation($dbconnector, 
                "rpsmo", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса переноса встречи", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['meet_reposition_status']), 
                array(), //inline_props
                null
                );
        $IndividualMeetSetStatusOperation = new Operation($dbconnector, 
                "indsmo", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса индивидуального случая встречи", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['individual_meet_status']), 
                array(), //inline_props
                null
                );
        $PoolMeetSetStatusOperation = new Operation($dbconnector, 
                "plsmo", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса опроса встречи", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['pool_meet_status']), 
                array(), //inline_props
                null
                );
        
        $SetKGParamsOperation = new Operation($dbconnector, 
                "stkgprm", 
                "KinderGarten", 
                $GLOBALS['external_params_update_type'], 
                "Операция установки параметров ДС", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("kg_id"=>"id", "ready_to_call"=>"ready_to_call",
                    "ready_to_call_datetime"=>"ready_to_call_datetime",
                    "kg_adress"=>"kg_adress", 
                    "kg_phones"=>"kg_phones", "kg_status"=>"kg_status",
                    "kg_contact_person"=>"kg_contact_person",
                    "kg_comment"=>"kg_comment"), //inline_props
                null
                );
        
        $BackToOperatorSetStatusOperation = new Operation($dbconnector, 
                "bcktoop", 
                "MeetingResult", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления возврата встречи ОПЕРАТОРУ", 
                array("id"=>"meeting_id"), 
                array(), //form_input_params
                array("meeting_result_type_id"=>$GLOBALS['back_to_operator_status']), 
                array(), //inline_props
                null
                );
        
        $MeetOnControlSetOperation = new Operation($dbconnector, 
                "on_control_meet_set_operation", 
                "Meeting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки На контроль", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("on_control"=>"on_control"), //inline_props
                $this
                );
        
        
        $AbortMeetingSetStatusOperation->acceptCustomHiddenKeys(
                array("planned_shooting_date"=>"hidden","plot_id"=>"hidden",
                    "stock_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        $AbortShootSetStatusOperation->acceptCustomHiddenKeys(
                array("planned_shooting_date"=>"hidden","plot_id"=>"hidden",
                    "stock_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        $OrderSetStatusOperation->acceptCustomHiddenKeys(
                array("plot_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        $OrderSetStatusOperation->showCustomHiddenKeys(
                array("plots_array"=>"showed", "planned_child_count"=>"showed", 
                "planned_group_count"=>"showed", "planned_small_gr_count"=>"showed", 
                "planned_shooting_place"=>"showed"));
        $RepeatCallMeetSetStatusOperation->acceptCustomHiddenKeys(
                array("planned_shooting_date"=>"hidden","plot_id"=>"hidden",
                    "stock_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        $RepositionMeetSetStatusOperation->acceptCustomHiddenKeys(
                array("planned_shooting_date"=>"hidden","plot_id"=>"hidden",
                    "stock_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        $IndividualMeetSetStatusOperation->acceptCustomHiddenKeys(
                array("planned_shooting_date"=>"hidden","plot_id"=>"hidden",
                    "stock_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        $PoolMeetSetStatusOperation->acceptCustomHiddenKeys(
                array("planned_shooting_date"=>"hidden",
                    "stock_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        $BackToOperatorSetStatusOperation->acceptCustomHiddenKeys(
                array("planned_shooting_date"=>"hidden","plot_id"=>"hidden",
                    "stock_id"=>"hidden", "meeting_result_type_id"=>"hidden"));
        
        $AbortMeetingAction = new Action("Отказ<br/>от встречи", array(
            $SetKGParamsOperation, $AbortMeetingSetStatusOperation), "AbMA",
            "#808080", $this);
        $AbortShootMeetingAction = new Action("Отказ<br/>от съемки", array(
            $SetKGParamsOperation, $AbortShootSetStatusOperation), "ASMA",
            "#808080", $this);
        $BackToOperatorAction = new Action("Возврат<br/>ОПЕРАТОРУ", array(
            $SetKGParamsOperation, $BackToOperatorSetStatusOperation,
            $RepositionMeetSetDateOperation), "BOpMA",
            "#00FF00", $this);
        //$SetKGReadyStatusAction = new Action("Готовн.", array(
        //    $SetKGReadyStatusOperation), "MSRKGA",
        //    "#00FF00", $this);
        $OrderMeetingAction = new Action("Заказ", array(
            $OrderSetStatusOperation, $SetKGParamsOperation), "OrMA", "#00FF00", $this);
        $RepeatMeetCallAction = new Action("Перезв.", array(
            $RepeatCallMeetSetStatusOperation, $RepeatCallSetDateOperation), "RCMA", "#FF0000", $this);
        $RepositionMeetAction = new Action("Перенос", array(
            $RepositionMeetSetStatusOperation, $RepositionMeetSetDateOperation,
            $SetKGParamsOperation), "RPMA", "#FF0000", $this);
        $IndividualMeetAction = new Action("Индивид.", array(
            $IndividualMeetSetStatusOperation), "INDMA", "#FF0000", $this);
        $PoolMeetAction = new Action("Опрос", array(
            $PoolMeetSetStatusOperation), "PLMA", "#00FFFF", $this);
        $SetKGReadyStatusAction = new Action("Данные ДС", array(
            $SetKGParamsOperation), "StRKGA",
            "#00FF00", $this);
        $MeetOnControlSetAction = new Action("На контроль", array(
            $MeetOnControlSetOperation), "ONCMA", "#FF0000", $this);
        $this->record_actions = array($AbortMeetingAction, $AbortShootMeetingAction,
            $OrderMeetingAction, $RepeatMeetCallAction,
            $RepositionMeetAction, $IndividualMeetAction, $PoolMeetAction,
            $SetKGReadyStatusAction, $BackToOperatorAction, $MeetOnControlSetAction);
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
