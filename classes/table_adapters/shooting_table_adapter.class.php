<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class ShootingTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "shootings", 
            $class_name, "shootings_with_relative");
        $this->dict_header = "Съемки";
		$this->part_capacity = 5;
        $this->add_update_procedure_name = "add_update_person";
		$this->base_filter = "(shooting_status_id<>{$GLOBALS['arhived_shooting_status_id']})";
        $this->filters_array = array("manager_id"=>"Person",
            "stock_id"=>"Stock");
        $this->values_filter_array = array("start_shooting_date"=>"IF(ISNULL(shooting_date),(1=1),(shooting_date>='***___start_shooting_date'))",
            "end_shooting_date"=>"IF(ISNULL(shooting_date),(1=1),(shooting_date<='***___end_shooting_date'))", 
            "district_id"=>"(district_id=***___district_id)", "state_id"=>"(state_id=***___state_id)");
        $this->filters_values = array("manager_id"=>null, 
            "stock_id"=>null);
        $this->filter_values_select_keys = array("district_id"=>"District", "state_id"=>"State");
        $this->values_filter_values = array("end_shooting_date"=>date('Y-m-d',$this->DateAdd('d', 90, time())),
            "start_shooting_date"=>null,//date('Y-m-d',$this->DateAdd('d', -30, time())), 
            "district_id"=>null, "state_id"=>null);
        $this->filters_key_filters = array("manager_id"=>"(person_type_id=2)");
        //$this->inline_input_properties = array("real_count"=>"real_count",
        //    "back_count"=>"back_count");
        
        $this->insert_instruction_template = "SET @pid=NULL; call `add_update_shooting` (:manager_id,:order_id,:stock_id,
		      :shooting_date,:shooting_time,:child_count,@pid); SET @code=:code;"; 
        $this->update_instruction_template = "SET @pid=:id; call `add_update_shooting` (:manager_id,:order_id,:stock_id,
		      :shooting_date,:shooting_time,:child_count,@pid); SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('shooting', :id, @dcount);";
    
        //$SetKGReadyStatusOperation = new Operation($dbconnector, 
        //        "stkgrdst", 
        //        "KinderGarten", 
        //        $GLOBALS['external_params_update_type'], 
        //        "Операция установки готовности ДС", 
        //        array(), //id`s links
        //        array(), //form_input_params
        //        array(), //default values 
        //        array("kg_id"=>"id", "ready_to_call"=>"ready_to_call",
        //            "ready_to_call_datetime"=>"ready_to_call_datetime"), //inline_props
        //        null
        //        );
        $this->short_name = "SHT";
        
        $AbortShootingSetStatusOperation = new Operation($dbconnector, 
                "absh", 
                "ShootingsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса отказа", 
                array("id"=>"shooting_id"), 
                array(), //form_input_params
                array("shooting_status_id"=>$GLOBALS['abort_shooting_status_id']), 
                array(), //inline_props
                null
                );
        $RepositionShootingSetStatusOperation = new Operation($dbconnector, 
                "rpsh", 
                "ShootingsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса переноса", 
                array("id"=>"shooting_id"), 
                array(), //form_input_params
                array("shooting_status_id"=>$GLOBALS['replace_shooting_status_id']), 
                array(), //inline_props
                null
                );
        $CompleteShootingSetStatusOperation = new Operation($dbconnector, 
                "cplsh", 
                "ShootingsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса Выполнено", 
                array("id"=>"shooting_id"), 
                array(), //form_input_params
                array("shooting_status_id"=>$GLOBALS['complete_shooting_status_id']), 
                array(), //inline_props
                null
                );
	$ArhivedShootingSetStatusOperation = new Operation($dbconnector, 
                "arhsh", 
                "ShootingsStatuses", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления статуса В архиве", 
                array("id"=>"shooting_id"), 
                array(), //form_input_params
                array("shooting_status_id"=>$GLOBALS['arhived_shooting_status_id']), 
                array(), //inline_props
                null
                );
        
        $AddToHandlingTaskMemberOperation = new Operation($dbconnector, 
                "adhm", 
                "TeamItem", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления участника обработки", 
                array("id"=>"team_object_id"), 
                array(), //form_input_params
                array("team_type_id"=>$GLOBALS['processing_task_id']), 
                array(), //inline_props
                null
                );
        $AddToClDeliveryTaskMemberOperation = new Operation($dbconnector, 
                "adcdm", 
                "TeamItem", 
                $GLOBALS['linked_entity_append_type'], 
                "Операция добавления участника доставки клиенту", 
                array("id"=>"team_object_id"), 
                array(), //form_input_params
                array("team_type_id"=>$GLOBALS['client_delivery_task_id']), 
                array(), //inline_props
                null
                );
        $RealCountSetOperation = new Operation($dbconnector, 
                "rptcnt", 
                "Shooting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки реального кол-ва от фотографа", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("real_count"=>"real_count"), //inline_props
                $this
                );
        $BackCountSetOperation = new Operation($dbconnector, 
                "bckcnt", 
                "Shooting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки кол-ва возвратов фотографий", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("back_count"=>"back_count"), //inline_props
                $this
                );
        $HandleCountSetOperation = new Operation($dbconnector, 
                "hndcnt", 
                "Shooting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки кол-ва обработанных фотографий", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("handling_count"=>"handling_count"), //inline_props
                $this
                );
	$PrintCountSetOperation = new Operation($dbconnector, 
                "prtcnt", 
                "Shooting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки кол-ва фотографий на печать", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("print_count"=>"print_count"), //inline_props
                $this
                );
	$ToClientCountSetOperation = new Operation($dbconnector, 
                "clncnt", 
                "Shooting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки кол-ва фотографий для клиента", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("to_client_count"=>"to_client_count"), //inline_props
                $this
                );
        $ComplektCountSetOperation = new Operation($dbconnector, 
                "cplkcnt", 
                "Shooting", 
                $GLOBALS['inline_params_update_type'], 
                "Операция установки кол-ва комплектов", 
                array(), //id`s links
                array(), //form_input_params
                array(), //default values 
                array("full_kompl_count"=>"full_kompl_count",
                    "big_photos_count"=>"big_photos_count",
                    "small_photos_count"=>"small_photos_count"), //inline_props
                $this
                );
        
        $AbortShootingSetStatusOperation->acceptCustomHiddenKeys(
                array("shooting_status_id"=>"hidden"));
        $RepositionShootingSetStatusOperation->acceptCustomHiddenKeys(
                array("shooting_status_id"=>"hidden"));
        $CompleteShootingSetStatusOperation->acceptCustomHiddenKeys(
                array("shooting_status_id"=>"hidden"));
        $ArhivedShootingSetStatusOperation->acceptCustomHiddenKeys(
                array("shooting_status_id"=>"hidden"));
        
        $AbortShootingAction = new Action("Отказ", array(
            $AbortShootingSetStatusOperation), "AbSA",
            "#808080", $this);
        $ReplaceShootingAction = new Action("Перенос", array(
            $RepositionShootingSetStatusOperation), "RpSA",
            "#FF0000", $this);
        $CompleteShootingAction = new Action("Выполнена", array(
            $CompleteShootingSetStatusOperation), "CplSA",
            "#7FC9FF", $this);
		$ArhivedShootingAction = new Action("В архив", array(
            $ArhivedShootingSetStatusOperation), "ArhSA",
            "#7FC9FF", $this);
        $HandlingTaskMemberAction = new Action("+Участник обработки", array(
            $AddToHandlingTaskMemberOperation), "HdMSA",
            "#7FC9FF", $this);
        $ClDeliveryTaskMemberAction = new Action("+Участник доставки", array(
            $AddToClDeliveryTaskMemberOperation), "CDMSA",
            "#7FC9FF", $this);
        $RealCountSetAction = new Action("Реально", array(
            $RealCountSetOperation), "RlCnSA",
            "#7FC9FF", $this);
        $BackCountSetAction = new Action("Возврат", array(
            $BackCountSetOperation), "BckCnSA",
            "#7FC9FF", $this);
        $HandleCountSetAction = new Action("В обр-ке", array(
            $HandleCountSetOperation), "HndCnSA",
            "#7FC9FF", $this);
	$PrintCountSetAction = new Action("На печать", array(
            $PrintCountSetOperation), "PrtCnSA",
            "#7FC9FF", $this);
	$ToClientCountSetAction = new Action("Клиенту", array(
            $ToClientCountSetOperation), "ClnCnSA",
            "#7FC9FF", $this);
        $ComplektCountSetAction = new Action("Комплект", array(
            $ComplektCountSetOperation), "CplkCnSA",
            "#7FC9FF", $this);
        $this->record_actions = array($AbortShootingAction, $ReplaceShootingAction,
            $CompleteShootingAction, $HandlingTaskMemberAction,
            $ClDeliveryTaskMemberAction, $ArhivedShootingAction,
            $RealCountSetAction, $HandleCountSetAction,
            $PrintCountSetAction, $ToClientCountSetAction, $BackCountSetAction,
            $ComplektCountSetAction);
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
