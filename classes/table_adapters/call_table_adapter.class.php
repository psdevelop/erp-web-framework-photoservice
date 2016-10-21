<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");
require_once("classes/view_forms/ajaxed_object.class.php"); 
require_once("classes/view_forms/ajaxed_object_sequency.class.php");
require_once("classes/view_forms/custom_manip_object.class.php");
require_once("classes/action_classess/operation.class.php");
require_once("classes/action_classess/action.class.php");

class CallTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name, $recursive_mode=false, $suffix=null)    { 
        parent::__construct($dbconnector, "calls", 
            $class_name, "calls_with_relative"); 
        $this->dict_header = "Звонки";
        $this->add_update_procedure_name = "add_update_person";
        $this->detail_view_name = "calls_with_relative_detail";
        $this->part_capacity = 10;
        if (isset($suffix))
            $this->assignNumSuffix($suffix);
        $this->recursive_mode = $recursive_mode;
        $this->filters_array = array("operator_id"=>"Person",
             "stock_id"=>"Stock");
        $this->base_filter = "((meeting_status_id<>{$GLOBALS['order_meet_status']}) OR 
            ISNULL(meeting_status_id) OR (meeting_status_id<1)) AND 
            ( ((call_status_id<>{$GLOBALS['abort_call_status_id']}) AND 
			(call_status_id<>{$GLOBALS['arhived_call_status_id']})) OR ISNULL(call_status_id) OR (call_status_id<1)) ";
        //$this->report_mode_base_filter = "  ";
        //$CallGroupReport = new CallGroupReport($dbconnector);
        $this->detailed_reports = array("call_statuses_pie"=>"CallGroup");
        $this->values_filter_array = array("start_call_date"=>"(call_date>='***___start_call_date 00:00:00')",
            "end_call_date"=>"(call_date<='***___end_call_date 23:59:59')",
            "like_str"=>"(LOWER(call_name) LIKE LOWER('%***___like_str%') )",
            "calls_statuses_id"=>"(***___calls_statuses_id IN (SELECT call_status_id FROM call_statuses_rels csr 
                WHERE csr.call_id=calls_with_relative.id))", "district_id"=>"(district_id=***___district_id)",
            "sector_operator_id"=>"sector_operator_id=***___sector_operator_id",
            "sector_manager_id"=>"sector_manager_id=***___sector_manager_id", 
            "call_status_id"=>"call_status_id=***___call_status_id", 
            "repeat_call_datetime"=>" ( (repeat_call_datetime<='***___repeat_call_datetime') AND 
                ((call_status_id={$GLOBALS['repeat_call_status_id']}) OR 
                (call_status_id={$GLOBALS['noanswer_call_status_id']})) ) ",
            "meeting_status_id"=>"meeting_status_id=***___meeting_status_id",
            "ready_to_call_datetime"=>" (ready_to_call_datetime<='***___ready_to_call_datetime') AND (ready_to_call='1') AND NOT ISNULL(ready_to_call_datetime) ",
            "middle_childs_count"=>" (f_get_kg_middle_ariphm_childs(kg_id, NULL, NULL)<=***___middle_childs_count) ",
            "call_kg_status"=>"((LOWER(kg_status) LIKE 'постоян%') AND (***___call_kg_status=1)) OR (***___call_kg_status<>1) ",
            "empty_call_stock"=>" (ISNULL(stock_id) OR (***___empty_call_stock<>1)) " );
        //$this->base_or_condition_params = array("start_call_date"=>"true");
        $this->filters_values = array("operator_id"=>((array_key_exists('operator_id', $_SESSION)?($_SESSION['operator_id']>0?$_SESSION['operator_id']:null):null)),"stock_id"=>null);
        $this->filter_values_select_keys = array("calls_statuses_id"=>"CallStatus", "district_id"=>"District",
            "sector_operator_id"=>"Person","sector_manager_id"=>"Person", 
            "call_status_id"=>"CallStatus", "meeting_status_id"=>"MeetingResultType");
        $this->values_filter_values = array("end_call_date"=>null,//date('Y-m-d',time()),
            "start_call_date"=>null,//date('Y-m-d',$this->DateAdd('d', -30, time())),
            "like_str"=>null, "repeat_call_datetime"=>null,
            "calls_statuses_id"=>null, "district_id"=>null,
            "sector_operator_id"=>null,"sector_manager_id"=>null,
            "call_status_id"=>null, "meeting_status_id"=>null, 
            "ready_to_call_datetime"=>null, "middle_childs_count"=>null,
            "call_kg_status"=>null, "empty_call_stock"=>null);
        $this->filters_key_filters = array("operator_id"=>"(person_type_id=1)");
        //$this->linked_entities_keys = array("CallsStatuses"=>array("id"=>"call_id"));
        //$this->linked_entities_names = array("CallsStatuses"=>"Статусы");
        //$this->linked_form_heights = array("CallsStatuses"=>80);
        //$this->inline_input_properties = array("repeat_call_datetime"=>"repeat_call_datetime");
        $this->custom_order_filter_fields = array
                ("call_status_id"=>array
                    ("filter_field"=>"call_status_id",
                    "filter_value"=>$GLOBALS['repeat_call_status_id'],
                    "order_expression"=>" repeat_call_datetime ASC "),
                 "abort_meeting_status_id"=>array
                    ("filter_field"=>"meeting_status_id",
                    "filter_value"=>$GLOBALS['abort_meet_status'],
                    "order_expression"=>" ready_to_call_datetime ASC "),
                 "shoot_abort_meeting_status_id"=>array
                    ("filter_field"=>"meeting_status_id",
                    "filter_value"=>$GLOBALS['shoot_abort_meet_status'],
                    "order_expression"=>" ready_to_call_datetime ASC ")
            );
        $this->linked_detail_entities = array("Shooting"=>array("kg_id"=>"kg_id=***___kg_id"),
            "KinderGarten"=>array("kg_id"=>"id=***___kg_id"));
        $this->detail_info_template = "<table style=\"width:600px;\"><tr><td colspan=\"2\"><table><tr><td style=\"font-size:16px;\"><b>Детальная информация о звонке:</b></td></tr><tr><td>***___SELF</td></tr></table></td></tr>
            <tr>
            <td><table><tr><td style=\"font-size:16px;\"><b>Информация о ДС:</b></td></tr><tr><td>***___KinderGarten</td></tr></table></td>
            <td><table><tr><td style=\"font-size:16px;\"><b>Съемки по этому ДС:</b></td></tr><tr><td>***___Shooting</td></tr></table></td></tr></table>";
        
        //$kg_search_ready_checkbox_html = $this->get_input_checkbox("kg_search_ready_checkbox", 
        //        10, "1", "Готовые");
        //$kg_search_object_sequence_template="<div class=\"sequence_form_default\">
        //    Панель поиска ДС:<table border=\"0\"><tr><td>***___State</td>
        //    <td>***___District</td><td>{$kg_search_ready_checkbox_html}</td></tr>
        //    <tr><td colspan=\"3\">***___KinderGarten</td></tr></table></div>";
        //$StatesAjaxedObject = new AjaxedObject("calls_kg_state_search", $GLOBALS['active_cont_select_type'],
        //        1, "State", array(), array(), array(), "Область ДС");
        //$StatesAjaxedObject->load_after_write_html = true;
        //$DistrictsAjaxedObject = new AjaxedObject("calls_kg_district_search", $GLOBALS['active_cont_select_type'],
        //        2, "District", array("state_id"=>"calls_kg_state_search"), array(1), array(1), "Округ ДС");
        //$KGSAjaxedObject = new AjaxedObject("calls_kg_kg_search", $GLOBALS['active_cont_select_type'],
        //        3, "KinderGarten", array("district_id"=>"calls_kg_district_search",
        //            "ready_to_call"=>"kg_search_ready_checkbox"), array(2), array(1,2), "Найденые ДС");
        //$KGSearchObjectsSequency = new AjaxedObjectsSequency(array($StatesAjaxedObject, 
        //    $DistrictsAjaxedObject, $KGSAjaxedObject), $kg_search_object_sequence_template);
        
        $kg_list_ready_checkbox_html = $this->get_input_checkbox("kg_list_ready_checkbox", 
                10, "1", "Готовность");
        $clear_button_code = "<span id=\"anchor_ready_to_call_datetime_ready_search\">
            </span><a href=\"#anchor_ready_to_call_datetime_ready_search\" onClick=\" document.
            getElementById('ready_to_call_datetime_ready_search').value='';\">
            <img src=\"images/clear.jpg\"></a>";
        $ready_to_call_datetime_html = $this->get_input_text_with_class_and_placement
                ("ready_to_call_datetime_ready_search", 20, date('Y-m-d', time()), 
                "Дет. сад готов до срока: ","date_cont_div","horizontal");
        $middle_childs_count_html = $this->get_input_text_with_class_and_placement
                ("middle_childs_count_ready_search", 20, null, 
                "Средне кол-во детей ранее <=: ","","horizontal");
        $stable_kg_status_html = $this->get_input_checkbox("stable_kg_status_ready_checkbox", 
                10, null, "Только постоянные");
                ////$this->object_adapter->
                //write_input_text_field_with_num_and_placement("ready_to_call_datetime",
                        //null, "_ready_search", "horisontal");
        $kg_ready_list_sequence_template=$this->getSlidePanelId( "id_list_form_panel", 
                    "id_list_form_handle", "list_form_panel", 
                    "list_form_handle", "images/button.gif",122,40,50, "true", "right",
                    " Список срочных ДС (предел выборки 50):<br/>
                     <b><span style=\"font-size:16px;\">В первую очередь и обязательно выбираем оператора района</span></b><table border=\"0\">
                    <tr><td>{$kg_list_ready_checkbox_html}</td></tr>
                    <tr><td>{$ready_to_call_datetime_html}</td></tr>
                    <tr><td>{$stable_kg_status_html}</td></tr>
                    <tr><td>{$middle_childs_count_html}</td></tr>
                    <tr><td>***___Stock</td></tr>
                    <tr><td>***___Person</td></tr>
                    <tr><td>***___District</td></tr>
                    <tr><td><div class=\"list_form_default\">***___KinderGarten</td><tr></table>");
        $ReadyKGDistrictsAjaxedObject = new AjaxedObject("ready_kg_district_list", $GLOBALS['active_cont_select_type'],
                1, "District", array(), array(), array(), "Округ ДС");
        $ReadyKGDistrictsAjaxedObject->load_after_write_html = true;
        $NotInStocksAjaxedObject = new AjaxedObject("not_in_stock_list", $GLOBALS['active_cont_select_type'],
                2, "Stock", array(), array(), array(), "Нет звонка по акции");
        $NotInStocksAjaxedObject->load_after_write_html = true;
        $DistrictOperatorAjaxedObject = new AjaxedObject("distr_operator_list", $GLOBALS['active_cont_select_type'],
                3, "Person", array("person_type_id"=>1), array(), array(), "Оператор района", false, "operator_mode");
        $DistrictOperatorAjaxedObject->load_after_write_html = true;
        $ReadyKGSListAjaxedObject = new AjaxedObject("ready_kg_kg_list", $GLOBALS['active_list_div_type'],
                4, "KinderGarten", array("district_id"=>"ready_kg_district_list",
                    "ready_to_call"=>"kg_list_ready_checkbox",
                    "ready_to_call_datetime"=>"ready_to_call_datetime_ready_search",
                    "stable_kg_status"=>"stable_kg_status_ready_checkbox",
                    "middle_childs_count"=>"middle_childs_count_ready_search",
                    "no_call_in_stock"=>"not_in_stock_list",
                    "sector_operator_id"=>"distr_operator_list"), array(1,2,3), array(1,2,3), "Срочные ДС");
        //$ReadyKGSListAjaxedObject->load_after_write_html = true;
        $ReadyKGSListObjectsSequency = new AjaxedObjectsSequency(array($ReadyKGDistrictsAjaxedObject, 
            $NotInStocksAjaxedObject, $DistrictOperatorAjaxedObject, 
            $ReadyKGSListAjaxedObject), $kg_ready_list_sequence_template);
        
        $call_ready_list_sequence_template=$this->getSlidePanelId( "id_call_list_form_panel", 
                    "id_call_list_form_handle", "list_form_panel", 
                    "list_form_handle", "images/button_call.gif",122,40,250, "true", "right",
                    " Список срочных ЗВОНКОВ (предел выборки 50):<br/><table border=\"0\">
                    <tr><td>***___CallStatus</td></tr>
                    <tr><td><div class=\"list_form_default\">***___Call</td><tr></table>");
        
        $CallStatusFilterAjaxedObject = new AjaxedObject("call_status_filter", $GLOBALS['active_cont_select_type'],
                1, "CallStatus", array(), array(), array(), "Статус звонка");
        $CallStatusFilterAjaxedObject->load_after_write_html = true;
        
        if (!$this->recursive_mode)
        $ReadyCallsListAjaxedObject = new AjaxedObject("ready_calls_list", $GLOBALS['active_list_div_type'],
                2, "Call", array("call_status_id"=>"call_status_filter"), 
                array(1), array(1), "Срочные звонки", true);
        
        if (!$this->recursive_mode)
        $ReadyCallsObjectsSequency = new AjaxedObjectsSequency(array($CallStatusFilterAjaxedObject, 
            $ReadyCallsListAjaxedObject), $call_ready_list_sequence_template);
        
        if (!$this->recursive_mode)
        $this->aobject_sequencies = array(//$KGSearchObjectsSequency, 
            $ReadyKGSListObjectsSequency, $ReadyCallsObjectsSequency);
        
        //$CallFastAppendObject = new CustomManipObject($GLOBALS['fast_append_manip_type'], "Call", 
        //        array("kg_id"=>"calls_kg_kg_search"), "call_fast_append_result", "Добавить звонок по ДС");
        //$this->fast_manip_objects = array($CallFastAppendObject);
        
        $this->insert_instruction_template = "SET @pid=NULL; call `add_update_call` (:operator_id,:kg_id,0,
		      :call_date, :calls_comment, @pid, :stock_id, NULL); SET @code=:code; UPDATE kinder_gartens 
                      SET ready_to_call='0' WHERE id=:kg_id; "; 
        $this->update_instruction_template = "SET @pid=:id; call `add_update_call` (:operator_id,:kg_id,0,
		      :call_date, :calls_comment, @pid, :stock_id, NULL); SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('call', :id, @dcount);";
        $this->addit_fast_sql_template = " UPDATE kinder_gartens SET ready_to_call='0' WHERE id=:kg_id; ";
        
        $this->default_fast_append_session_params = array("operator_id"=>"current_user_id");
        //$this->inline_external_params = array("KinderGarten"=>array("kg_id"=>"id",
        //    "ready_to_call"=>"ready_to_call",
        //    "ready_to_call_datetime"=>"ready_to_call_datetime"));
        $this->short_name = "CL";
        
        $RepeatCallSetStatusOperation = new Operation($dbconnector, 
                "rpcall", 
                "CallsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса перезвона", 
                array("id"=>"call_id"), 
                array(), //form_input_params
                array("call_status_id"=>$GLOBALS['repeat_call_status_id']), 
                array(), //inline_props
                null
                );
        $RepeatCallSetDateOperation = new Operation($dbconnector, 
                "rpcldt", 
                "Call", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки даты перезвона", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("repeat_call_datetime"=>"repeat_call_datetime"), //inline_props
                $this
                );
        
        $SetKGReadyStatusOperation = new Operation($dbconnector, 
                "stkgstat", 
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
        $AbortCallSetStatusOperation = new Operation($dbconnector, 
                "abcl", 
                "CallsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса отказа", 
                array("id"=>"call_id"), 
                array(), //form_input_params
                array("call_status_id"=>$GLOBALS['abort_call_status_id']), 
                array(), //inline_props
                null
                );
        $NoAnswerCallSetStatusOperation = new Operation($dbconnector, 
                "nancl", 
                "CallsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса недозвона", 
                array("id"=>"call_id"), 
                array(), //form_input_params
                array("call_status_id"=>$GLOBALS['noanswer_call_status_id']), 
                array(), //inline_props
                null
                );
        $MeetCallSetStatusOperation = new Operation($dbconnector, 
                "mtcl", 
                "CallsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса встречи", 
                array("id"=>"call_id"), 
                array(), //form_input_params
                array("call_status_id"=>$GLOBALS['meet_call_status_id']), 
                array(), //inline_props
                null
                );
	$ArhivedCallSetStatusOperation = new Operation($dbconnector, 
                "arhcl", 
                "CallsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса В архиве", 
                array("id"=>"call_id"), 
                array(), //form_input_params
                array("call_status_id"=>$GLOBALS['arhived_call_status_id']), 
                array(), //inline_props
                null
                );
        $MeetCallSetStatusOperation->showCustomHiddenKeys(
                array("meet_datetime"=>"visible"));
        $RepeatCallSetStatusOperation->acceptCustomHiddenKeys(
                array("call_status_id"=>"hidden"));
        $AbortCallSetStatusOperation->acceptCustomHiddenKeys(
                array("call_status_id"=>"hidden"));
        $NoAnswerCallSetStatusOperation->acceptCustomHiddenKeys(
                array("call_status_id"=>"hidden"));
        $MeetCallSetStatusOperation->acceptCustomHiddenKeys(
                array("call_status_id"=>"hidden"));
        $ArhivedCallSetStatusOperation->acceptCustomHiddenKeys(
                array("call_status_id"=>"hidden"));
        $RepeatCallAction = new Action("Перезвон", array(
            $RepeatCallSetStatusOperation, $RepeatCallSetDateOperation), "RpCA",
            "#FF0000", $this);
        $SetKGReadyStatusAction = new Action("Данные ДС", array(
            $SetKGParamsOperation), "StRKGA",
            "#00FF00", $this);
        $AbortCallAction = new Action("Отказ", array(
            $SetKGReadyStatusOperation, $AbortCallSetStatusOperation), "AbCA",
            "#808080", $this);
        $NoAnswerCallAction = new Action("Недозвон", array(
            $NoAnswerCallSetStatusOperation, $RepeatCallSetDateOperation), "NaCA",
            "#B6FF00", $this);
        $MeetCallAction = new Action("Встреча", array(
            $MeetCallSetStatusOperation), "MtCA",
            "#7FC9FF", $this);
		$ArhivedCallAction = new Action("В архив", array(
            $ArhivedCallSetStatusOperation), "ArhCA",
            "#7FC9FF", $this);
        $this->record_actions = array($RepeatCallAction, $SetKGReadyStatusAction, $AbortCallAction,
            $NoAnswerCallAction, $MeetCallAction, $ArhivedCallAction);
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
