<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class KinderGartenTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "kinder_gartens", 
            $class_name, "kg_with_relative"); 
        $this->dict_header = "Детские сады";
        $this->div_list_view_name = "kg_with_relative_detail";
        $this->filters_array = array("sector_id"=>"Sector");
        $this->filters_values = array("sector_id"=>null);
        $this->values_filter_array = array("district_id"=>"(district_id=***___district_id)",
            "sector_operator_id"=>"sector_operator_id=***___sector_operator_id",
            "sector_manager_id"=>"sector_manager_id=***___sector_manager_id",
            "ready_to_call"=>"ready_to_call='***___ready_to_call'", 
            "no_call_in_stock"=>"***___no_call_in_stock NOT IN 
                (SELECT calls.stock_id FROM calls WHERE calls.kg_id=kg_with_relative_detail.id)",
            "ready_to_call_datetime"=>
                " (ready_to_call_datetime<='***___ready_to_call_datetime') ",
            "middle_childs_count"=>" (f_get_kg_middle_ariphm_childs(id, NULL, NULL)<=***___middle_childs_count) ",
            "stable_kg_status"=>"((LOWER(kg_status) LIKE 'постоян%') AND (***___stable_kg_status=1)) OR (***___stable_kg_status<>1) ");
        $this->filter_values_select_keys = array("district_id"=>"District",
            "sector_operator_id"=>"Person","sector_manager_id"=>"Person",
            "no_call_in_stock"=>"Stock");
        $this->values_filter_values = array("district_id"=>null,
            "sector_operator_id"=>((array_key_exists('operator_id', $_SESSION)?($_SESSION['operator_id']>0?$_SESSION['operator_id']:null):null)),"sector_manager_id"=>null, "ready_to_call"=>null,
            "no_call_in_stock"=>null, "ready_to_call_datetime"=>null,
            "middle_childs_count"=>null, "stable_kg_status"=>null
            );
        $this->custom_order_filter_fields = array
                ("call_status_id"=>array
                    ("filter_field"=>"middle_childs_count",
                    "filter_value"=>null,
                    "order_expression"=>" kg_childs_middle_count DESC ")
            );
        $this->add_update_procedure_name = ""; 
        $this->insert_instruction_template = "insert into `kinder_gartens`(`id`,`code`,`kg_area`,`kg_adress`,
            `kg_phones`,`kg_contact_person`,`kg_status`, `sector_id`, `email`, `kg_comment`) 
            values(null,:code,:kg_area,:kg_adress,
            :kg_phones,:kg_contact_person,:kg_status, :sector_id, :email, :kg_comment);"; 
        $this->update_instruction_template = "update `kinder_gartens` SET `code`=:code,`kg_area`=:kg_area 
            ,`kg_adress`=:kg_adress,`kg_phones`=:kg_phones,`kg_contact_person`=:kg_contact_person,
            `kg_status`=:kg_status, `sector_id`=:sector_id, `email`=:email, `kg_comment`=:kg_comment where `id`=:id";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('kg', :id, @dcount);";
        $this->detail_entities = array("Order"=>array("master_id"=>"id", "detail_id"=>"kg_id", "detail_header"=>"Заказы"),
            "Call"=>array("master_id"=>"id", "detail_id"=>"kg_id", "detail_header"=>"Звонки"),
            "Shooting"=>array("master_id"=>"id", "detail_id"=>"kg_id", "detail_header"=>"Съемки"));
        //$this->linked_entities_keys = array("Call"=>array("id"=>"kg_id"),"Order"=>array("id"=>"kg_id"));
        //$this->linked_entities_names = array("Call"=>"Доб. звонки","Order"=>"Доб. заказы");
        //$this->linked_form_heights = array("Call"=>200,"Order"=>280);
        $this->short_name = "KS";
        $CallFastFormKGAppendObject = new CustomManipObject($GLOBALS['fast_append_list_type'], "Call", 
                array("kg_id"=>"id"), "call_fast_app_from_kg_result", "Добавить звонок по ДС",
                array("stock_id"=>"no_call_in_stock"));
        //$this->fast_manip_objects = array($CallFastAppendObject);
        $this->fast_manip_fields = array($CallFastFormKGAppendObject);
        $this->fast_id_select_params = array("code"=>"{$this->class_name}_fast_id_search_code", 
            "kg_adress"=>"{$this->class_name}_fast_id_search_kg_adress");
        $this->fast_id_select_form_template = "<div id=\"{$this->class_name}_id_fast_search_form\">
            <table border=\"0\"><tr><td>***___code</td><td>***___kg_adress</td>
            </tr></table></div>";
        $this->fast_id_select_sql_template = "select id, kg_name from kg_with_relative where 
            (LOWER(`code`) LIKE LOWER('***___code%')) AND (LOWER(`kg_name`) LIKE LOWER('%***___kg_adress%')) limit 0,20;";
        $this->fast_id_list_row_template = "<a href=\"#\"
            onclick=\" document.getElementById('***___set_field_id').value=***___id;\" >***___kg_name</a>";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>