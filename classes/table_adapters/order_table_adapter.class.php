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

class OrderTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "orders", 
            $class_name, "orders_with_relative"); 
        $this->dict_header = "Заказы";
        $this->add_update_procedure_name = "add_update_person"; 
        $this->detail_view_name = "orders_with_relative_detail";
        $this->part_capacity = 5;
        
        $this->base_filter = "((shooting_status_id={$GLOBALS['replace_shooting_status_id']}) OR 
            (shooting_status_id={$GLOBALS['abort_shooting_status_id']}) OR ISNULL(shooting_status_id) OR (shooting_status_id<1)) AND 
            ((order_status_id<>{$GLOBALS['abort_order_status_id']}) OR ISNULL(order_status_id) OR (order_status_id<1))";
        $this->filters_array = array(//"kg_id"=>"KinderGarten",
            "manager_id"=>"Person", "stock_id"=>"Stock");
        $this->values_filter_array = array("start_order_date"=>"(order_date>='***___start_order_date 00:00:00')",
            "end_order_date"=>"(order_date<='***___end_order_date 23:59:59')",
            "start_shoot_date"=>"(shooting_date>='***___start_shoot_date')",
            "end_shoot_date"=>"(shooting_date<='***___end_shoot_date')",
            "like_str"=>"(LOWER(order_name) LIKE LOWER('%***___like_str%') )",
            "plot_id"=>"(***___plot_id IN (SELECT plot_id FROM orders_plots_rels opl 
                WHERE opl.order_id=orders_with_relative.id))", 
            "district_id"=>"(district_id=***___district_id)",
            "state_id"=>"(state_id=***___state_id)",
            "sector_operator_id"=>"sector_operator_id=***___sector_operator_id",
            "sector_manager_id"=>"sector_manager_id=***___sector_manager_id",
            "kg_id"=>" ( (***___kg_id=0) OR ISNULL(kg_id) OR (kg_id<1) ) ",
            "order_status_id"=>" (order_status_id=***___order_status_id) ",
            "order_empty_status"=>"(ISNULL(order_status_id) OR (order_status_id<1) OR (***___order_empty_status<>1)) ",
            "order_has_reordering"=>" ((order_status_id={$GLOBALS['ondate_repeat_order_status_id']}) OR 
            (order_status_id={$GLOBALS['unknown_repeat_order_status_id']}) OR 
            (NOT ISNULL(repeat_call_datetime) AND (repeat_call_datetime>'2000-01-01 00:00:00')) OR (***___order_has_reordering<>1)) ",
            "on_date_orders"=>"CAST(shooting_date AS DATE)='***___on_date_orders'",
            "moscow_end_disrict"=>"((***___moscow_end_disrict=0) or ((state_id=3) or (state_id=1)))",
            "piter_end_disrict"=>"((***___piter_end_disrict=0) or (state_id=2))");
        $this->filters_values = array(//"kg_id"=>0,
            "manager_id"=>null, "stock_id"=>null);
        $this->filter_values_select_keys = array("plot_id"=>"Plot", "district_id"=>"District", "state_id"=>"State",
            "sector_operator_id"=>"Person","sector_manager_id"=>"Person", 
            "order_status_id"=>"OrderStatus");
        $this->values_filter_values = array("end_order_date"=>null,//date('Y-m-d',$this->DateAdd('d', 90, time())),
            "start_order_date"=>null, "end_shoot_date"=>null, "start_shoot_date"=>null,
            "kg_id"=>0, "like_str"=>null, "plot_id"=>null, "moscow_end_disrict"=>0, "piter_end_disrict"=>0,
            "sector_operator_id"=>null,"sector_manager_id"=>null, 
            "order_status_id"=>null, "order_empty_status"=>null, "order_has_reordering"=>null, 
            "on_date_orders"=>null, "state_id"=>null, "state_id"=>null, "district_id"=>null);
        $this->filters_key_filters = array("manager_id"=>"(person_type_id=2)");
        $this->linked_entities_keys = array("OrdersPlots"=>array("id"=>"order_id")//,
            //"OrdersStatuses"=>array("id"=>"order_id"), 
            //"TeamItem"=>array("id"=>"team_object_id")
            );
        $this->linked_entities_names = array("OrdersPlots"=>"Сюжеты"//, //"OrdersStatuses"=>"Статусы", 
            //"TeamItem"=>"Задачи"
            );
        $this->linked_form_heights = array("OrdersPlots"=>40,//"OrdersStatuses"=>80,
            "OrdersStatuses"=>80//, "TeamItem"=>80
            );
        //$this->inline_input_properties = array("repeat_call_datetime"=>"repeat_call_datetime",
        //    "our_fault"=>"our_fault", "their_fault"=>"their_fault");
        $this->linked_detail_entities = array("Shooting"=>array("kg_id"=>"kg_id=***___kg_id"),
            "KinderGarten"=>array("kg_id"=>"id=***___kg_id"));
        $this->detail_info_template = "<table style=\"width:600px;\"><tr><td colspan=\"2\"><table><tr><td style=\"font-size:16px;\"><b>Детальная информация о заказе:</b></td></tr><tr><td>***___SELF</td></tr></table></td></tr>
            <tr>
            <td><table><tr><td style=\"font-size:16px;\"><b>Информация о ДС:</b></td></tr><tr><td>***___KinderGarten</td></tr></table></td>
            <td><table><tr><td style=\"font-size:16px;\"><b>Съемки по этому ДС:</b></td></tr><tr><td>***___Shooting</td></tr></table></td></tr></table>";
        
        $reflectionClass = new ReflectionClass("KinderGarten"."TableAdapter");
        $KGDictTAdapt = $reflectionClass->newInstanceArgs(array($dbconnector,
            "","KinderGarten"));
        $this->object_adapter->setFastSearchFields(array("kg_id"=>$KGDictTAdapt));
        
        //$kg_search_ready_checkbox_html = $this->get_input_checkbox("kg_search_ready_checkbox", 
        //        10, "1", "Только готовые");
        //$kg_search_object_sequence_template="<div class=\"sequence_form_default\">
        //    Панель поиска ДС:<table border=\"0\"><tr><td>***___State</td>
        //    <td>***___District</td><td>{$kg_search_ready_checkbox_html}</td><tr>
        //    <tr><td colspan=\"3\">***___KinderGarten</td><tr></table></div>";
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
        //$this->aobject_sequencies = array($KGSearchObjectsSequency);
        
        //$OrderFastAppendObject = new CustomManipObject($GLOBALS['fast_append_manip_type'], "Order", 
        //        array("kg_id"=>"calls_kg_kg_search"), "call_fast_append_result");
        //$this->fast_manip_objects = array($OrderFastAppendObject);
        
        $this->insert_instruction_template = "SET @ord_id=NULL; call `add_update_order` (:kg_id,:manager_id,:stock_id,
		      :order_date,:shooting_date,:shooting_time, :planned_child_count, :order_comment,@ord_id, 
                      :shooting_place, :group_count, :little_group_count);  SET @code=:code;"; 
        $this->update_instruction_template = "SET @ord_id=:id; call `add_update_order` (:kg_id,:manager_id,:stock_id,
		      :order_date,:shooting_date,:shooting_time, :planned_child_count, :order_comment,@ord_id, 
                      :shooting_place, :group_count, :little_group_count);  SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('order', :id, @dcount);";
    
        //$this->inline_external_params = array("KinderGarten"=>array("kg_id"=>"id",
        //    "ready_to_call"=>"ready_to_call",
        //    "ready_to_call_datetime"=>"ready_to_call_datetime"));
        $SetKGReadyStatusOperation = new Operation($dbconnector, 
                "skgrsop", 
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
        $AbortOrderSetStatusOperation = new Operation($dbconnector, 
                "aboo", 
                "OrdersStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса<br/> <b>отказа</b> от заказа", 
                array("id"=>"order_id"), 
                array(), //form_input_params
                array("order_status_id"=>$GLOBALS['abort_order_status_id']), 
                array(), //inline_props
                null
                );
        $CheckOrderSetStatusOperation = new Operation($dbconnector, 
                "chkoo", 
                "OrdersStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса<br/> <b>подтверждения</b> заказа", 
                array("id"=>"order_id"), 
                array(), //form_input_params
                array("order_status_id"=>$GLOBALS['check_order_status']), 
                array(), //inline_props
                null
                );
        $ControlCheckOrderSetStatusOperation = new Operation($dbconnector, 
                "ctrloo", 
                "OrdersStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса<br/> <b>контрольного звонка</b> заказа", 
                array("id"=>"order_id"), 
                array(), //form_input_params
                array("order_status_id"=>$GLOBALS['control_check_order_status']), 
                array(), //inline_props
                null
                );
        $ShootOrderSetStatusOperation = new Operation($dbconnector, 
                "shtoo", 
                "OrdersStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса<br/> <b>выполнения</b> съемки заказа", 
                array("id"=>"order_id"), 
                array(), //form_input_params
                array("order_status_id"=>$GLOBALS['active_order_status']), 
                array(), //inline_props
                null
                );
        $RepositionOrdertSetDateOperation = new Operation($dbconnector, 
                "rpsodo", 
                "Order", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки даты<br/> переноса заказа", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("our_fault"=>"our_fault", "their_fault"=>"their_fault", 
                    "repeat_call_datetime"=>"repeat_call_datetime",
                    "shooting_date"=>"shooting_date",
                    "shooting_time"=>"shooting_time",
                    "order_comment"=>"order_comment"), //inline_props
                $this
                );
        $RepositionOrderSetStatusOperation = new Operation($dbconnector, 
                "rpsoo", 
                "OrdersStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса<br/> переноса заказа", 
                array("id"=>"order_id"), 
                array(), //form_input_params
                array("order_status_id"=>$GLOBALS['ondate_repeat_order_status_id']), 
                array(), //inline_props
                null
                );
        $UnkRepositionOrdertSetDateOperation = new Operation($dbconnector, 
                "urpsodo", 
                "Order", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки причин<br/> переноса заказа c уточнением", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("our_fault"=>"our_fault", "their_fault"=>"their_fault"), //inline_props
                $this
                );
        $UnkRepositionOrderSetStatusOperation = new Operation($dbconnector, 
                "urpsoo", 
                "OrdersStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса<br/> переноса заказа с уточнением", 
                array("id"=>"order_id"), 
                array(), //form_input_params
                array("order_status_id"=>$GLOBALS['unknown_repeat_order_status_id']), 
                array(), //inline_props
                null
                );
        $AddToShootTaskMemberOperation = new Operation($dbconnector, 
                "adsm", 
                "TeamItem", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления участника съемки", 
                array("id"=>"team_object_id"), 
                array(), //form_input_params
                array("team_type_id"=>$GLOBALS['shooting_task_id']), 
                array(), //inline_props
                null
                );
        $CrashOrderSetStatusOperation = new Operation($dbconnector, 
                "crsoo", 
                "OrdersStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса<br/> срыва съемки", 
                array("id"=>"order_id"), 
                array(), //form_input_params
                array("order_status_id"=>$GLOBALS['crash_order_status_id']), 
                array(), //inline_props
                null
                );
        $OrderKGSetDateOperation = new Operation($dbconnector, 
                "kgsodo", 
                "Order", 
                $GLOBALS['inline_params_update_type'], 
                "Операция связки с <br/> детским садом", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("kg_id"=>"kg_id"), //inline_props
                $this
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
        
        $AbortOrderSetStatusOperation->acceptCustomHiddenKeys(
                array("order_status_id"=>"hidden"));
        $CheckOrderSetStatusOperation->acceptCustomHiddenKeys(
                array("order_status_id"=>"hidden"));
        $ControlCheckOrderSetStatusOperation->acceptCustomHiddenKeys(
                array("order_status_id"=>"hidden"));
        $ShootOrderSetStatusOperation->acceptCustomHiddenKeys(
                array("order_status_id"=>"hidden"));
        $RepositionOrderSetStatusOperation->acceptCustomHiddenKeys(
                array("order_status_id"=>"hidden"));
        $UnkRepositionOrderSetStatusOperation->acceptCustomHiddenKeys(
                array("order_status_id"=>"hidden"));
        $CrashOrderSetStatusOperation->acceptCustomHiddenKeys(
                array("order_status_id"=>"hidden"));
        $RepositionOrdertSetDateOperation->hidden_inline_params =
                array("shooting_date"=>"hidden",
                    "shooting_time"=>"hidden",
                    "order_comment"=>"hidden");
        
        $AbortOrderAction = new Action("Отказ", array(
            $SetKGReadyStatusOperation, $AbortOrderSetStatusOperation), "AbOA",
            "#808080", $this);
        $CheckOrderAction = new Action("Подтв.", array(
            $CheckOrderSetStatusOperation), "CHOA",
            "#7FC9FF", $this);
        $ControlCheckOrderAction = new Action("Контроль", array(
            $ControlCheckOrderSetStatusOperation), "CCOA",
            "#7FC9FF", $this);
        $ShootOrderAction = new Action("Съемка", array(
            $ShootOrderSetStatusOperation), "SHOA",
            "#00FF00", $this);
        $RepositionOrderAction = new Action("Перенос", array(
            $RepositionOrdertSetDateOperation, 
            $RepositionOrderSetStatusOperation), "RPSOA",
            "#FF0000", $this);
        $UnkRepositionOrderAction = new Action("Пер. уточн.", array(
            $UnkRepositionOrderSetStatusOperation,
            $RepositionOrdertSetDateOperation), "URPOA",
            "#FF0000", $this);
        $CrashOrderAction = new Action("Срыв съемки", array(
            $RepositionOrdertSetDateOperation, 
            $CrashOrderSetStatusOperation), "CRSOA",
            "#FF0000", $this);
        $ShootTaskMemberAction = new Action("+Участник съемки", array(
            $AddToShootTaskMemberOperation), "ShMSA",
            "#7FC9FF", $this);
        $OrderSetKGAction = new Action("->ДС", array(
            $OrderKGSetDateOperation), "OrdKGSA",
            "#7FC9FF", $this);
        $SetKGParamsAction = new Action("Данные ДС", array(
            $SetKGParamsOperation), "StRKGA",
            "#00FF00", $this);
        $this->record_actions = array($AbortOrderAction, $CheckOrderAction,
            $ControlCheckOrderAction, $ShootOrderAction, $RepositionOrderAction,
            $UnkRepositionOrderAction, $CrashOrderAction, $ShootTaskMemberAction,
            $OrderSetKGAction, $SetKGParamsAction);
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
