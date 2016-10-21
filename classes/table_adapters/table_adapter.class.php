<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

if (!defined("ADAPT_ABSOLUTE_PATH"))
    define("ADAPT_ABSOLUTE_PATH", dirname(__FILE__)."/../../");
 
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/configuration.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/dbconnector.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_collection.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/person_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/person_type_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/kg_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/stock_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/plot_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/call_status_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/call_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/order_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/meeting_result_type_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/meeting_result_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/meeting_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/shooting_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/district_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/state_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/orders_plots_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/calls_statuses_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/orders_statuses_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/order_status_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/team_item_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/team_type_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/user_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/sector_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/shooting_status_object_adapter.class.php");
require_once(constant("ADAPT_ABSOLUTE_PATH")."classes/object_adapters/shootings_statuses_object_adapter.class.php");

abstract class TableAdapter extends Tools {
    protected $dbconnector;
    public $object_adapter;
    protected $selectFilter="";
    protected $table_name;
    public $class_name;
    public $short_name=null;
    protected $with_relative_view_name;
    public $detail_view_name = null;
    public $num_suffix="";
    protected $div_list_view_name = null;
    public $select_collection = array();
    public $base_filter = "(1=1)";
    protected $report_mode_base_filter = " (closed<>1) ";
    protected $filters_array = array();
    public $filters_values = array();
    protected $filters_key_filters = array();
    protected $values_filter_array = array();
    public $values_filter_values = array();
    public $detail_entities = array();
    protected $add_update_procedure_name;
    protected $insert_instruction_template;
    protected $update_instruction_template;
    protected $delete_instruction_template;
    protected $part_capacity = 15;
    protected $current_select_collection_part;
    protected $current_part_num;
    protected $not_show_closed=true;
    protected $all_capacity=0;
    protected $linked_entities_keys = array();
    protected $dict_header = "---";
    protected $filter_values_select_keys = array();
    protected $linked_entities_names = array();
    protected $linked_form_heights = array();
    protected $inline_input_properties = array();
    protected $detail_info_template = null;
    protected $linked_detail_entities = array();
    protected $inline_props_template = null;
    protected $aobject_sequencies = array();
    protected $fast_manip_objects = array();
    protected $fast_manip_fields = array();
    protected $base64_encode_inline_props = array();
    protected $inline_select_filters=array();
    public $default_insert_session_params = array();
    public $default_fast_append_session_params = array();
    protected $inline_external_params = array();
    protected $inline_external_template = null;
    protected $custom_action_instructions = array();
    protected $custom_action_params = array();
    protected $record_actions = array();
    public $custom_hidden_form_fields = array();
    public $base_or_condition_params = array();
    protected $addit_fast_sql_template = "";
    protected $multi_addit_instructions = array();
    protected $fast_id_select_params = array();
    protected $fast_id_select_form_template = null;
    protected $fast_id_select_sql_template = null;
    protected $fast_id_list_row_template = null;
    protected $custom_sel_array_order_clause = null;
    protected $id_field="id";
    protected $custom_order_clause=null;
    protected $custom_order_filter_fields=array();
    protected $detailed_reports=array();
    protected $group_by_expression = "";
    protected $aggregate_fields = array();
    protected $additional_fields = array();
    protected $recursive_mode = false;
    protected $last_query_result_size = 0;

    function __construct($dbconnector, $table_name, 
        $class_name, $with_relative_view_name)    {
        $this->dbconnector = $dbconnector;

        $this->table_name = $table_name;
        $this->class_name = $class_name;
        //echo "[".$this->class_name."]";
        $this->with_relative_view_name = $with_relative_view_name;
        $this->object_adapter = $this->getObjectAdapterInstance();
        $hidden_fields_keys = array_keys($this->custom_hidden_form_fields);
        foreach($hidden_fields_keys as $hidden_key) {
            if(!array_key_exists($hidden_key, 
                    $this->object_adapter->hidden_keys))   {
                $this->object_adapter->hidden_keys[$hidden_key]=
                        "hidden";
            }
        }
        //$this->part_capacity = 15;
        $this->current_part_num = 0;    
    }
    
    function assignNumSuffix($suffix)   {
        $this->num_suffix = $suffix;
        //$this->object_adapter->num_suffix = $this->num_suffix;
    }
    
    function acceptReportModeBaseFilter()   {
        $this->base_filter = $this->report_mode_base_filter;
    }
    
    function setCustomSelectViewName($view_name)    {
        $this->with_relative_view_name = $view_name;
    }
    
    function setGroupExpression($group_expression)    {
        $this->group_by_expression = $group_expression;
    }
    
    function setAggregateFields($agg_fields)    {
        $this->aggregate_fields = $agg_fields;
    }
    
    function getAggregateExpression()   {
        $result = "";
        $agg_keys = array_keys($this->aggregate_fields);
        foreach($agg_keys as $agg_key)  {
            $result .= ", ".$this->aggregate_fields[$agg_key]." AS ".$agg_key;
        }
        return $result;
    }
    
    function setAdditionalFields($addit_fields)  {
        $this->additional_fields = $addit_fields;
    }
    
    function getAddFieldsExpression()   {
        $result = "";
        $addf_keys = array_keys($this->additional_fields);
        foreach($addf_keys as $addf_key)  {
            $result .= ", ".$this->additional_fields[$addf_key]." AS ".$addf_key;
        }
        return $result;
    }
    
    function acceptCustomHiddenKeys($custom_hidden_form_fields)  {
        $hidden_fields_keys = array_keys($custom_hidden_form_fields);
        foreach($hidden_fields_keys as $hidden_key) {
            if(!array_key_exists($hidden_key, 
                    $this->object_adapter->hidden_keys))   {
                $this->object_adapter->hidden_keys[$hidden_key]=
                        "hidden";
            }
        }
    }
    
    function showCustomHiddenKeys($custom_showed_form_fields)  {
        $hidden_fields_keys = array_keys($custom_showed_form_fields);
        foreach($hidden_fields_keys as $hidden_key) {
            if(array_key_exists($hidden_key, 
                    $this->object_adapter->hidden_keys))   {
                unset($this->object_adapter->hidden_keys[$hidden_key]);
            }
        }
    }
    
    function getObjectAdapterInstance()    {
        $reflectionClass = new ReflectionClass($this->class_name."ObjectAdapter");
        return $reflectionClass->newInstanceArgs(array($this->table_name, $this->class_name));
    }
    
    function getJSONData($select_type = null)   {
        if(is_null($select_type)||($select_type==$GLOBALS['full_extract_type']))   {
            
        }
    }
    
    function selectWithRelative()   {
        $start_limit = 0+$this->part_capacity*$this->current_part_num;
        $end_limit = $this->part_capacity;
        
        $count = $this->dbconnector->query_both_to_array(
            "SELECT COUNT(*) result_size FROM ".$this->with_relative_view_name." WHERE ( ".$this->base_filter." OR ".
		$this->getBaseFilterOrClause().") AND ".
                $this->getFilterWhereClauseWithoutOrParams().";");
        
        //$this->last_query_result_size = $count[0]['result_size'];
        $this->last_query_result_size = $count[0]['result_size'];
        
        $custom_order=$this->getCustomOrderClause();
        $rows=$this->dbconnector->query_both_to_array(
            "SELECT * FROM ".$this->with_relative_view_name." WHERE ( "
            .$this->base_filter." OR ".
            $this->getBaseFilterOrClause().") AND ".
            $this->getFilterWhereClauseWithoutOrParams().
	    " order by ".(is_null($custom_order)?
                " ".$this->id_field." desc ":
                $custom_order)." "
                ." limit {$start_limit},{$end_limit};");
        //echo (
        //    "SELECT * FROM ".$this->with_relative_view_name." WHERE ".$this->base_filter." AND ".
        //        $this->getFilterWhereClause().";");
        //print_r($rows);
        //echo (
        //    "SELECT * FROM ".$this->with_relative_view_name." WHERE ".$this->base_filter." AND ".
        //        $this->getFilterWhereClause().";");
        if ($rows!=null)    {
            $this->all_capacity = sizeof($rows);
            $this->select_collection = new ObjectCollection($this->class_name,$rows);
        }   else    {
            $this->all_capacity = 0;
            $this->select_collection = array();
        }
        //$this->generateTablePartByNum($this->current_part_num);
    }
    
    function getCustomOrderClause() {
        $all_filter_values = array_merge($this->filters_values, $this->values_filter_values);
        //$all_filters_keys = array_keys($all_filter_values);
        $order_filter_keys = array_keys($this->custom_order_filter_fields);
        //print_r($all_filter_values);
        $result_expr=$this->custom_order_clause;
        foreach($order_filter_keys as $order_filter_key)    {
            //echo $order_filter_key."---";
            if (isset($all_filter_values
                    [$this->custom_order_filter_fields[$order_filter_key]["filter_field"]]))   {
                if (is_null($this->custom_order_filter_fields[$order_filter_key]["filter_value"])||
                  ($this->custom_order_filter_fields[$order_filter_key]["filter_value"]==
                   $all_filter_values[$this->custom_order_filter_fields[$order_filter_key]["filter_field"]])) {
                   if (is_null($result_expr)) 
                       $result_expr = " ".$this->custom_order_filter_fields[$order_filter_key]["order_expression"];
                   else
                       $result_expr .= (", ".$this->custom_order_filter_fields[$order_filter_key]["order_expression"]);
                }
            }
        }
        
        return $result_expr;
        
    }
    
    function selectFullWithRelative($limit=500)   {
        
        $custom_order=$this->getCustomOrderClause();
        $rows=$this->dbconnector->query_both_to_array(
            "SELECT * FROM ".$this->with_relative_view_name.
                " WHERE ".$this->base_filter." AND (closed<>1) AND ".
                $this->getFilterWhereClause().
                " order by ".(is_null($custom_order)?
                $this->id_field." desc ":
                $custom_order)." limit 0,{$limit};");
        if ($rows!=null)    {
            $this->all_capacity = sizeof($rows);
            $this->select_collection = new ObjectCollection($this->class_name,$rows);
        }   else    {
            $this->all_capacity = 0;
            $this->select_collection = array();
        }
    }
    
    function selectFullWithRelativeGroupMode()   {
        $rows=$this->dbconnector->query_both_to_array(
            "SELECT *".$this->getAggregateExpression().$this->getAddFieldsExpression().
                " FROM ".$this->with_relative_view_name." WHERE ".$this->base_filter." AND (closed<>1) AND ".
                $this->getFilterWhereClause()." ".
                $this->group_by_expression);
        //print_r($rows);
        if ($rows!=null)    {
            $this->all_capacity = sizeof($rows);
            $this->select_collection = new ObjectCollection($this->class_name,$rows);
        }   else    {
            $this->all_capacity = 0;
            $this->select_collection = array();
        }
        
      return $rows;  
    }
    
    function selectFullWithRelativeWithoutFilters($limit=500)   {
        $custom_order=$this->getCustomOrderClause();
        $rows=$this->dbconnector->query_both_to_array(
            "SELECT * FROM ".$this->with_relative_view_name.
            " WHERE ".$this->base_filter." AND (closed<>1) ".
            " order by ".(is_null($custom_order)?
                $this->id_field." desc ":
                $custom_order)." limit 0,{$limit};");
        if ($rows!=null)    {
            $this->all_capacity = sizeof($rows);
            $this->select_collection = new ObjectCollection($this->class_name,$rows);
        }   else    {
            $this->all_capacity = 0;
            $this->select_collection = array();
        }
    }
    
    function getSelectContentFullByFilter($limit=500) {
        $custom_order=$this->getCustomOrderClause();
        $rows=$this->dbconnector->query_both_to_array(
            "SELECT id, ".$this->object_adapter->select_display_field.
            " as select_name FROM ".$this->with_relative_view_name.
            " WHERE ".$this->base_filter." AND ".
            $this->getFilterWhereClause().
            " order by ".(is_null($custom_order)?
            $this->id_field." desc ":
            $custom_order)." limit 0,{$limit};");
        if ($rows!=null)    {
        }   else    {
            $rows = array();
        }
        array_unshift($rows, array("id"=>-1, 
                "select_name"=>"Не выбрано"));
        return $this->generate_select_content($rows, "id", "select_name");
    }
    
    function generateDetailedReports($params)  {
        $detailed_reports_keys = array_keys($this->detailed_reports);
        foreach($detailed_reports_keys as $detailed_reports_key)    {
            $reflectionClass = new ReflectionClass(
                    $this->detailed_reports[$detailed_reports_key]."Report");
            $Report = $reflectionClass->newInstanceArgs(array($this->dbconnector));
            $Report->acceptAjaxParams($params);
            $Report->generateReportByName($detailed_reports_key);
        }
    }
    
    function writeActionsForms()    {
        foreach($this->record_actions as $rec_action) {
            echo $rec_action->getAbstractActionFormHTML();
        }
        echo $this->getSlidePanelId( "id_actions_panel", 
                    "id_actions_handle", "actions_panel", 
                    "actions_handle", "images/button_hor.gif",40,122,0, "true", "bottom",
                    "<div id=\"object_actions_panel\"><div class=\"current_object_identity\"></div>
                    <div id=\"actions_container_{$this->class_name}\"></div></div>");
    }
    
    function getListDivContentFullByFilter() {
        if (isset($this->div_list_view_name))   {
            $this->with_relative_view_name=$this->div_list_view_name;
        }
        $rows=$this->selectFullWithRelative(50);
        $rows = $this->select_collection;
        $result_html = "<table border=\"0\" width=\"100%\">";
        $row_num = 0;
        foreach($this->select_collection as $list_object)   {
            $list_item_content = $this->object_adapter->getListItemRow($list_object);
            foreach($this->fast_manip_fields as $fast_field)    {
                $list_item_content = str_replace("***___FAST".$fast_field->adapter_class, 
                    $fast_field->getObjectHTML("button medium green",$this->getFilterJSParams(),
                            //$this->getFilterJSParams(),
                            0,$list_object, $row_num),
                    $list_item_content);
            }
            $row_num++;
            $result_html .= $list_item_content;
        }
        $result_html .= "</table>";
        return $result_html;
    }
    
    function getContentJSByType($content_type, $js_params, $container)  {
        $content_params_keys = array_keys($js_params);
        $content_js = "";
        $index = 0;
        foreach ($content_params_keys as $content_params_key)   {
            if($index>0) $content_js .= ",";
            $content_js .= " {$content_params_key}:'{$js_params[$content_params_key]}'";
            
            $index++;
        }
        //echo "[".$content_js."]";
        if($content_type==$GLOBALS['active_cont_select_type'])
            return "ajaxGetRequest('".$GLOBALS['out_table_php']."', '{$this->class_name}', 
                '{$GLOBALS['get_sel_options_mode']}', { ".$content_js." },
                '0', '{$container}');";
        else if($content_type==$GLOBALS['active_list_div_type'])
            return "ajaxGetRequest('".$GLOBALS['out_table_php']."', '{$this->class_name}', 
                '{$GLOBALS['get_list_div_mode']}', { ".$content_js." },
                '0', '{$container}');";
        else
            return "";
    }
    
    function getManipJSByType($content_type, $js_params, $container, $filter_js_params, $part_num, $direct_params=array())  {
        $content_params_keys = array_keys($js_params);
        $direct_params_keys = array_keys($direct_params);
        $content_js = "";
        $index = 0;
        foreach ($content_params_keys as $content_params_key)   {
            if($index>0) $content_js .= ",";
            $content_js .= " {$content_params_key}:'{$js_params[$content_params_key]}'";
            
            $index++;
        }
        foreach ($direct_params_keys as $direct_params_key)   {
            if($index>0) $content_js .= ",";
            $content_js .= " {$direct_params_key}:'{$direct_params[$direct_params_key]}'";
            
            $index++;
        }
        if($content_type==$GLOBALS['fast_append_manip_type'])
            return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['fast_append_manip_mode']}', { ".$content_js." },
         '{$part_num}', '{$container}', { ".$filter_js_params." }); } );";
        else
            return "";
    }
    
    function getManipJSByTypeByObject($content_type, $js_params, $container, $filter_js_params, $part_num, $object, $direct_params=array())  {
        $content_params_keys = array_keys($js_params);
        $direct_params_keys = array_keys($direct_params);
        $content_js = "";
        $index = 0;
        foreach ($content_params_keys as $content_params_key)   {
            if($index>0) $content_js .= ",";
            $content_js .= " {$content_params_key}:'{$object->$js_params[$content_params_key]}'";
            
            $index++;
        }
        foreach ($direct_params_keys as $direct_params_key)   {
            if($index>0) $content_js .= ",";
            $content_js .= " {$direct_params_key}:'{$direct_params[$direct_params_key]}'";
            
            $index++;
        }
        if($content_type==$GLOBALS['fast_append_list_type'])
            return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['fast_append_manip_mode']}', { ".$content_js." },
         '{$part_num}', '{$container}', { ".$filter_js_params." }); } );";
        else
            return "";
    }
    
    function generateAObjectsHTML() {
        foreach($this->aobject_sequencies as $aobj_sequency)    {
           echo $aobj_sequency->getSequency();
        }
    }
    
    function generateFastManipHTML() {
        foreach($this->fast_manip_objects as $fast_object)    {
           $fast_object->getObjectHTML("",$this->getFilterJSParams(),0, null);
        }
    }
    
    function getArraysByKeys($key_array, $arrays_filters)    {
        $relative_fields_array = array();
        $select_fields_keys = array_keys($key_array);
        foreach ($select_fields_keys as $select_fields_key) {
            $class_name = $key_array[$select_fields_key];
            $reflectionClass = new ReflectionClass($class_name."TableAdapter");
            $SelectDictTAdapt = $reflectionClass->newInstanceArgs(array($this->dbconnector,
                "", $class_name));
            if (isset ($arrays_filters[$select_fields_key]))   {
                $SelectDictTAdapt->base_filter = 
                        $arrays_filters[$select_fields_key];
            }
            
            $relative_fields_array[$select_fields_key] = 
                $SelectDictTAdapt->getSelectArray();
        }
        return $relative_fields_array;
    }
    
    function getSelectArrays()  {
        return $this->getArraysByKeys($this->object_adapter->foreigen_keys, $this->object_adapter->foreigen_keys_filters);
    }
    
    function getSelectArraysWithoutParentLists($parent_lists)  {
        $parent_keys = array_keys($parent_lists);
        foreach($parent_keys as $parent_key)    {
            if (isset($this->object_adapter->foreigen_keys[$parent_lists[$parent_key]]))   {
                unset($this->object_adapter->foreigen_keys[$parent_lists[$parent_key]]);
            }
        }
        if (array_count_values($this->object_adapter->ids_array_fields)>0)
            return $this->getArraysByKeys( array_merge($this->object_adapter->foreigen_keys, 
                $this->object_adapter->ids_array_fields) , $this->object_adapter->foreigen_keys_filters);
        else
            return $this->getArraysByKeys( $this->object_adapter->foreigen_keys, 
                    $this->object_adapter->foreigen_keys_filters);
    }
    
    function getAllInlineJS($inline_props, $local_prop_suffix, $with_confirm, $object, $current_row_num, $complete_function)  {
        $inline_input_properties_keys = array_keys($inline_props);
        //print_r($inline_props);
        $local_js_params = " id:'id".$local_prop_suffix."'";
        foreach($inline_input_properties_keys as $inline_input_properties_key)    {
                          
                          if (array_key_exists($inline_input_properties_key, $object->getFullPropArray())) {
                            
                            $local_manip_id = $this->class_name."_local_".$inline_input_properties_key.
                                    $current_row_num."_div";
                            $local_js_params .= ",{$inline_input_properties_key}:'{$inline_input_properties_key}{$local_prop_suffix}' ";
                                                                
                          } else    {
                              echo "Не найдено свойства для встраиваемого поля";
                          }
                              
                      }
                      
                      if ($with_confirm)
                            $result_js = $this->generateLocalUpdateJSWithFilterWithExternalRefreshJS($this->class_name."_part_num", 
                                        "{$local_manip_id}", "{$local_js_params}", $complete_function
                                        //$this->generateSelectJSWithFilter(
                                        //    "", $this->current_part_num, $this->class_name.$GLOBALS['dict_container_base'])
                                        );
                      else
                            $result_js = $this->generateLocalUpdateJSWithFilterWithExternalRefreshJSNoConfirm($this->class_name."_part_num", 
                                        "{$local_manip_id}", "{$local_js_params}", $complete_function
                                        //$this->generateSelectJSWithFilter(
                                        //    "", $this->current_part_num, $this->class_name.$GLOBALS['dict_container_base'])
                                        );

                      return $result_js;
    }
    
    function getInlineForm($inline_props, $inline_props_template, $object, $show_buttons, 
            $local_prop_suffix, $current_row_num, $hidden_inline_params=array())   {
        $inline_input_properties_keys = array_keys($inline_props);
        //print_r($inline_props);
        $fullArrayedObject = $object->getFullPropArray();
        $inline_content = "";
        $inline_content .= $this->object_adapter->write_input_text_field_with_num(
                                    "id", 
                                    $fullArrayedObject['id'],$local_prop_suffix);
        foreach($inline_input_properties_keys as $inline_input_properties_key)    {
                          
                          if($inline_props_template==null)
                            $inline_content .= "<td>";
                          //echo $inline_input_properties_key."!!!!!!!!!!!!!!!!!";
                          //print_r($object->getFullPropArray());
                          //prop
                          
                          if (array_key_exists($inline_input_properties_key, $object->getFullPropArray())) {
                            
                             $inline_content .= (array_key_exists($inline_input_properties_key,$hidden_inline_params)?"<span style=\"display:none;\">":""); 
                             
                             //if (array_key_exists($inline_input_properties_key,$hidden_inline_params))
                             //print_r($hidden_inline_params);
                             $inline_content .= $this->object_adapter->write_input_text_field_with_num_and_placement(
                                    $inline_input_properties_key, 
                                    $fullArrayedObject[$inline_input_properties_key],
                                    $local_prop_suffix, "vertical");
                             $inline_content .= (array_key_exists($inline_input_properties_key,$hidden_inline_params)?"</span>":"");
                            
                            $local_manip_id = $this->class_name."_local_".$inline_input_properties_key.
                                    $current_row_num."_div";
                            $inline_content .= "<div id={$local_manip_id}></div>";
                            if ($show_buttons)  {
                                $local_js_params = " id:'id".$local_prop_suffix.$inline_input_properties_key."',
                                    {$inline_input_properties_key}:'{$inline_input_properties_key}{$local_prop_suffix}' ";
                                $inline_content .= $this->get_link_button("Установить", "link_button_small_default",
                                    $this->generateLocalUpdateJSWithFilterWithExternalRefreshJS($this->class_name."_part_num", 
                                        "{$local_manip_id}", "{$local_js_params}", 
                                        $this->generateSelectJSWithFilter(
                                            "", $this->current_part_num, $this->class_name.$GLOBALS['dict_container_base'])),
                                                $this->class_name."_local_upd_link_id");
                            }
                                    ////),
                                            
                                    
                          } else    {
                              $inline_content .= "Не найдено свойства для встраиваемого поля";
                          }
                          
                          if($this->inline_props_template==null)    {
                            $inline_content .= "</td>";
                            //echo $inline_content;
                          }
                          else $inline_template_modified = str_replace("***___".$inline_input_properties_key, 
                                    $inline_content,$inline_template_modified);
                              
                      }
                      
                      if($this->inline_props_template!=null)
                        return $inline_template_modified;
                      else
                          return $inline_content;
    }
    
    function getTableInlineForm($inline_props, $inline_props_template, $object, $show_buttons, 
            $local_prop_suffix, $current_row_num)   {
        $inline_input_properties_keys = array_keys($inline_props);
        //print_r($inline_props);
        $fullArrayedObject = $object->getFullPropArray();
        $inline_content = "";
        $inline_content .= $this->object_adapter->write_input_text_field_with_num(
                                    "id", 
                                    $fullArrayedObject['id'],$local_prop_suffix);
        foreach($inline_input_properties_keys as $inline_input_properties_key)    {
                          
                          if($inline_props_template==null)
                            $inline_content .= "<td>";
                          //echo $inline_input_properties_key."!!!!!!!!!!!!!!!!!";
                          //print_r($object->getFullPropArray());
                          //prop
                          
                          if (array_key_exists($inline_input_properties_key, $object->getFullPropArray())) {
                            
                             
                             $inline_content .= $this->object_adapter->write_input_text_field_with_num_and_placement(
                                    $inline_input_properties_key, 
                                    $fullArrayedObject[$inline_input_properties_key],
                                    $local_prop_suffix, "vertical");
                            
                            $local_manip_id = $this->class_name."_local_".$inline_input_properties_key.
                                    $current_row_num."_div";
                            $inline_content .= "<div id={$local_manip_id}></div>";
                            if ($show_buttons)  {
                                $local_js_params = " id:'id".$local_prop_suffix."',
                                    {$inline_input_properties_key}:'{$inline_input_properties_key}{$local_prop_suffix}' ";
                                $inline_content .= $this->get_link_button("Установить", "link_button_small_default",
                                    $this->generateLocalUpdateJSWithFilterWithExternalRefreshJS($this->class_name."_part_num", 
                                        "{$local_manip_id}", "{$local_js_params}", 
                                        $this->generateSelectJSWithFilter(
                                            "", $this->current_part_num, $this->class_name.$GLOBALS['dict_container_base'])),
                                                $this->class_name."_local_upd_link_id");
                            }
                                    ////),
                                            
                                    
                          } else    {
                              $inline_content .= "Не найдено свойства для встраиваемого поля";
                          }
                          
                          if($this->inline_props_template==null)    {
                            $inline_content .= "</td>";
                            //echo $inline_content;
                          }
                          else $inline_template_modified = str_replace("***___".$inline_input_properties_key, 
                                    $inline_content,$inline_template_modified);
                              
                      }
                      
                      if($this->inline_props_template!=null)
                        return $inline_template_modified;
                      else
                          return $inline_content;
    }
    
    function getExtParamsForm($ext_props, $inline_external_template, $object, $show_buttons, 
            $local_prop_suffix, $current_row_num)   {
        $inline_ext_content = "";
        //if($inline_external_template==null)
        //    $inline_ext_content .= "<td>";
        $current_arrayed_object = $object->getFullPropArray();
        //print_r($current_arrayed_object);
        //$reflectionClass = new ReflectionClass($ext_class_name."TableAdapter");
        //$ExtDictTAdapt = $reflectionClass->newInstanceArgs(array($this->dbconnector,
        //    "", $ext_class_name));
        //$ext_link_prop = $this->inline_external_params['id'];
        //$local_prop_suffix = "ext_".$this->class_name.$ext_class_name.$current_row_num;
        $ext_props_keys = array_keys($ext_props);
        
        foreach($ext_props_keys as $ext_props_key)   { 
            if (array_key_exists($ext_props_key, $current_arrayed_object)) {
                            
                if ($ext_props[$ext_props_key]=="id")  
                    $inline_ext_content .= $this->object_adapter->
                        write_input_text_field_with_num_and_placement(
                            "id", 
                            $current_arrayed_object[$ext_props_key],
                            $local_prop_suffix, "vertical");
                else
                    $inline_ext_content .= $this->object_adapter->
                        write_input_text_field_with_num_and_placement(
                            $ext_props_key, 
                            $current_arrayed_object[$ext_props_key],
                            $local_prop_suffix, "vertical");
                            
            } else    {
                $inline_ext_content .= "Не найдено свойства для встраиваемого поля";
            }
        }
                                            
        //$inline_ext_content .= "</td>";
        return $inline_ext_content;
    }
    
    function getAllExtJS($ext_props, $local_prop_suffix, $with_confirm, $object, $current_row_num, 
            $complete_function, $local_manip_id)  {
        $ext_properties_keys = array_keys($ext_props);
        //print_r($inline_props);
        $local_js_params = "";
        $index = 0;
        foreach($ext_properties_keys as $ext_properties_key)    {
                          
            if (array_key_exists($ext_properties_key, $object->getFullPropArray())) {
                            
                if ($index>0) $local_js_params .= " , ";
                if ($ext_props[$ext_properties_key]=="id")
                    $local_js_params .= " {$ext_props[$ext_properties_key]}:'id".$local_prop_suffix."'";
                else
                    $local_js_params .= " {$ext_props[$ext_properties_key]}:'".$ext_properties_key.$local_prop_suffix."'";
                                
                $index++;
                            
            } else    {
                echo "Не найдено свойства для встраиваемого внешнего поля";
            }
                              
        }
                      
        if ($with_confirm)
            $result_js = $this->generateLocalUpdateJSWithFilterWithExternalRefreshJS($this->class_name."_part_num", 
                "{$local_manip_id}", "{$local_js_params}", $complete_function);
        else
            $result_js = $this->generateLocalUpdateJSWithFilterWithExternalRefreshJSNoConfirm($this->class_name."_part_num", 
                "{$local_manip_id}", "{$local_js_params}", $complete_function);

        return $result_js;
    }
    
    function generateTable()    {
        $linked_headers = array();
        $linked_rows = array();
        echo "<div class=\"dict_table_default\" style=\"\"><table id=\""
            .$this->class_name.$GLOBALS['dict_table_base']
            ."\" class=\"table_dict\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\" 
            style=\" background-color: #ddd; border-color: #ddd; font-size: 11px; width:1024px;\" ><thead>";
        $linked_headers['detail_headers'] = 
            $this->detail_entities;
        $this->object_adapter->writeTableHeader($linked_headers);
        echo "</thead><tbody>";
        if ($this->select_collection!=null) {
        //echo "Всего: ".sizeof($this->select_collection);
        $current_row_num = 0;    
        foreach( $this->select_collection as $current_object ): 
                  $current_row_num++;
                  $linked_rows = array();
                  $linked_rows['detail_rows'] = 
                    $this->getDetailRows($current_object);
                  if ((sizeof($this->linked_entities_keys)==0)&&
                          (sizeof($this->inline_input_properties)==0)&&
                          (sizeof($this->inline_external_params)==0)&&
                          (sizeof($this->record_actions)==0))
                    $this->object_adapter->writeTableRowFull($current_object, $linked_rows, $current_row_num);
                  else  {
                      $this->object_adapter->writeTableRowFullWithoutClosedTag($current_object, $linked_rows, $current_row_num);
                      $current_arrayed_object = $current_object->getFullPropArray();
                      //echo "<td>".$this->generateDetailInfo($current_object->getFullPropArray())."</td>";
                      
                      $inline_inputs = array();
                      $inline_template_modified = $this->inline_props_template;
                      $local_prop_suffix = $this->class_name.$current_row_num;
                      echo $this->getTableInlineForm($this->inline_input_properties, $this->inline_props_template,
                              $current_object, true, $local_prop_suffix, $current_row_num);
                      
                      $inline_external_properties_keys = array_keys($this->inline_external_params);
                      $inline_extinputs = array();
                      $inline_ext_template_modified = $this->inline_external_template;
                      foreach($inline_external_properties_keys as $inline_ext_properties_key)    {
                          $inline_ext_content = "";
                          if($this->inline_external_template==null)
                            $inline_ext_content .= "<td>";
                          //print_r($current_arrayed_object);
                          $ext_class_name = $inline_ext_properties_key;
                          $reflectionClass = new ReflectionClass($ext_class_name."TableAdapter");
                          $ExtDictTAdapt = $reflectionClass->newInstanceArgs(array($this->dbconnector,
                                "", $ext_class_name));
                          //$ext_link_prop = $this->inline_external_params['id'];
                          $ext_props = $this->inline_external_params[$inline_ext_properties_key];
                          $local_prop_suffix = "ext_".$this->class_name.$ext_class_name.$current_row_num;
                          $ext_props_keys = array_keys($ext_props);
                          $local_js_params = "";
                          $index = 0;
                          foreach($ext_props_keys as $ext_props_key)   { 
                                if (array_key_exists($ext_props_key, $current_arrayed_object)) {
                            
                                if ($ext_props[$ext_props_key]=="id")  
                                    $inline_ext_content .= $ExtDictTAdapt->object_adapter->
                                        write_input_text_field_with_num_and_placement(
                                    "id", 
                                    $current_arrayed_object[$ext_props_key],
                                    $local_prop_suffix, "vertical");
                                else
                                $inline_ext_content .= $ExtDictTAdapt->object_adapter->
                                        write_input_text_field_with_num_and_placement(
                                    $ext_props_key, 
                                    $current_arrayed_object[$ext_props_key],
                                    $local_prop_suffix, "vertical");
                            
                                if ($index>0) $local_js_params .= " , ";
                                if ($ext_props[$ext_props_key]=="id")
                                    $local_js_params .= " {$ext_props[$ext_props_key]}:'id".$local_prop_suffix."'";
                                else
                                $local_js_params .= " {$ext_props[$ext_props_key]}:'".$ext_props_key.$local_prop_suffix."'";
                                
                               //{$inline_input_properties_key}:'{$inline_input_properties_key}{$local_prop_suffix}' ";
                                $index++;
               
                                    
                                } else    {
                                    $inline_ext_content .= "Не найдено свойства для встраиваемого поля";
                                }
                          }
                          
                          $local_manip_id = $this->class_name.$ext_class_name."_ext_local_".
                                    $current_row_num."_div";
                          $inline_ext_content .= "<div id={$local_manip_id}></div>";
                          $inline_ext_content .= $this->get_link_button("Установить", "link_button_small_default",
                                    $ExtDictTAdapt->generateLocalUpdateJSWithFilterWithExternalRefreshJS($this->class_name."_part_num", 
                                        "{$local_manip_id}", "{$local_js_params}", 
                                        $this->generateSelectJSWithFilter(
                                        "", $this->current_part_num, $this->class_name.$GLOBALS['dict_container_base'])),
                                            $this->class_name."_local_ext_upd_link_id");
                          
                          //if($this->inline_external_template==null)    {
                            $inline_ext_content .= "</td>";
                            echo $inline_ext_content;
                          //}
                          //else $inline_ext_template_modified = str_replace("***___".$inline_input_properties_key, 
                          //          $inline_content,$inline_ext_template_modified);
                              
                      }
                      
                      $linked_entities_keys = array_keys($this->linked_entities_keys);
                      foreach( $linked_entities_keys as $linked_entities_key )    {
                        $class_name = $linked_entities_key;
                        $linked_entity_name = "Связ. сущности";
                        if (isset($this->linked_entities_names[$linked_entities_key]))  {
                            $linked_entity_name = $this->linked_entities_names[$linked_entities_key];
                        }
                        $linked_form_height = 200;
                        if (isset($this->linked_form_heights[$linked_entities_key]))  {
                            $linked_form_height = $this->linked_form_heights[$linked_entities_key];
                        }
                        
                        $reflectionClass = new ReflectionClass($class_name."TableAdapter");
                        $SelectDictTAdapt = $reflectionClass->newInstanceArgs(array($this->dbconnector,
                            "", $class_name));
                        
                        $linked_object_key_array = $this->linked_entities_keys[$linked_entities_key];
                        //print_r($linked_object_key_array);
                        $SelectDictTAdapt->getSelfLinkedObjectForm($linked_object_key_array, 
                                $current_row_num,
                                $this->generateSelectJSWithFilter(
                                                "", $this->current_part_num, 
                                                $this->class_name.$GLOBALS['dict_container_base']),
                                $current_arrayed_object, $linked_form_height, $linked_entity_name);
                      }
                      
                      echo "<td><div id=\"actions_{$this->class_name}{$current_row_num}\" 
                      style=\"display:none;\">
                      <table border=\"0\"><tr>";
                      foreach($this->record_actions as $rec_action) {
                          echo "<td>".$rec_action->getAbsActionButton($current_object)
                                  //getActionFormHTML($current_object, $current_row_num)
                                  ."</td>";
                      }
                      echo "</tr></table></div></td>";
                      
                      echo "</tr>";
                  }
                endforeach;
        }
        echo "</tbody></table>";
        
        //$this->writePager($this->class_name.$GLOBALS['dict_table_pager_base']);
        echo "</div>";
    }
    
    function getCurrentSelectJSWithFilter() {
        return $this->generateSelectJSWithFilter(
                                                "", $this->class_name."_part_num", 
                                                //$this->current_part_num, 
                                                $this->class_name.$GLOBALS['dict_container_base']);
    }
    
    function getExternalParamsObjectFrom($linked_object_key_array, $current_row_num, 
            $select_js, $current_arrayed_object, $linked_form_height, $linked_entity_name)  {
        return "";
    }
    
    function getSelfLinkedObjectForm($linked_object_key_array, $current_row_num, 
            $select_js, $current_arrayed_object, $linked_form_height, $linked_entity_name)  {
        $linked_object_keys = array_keys($linked_object_key_array);
        $linked_object = $this->object_adapter->getDataClassInstance();
                        foreach ($linked_object_keys as $linked_object_key) {
                            
                            $linked_arrayed_object = (array)$linked_object;
                            
                            $current_res_div = $this->class_name.$current_row_num;
                            $current_res_div .= $linked_object_key_array[$linked_object_key]."_res_div";
                            if (isset($current_arrayed_object[$linked_object_key]))  {
                                if (array_key_exists($linked_object_key, $linked_arrayed_object))   {
                                    $linked_object_relative_key = $linked_object_key_array[$linked_object_key];
                                    
                                    $linked_object->$linked_object_relative_key = 
                                       $current_arrayed_object[$linked_object_key];
                                    $elm_suffix = $this->class_name.$current_row_num;
                                    echo "<td style=\"width:70px;\">";
                                    //echo "<div class=\"panel\"><a class=\"handle\" 
                                    //    href=\"\">Не работают JS</a><h3><span lang=\"ru\">
                                    //    Заголовок</span></h3><br><span lang=\"ru\">";
                                    echo "<span id=\"anchor_{$elm_suffix}\"></span>
                                        <p class=\"slide\"><div id=\"{$elm_suffix}_panel_btn\" class=\"hidden_panel\" 
                                        style=\"height: {$linked_form_height}px;\">";
                                    $this->object_adapter->writeInsertEditFormWithNum(
                                            $linked_object,$this->
                                            getSelectArraysWithoutParentLists($linked_object_key_array),
                                            $elm_suffix);
                                    echo "<div id=\"{$current_res_div}\"></div>";
                                    $this->generate_link_button("Добавить", "link_button_small_default",
                                        $this->generateInsertJSWithFilterWithExternalRefreshJS(0,
                                        $current_res_div,
                                        $select_js,$elm_suffix),
                                        $this->class_name."_add_link_id");
                                    
                                    echo "</div><a id=\"{$elm_suffix}_btn\" href=\"#anchor_{$elm_suffix}\" 
                                    class=\"btn-slide\" onclick=\" $('#{$elm_suffix}_panel_btn').
                                        slideToggle('slow'); $(this).toggleClass('active'); \">
                                        {$linked_entity_name}</a></p>";
                                    //echo "</span></div>"; 
                                    echo "</td>";
                                    
                                }   else    {
                                echo "В привязываемом объекте нет привязываемого поля ".$linked_object_key."!";
                                }
                            }   else    {
                                echo "В текущем объекте нет привязываемого поля ".$linked_object_key.", или оно пустое!";
                            }
                        }
    }
    
    function generateDictHeader()   {
        echo "<div class=\"dict_header_default\">Таблица: <b><span style=\"font-size:14px;\">{$this->dict_header}</span></b>. ".
                " Часть: ".($this->current_part_num+1)." [".
                        ($this->part_capacity*$this->current_part_num+1)." - ".
                        (($this->part_capacity*$this->current_part_num)+$this->all_capacity)."] 
                  Всего: колич.-{$this->last_query_result_size}, частей-".(int)($this->last_query_result_size/$this->part_capacity+1).
                  " Осталось: кол-во-".(int)($this->last_query_result_size-($this->part_capacity*$this->current_part_num)-$this->all_capacity).", 
                  частей-".(int)(($this->last_query_result_size-($this->part_capacity*$this->current_part_num)-$this->all_capacity)/$this->part_capacity+(($this->last_query_result_size-($this->part_capacity*$this->current_part_num)-$this->all_capacity)%$this->part_capacity>0?1:0))."<input type=\"hidden\" 
                id=\"".$this->class_name."_part_num\" size=\"8\" 
                value=\"{$this->current_part_num}\" readonly></div>";
    }
    
    function generateReportDictHeader()   {
        echo "<div class=\"dict_header_default\">Таблица: <b><span style=\"font-size:14px;\">{$this->dict_header}</span></b>. ".
                " Всего: ".sizeof($this->select_collection).". <input type=\"hidden\" 
                id=\"".$this->class_name."_part_num\" size=\"8\" 
                value=\"{$this->current_part_num}\" readonly></div>";
    }
    
    function generateDictDetail()   {
        echo "<br/><br/>Подчиненные таблицы:<div id=\"".$this->class_name."_detail_container"."\" class=\"dict_detail_default\"></div>";
    }
    
    function getDetailHeaders() {
        
    }
    
    function getDetailRows($object)    {
        $row_details_links = array();
        $detail_keys = array_keys($this->detail_entities);
        foreach ($detail_keys as $detail_key)   {
            $reflectionClass = new ReflectionClass($detail_key."TableAdapter");
            $DictTAdapt = $reflectionClass->newInstanceArgs(array($this->dbconnector,
                "",$detail_key));
            //if (isset(
            $values_filters_keys = array_keys($DictTAdapt->values_filter_values);
            foreach ($values_filters_keys as $values_filters_key)   {
                $DictTAdapt->values_filter_values[$values_filters_key] = null;
            }
            $DictTAdapt->filters_values[$this->detail_entities[$detail_key]['detail_id']] = 
                $object->getId();//[$this->detail_entities[$detail_key]['master_id']];
            $row_details_links[$detail_key."_".$this->detail_entities[$detail_key]['detail_id']] = 
                    array ("name"=>$this->detail_entities[$detail_key]['detail_header'],
                        "jscript"=>$DictTAdapt->generateSelectJSWithFilterTAdaptParams
                            ("",0,$this->class_name."_detail_container"));
            
        }
        //print_r($row_details_links);
        return $row_details_links;
    }
    
    function generateCurrentTablePart()   {
        //$this->current_select_collection_part 
        $start_pos = $this->part_capacity*$this->current_part_num;
        $select_limit = $this->part_capacity;
        //echo $this->all_capacity."sssss";
        if($this->all_capacity<($start_pos+1)) {
            $this->select_collection = array();
        } else {
            //if ((($this->all_capacity - $start_pos)/$this->part_capacity)>0)    {
            //    $select_limit = $this->part_capacity;
            //}
            //else
            //    $select_limit = (($this->all_capacity - $start_pos)%$this->part_capacity);
            
            //print_r($this->select_collection);
            //echo sizeof($this->select_collection)."wwwww".$select_limit;
            $this->select_collection = new LimitIterator($this->select_collection, 
                $start_pos, $select_limit);
            //echo sizeof($this->select_collection)."wwwww";
        }
            
    }
    
    function generateTablePartByNum($part_num)  {
        $this->current_part_num = $part_num;
        $this->generateCurrentTablePart();
    }
	
	function getBaseFilterOrClause()	{
		$counter = 0;
		$out = " (1=0) ";
		$or_values_filter_keys = array_keys($this->base_or_condition_params);
		if ((sizeof($this->base_or_condition_params)>0))	{
			$out = " ( ";
			
			foreach($or_values_filter_keys as $or_values_filter_key)    {
            
            if($counter>0)  {
                $out.=" OR ";
            }
            
            if (isset($this->values_filter_array[$or_values_filter_key]))  {
                if (($this->values_filter_values[$or_values_filter_key]!=null)&&
                        ($this->values_filter_values[$or_values_filter_key]!="null")&&
                        ($this->values_filter_values[$or_values_filter_key]!=-1)&&
                        ($this->values_filter_values[$or_values_filter_key]!="-1"))   {
                    
                    if ($this->values_filter_array[$or_values_filter_key]!=
                             str_replace("***___".$or_values_filter_key,
                             $this->values_filter_values[$or_values_filter_key], 
                             $this->values_filter_array[$or_values_filter_key]))
                    $out.=(" (".str_replace("***___".$or_values_filter_key,
                             $this->values_filter_values[$or_values_filter_key], 
                             $this->values_filter_array[$or_values_filter_key]).") ");
                    else $out.=" (1=0) ";    
                }
                else $out.=" (1=0) ";
            }
                else $out.=" (1=0) ";
            
            $counter++;
			}
			
			$out .= " ) ";
		}
		return $out;
	}
    
    function getFilterWhereClause()   {
        $counter = 0;
        if ($this->not_show_closed)
            $out = "(closed<>1)";
        else
            $out = "(1=1)";
        $filter_keys = array_keys($this->filters_array);
        $values_filter_keys = array_keys($this->values_filter_array);
        if ((sizeof($this->filters_array)>0)||(sizeof($this->values_filter_array)>0)) {
            
        if ($this->not_show_closed)
            $out = "(closed<>1) AND (";
        else
            $out = "(";
            
        foreach($filter_keys as $filter_key)    {
            
            
            
            if (isset($this->filters_values[$filter_key]))  {
                if (($this->filters_values[$filter_key]!=null)&&
                        ($this->filters_values[$filter_key]!="null")&&
                        ($this->filters_values[$filter_key]!=-1)&&
                        ($this->filters_values[$filter_key]!="-1"))   {
                    
                    if($counter>0)  {
                        $out.=" AND ";
                    }
                    
                    $out.=(" (".$filter_key."=".$this->filters_values[$filter_key].") ");
                }
                else    {
                    if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                    //$out.=" ";
                }
            }
                else    {
                    if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                    //$out.=" (1=1) ";
                }
            
            $counter++;
        }
        
        foreach($values_filter_keys as $values_filter_key)    {
            
            
            
			if (isset($this->values_filter_values[$values_filter_key]))  {
                if (($this->values_filter_values[$values_filter_key]!=null)&&
                        ($this->values_filter_values[$values_filter_key]!="null")&&
                        ($this->values_filter_values[$values_filter_key]!=-1)&&
                        ($this->values_filter_values[$values_filter_key]!="-1"))   {
                    
                    if ($this->values_filter_array[$values_filter_key]!=
                             str_replace("***___".$values_filter_key,
                             $this->values_filter_values[$values_filter_key], 
                             $this->values_filter_array[$values_filter_key]))   {
                        
                        if($counter>0)  {
                            $out.=" AND ";
                        }
                        
                        $out.=(" (".str_replace("***___".$values_filter_key,
                             $this->values_filter_values[$values_filter_key], 
                             $this->values_filter_array[$values_filter_key]).") ");
                             }
                    else    {
                        if($counter==0)  {
                            $out.=" (1=1) ";
                        }
                        //$out.=" (1=1) ";    
                    }
                }
                else    {
                    if($counter==0)  {
                            $out.=" (1=1) ";
                        }
                    //$out.=" (1=1) ";
                }
            }
                else    {
                    if($counter==0)  {
                            $out.=" (1=1) ";
                        }
                    //$out.=" (1=1) ";
                }
            
            $counter++;
        }
        
        $out .= ")";
        
        }
        
        //echo $out;
            
        return $out;
    }
    
    function getFilterWhereClauseWithoutOrParams()   {
        $counter = 0;
        if ($this->not_show_closed)
            $out = "(closed<>1)";
        else
            $out = "(1=1)";
        $filter_keys = array_keys($this->filters_array);
        $values_filter_keys = array_keys($this->values_filter_array);
        if ((sizeof($this->filters_array)>0)||(sizeof($this->values_filter_array)>0)) {
            
        if ($this->not_show_closed)
            $out = "(closed<>1) AND (";
        else
            $out = "(";
            
        foreach($filter_keys as $filter_key)    {
            
            
            
            if(!isset($this->base_or_condition_params[$filter_key]))	{
			if (isset($this->filters_values[$filter_key]))  {
                if (($this->filters_values[$filter_key]!=null)&&
                        ($this->filters_values[$filter_key]!="null")&&
                        ($this->filters_values[$filter_key]!=-1)&&
                        ($this->filters_values[$filter_key]!="-1"))   {
                    
                    if($counter>0)  {
                        $out.=" AND ";
                    }
                    
                    $out.=(" (".$filter_key."=".$this->filters_values[$filter_key].") ");
                }
                else    {
                    if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                    //$out.=" (1=1) ";
                }
            }
                else    {
                    if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                    //$out.=" (1=1) ";
                }
            }
            else    {
                if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                //$out.=" (1=1) ";
            }
            
            $counter++;
        }
        
        foreach($values_filter_keys as $values_filter_key)    {
            
            //echo "!!!".$values_filter_key;
			if(!array_key_exists($values_filter_key, $this->base_or_condition_params))	{
			if (isset($this->values_filter_values[$values_filter_key]))  {
                if (($this->values_filter_values[$values_filter_key]!=null)&&
                        ($this->values_filter_values[$values_filter_key]!="null")&&
                        ($this->values_filter_values[$values_filter_key]!=-1)&&
                        ($this->values_filter_values[$values_filter_key]!="-1"))   {
                    
                    if ($this->values_filter_array[$values_filter_key]!=
                             str_replace("***___".$values_filter_key,
                             $this->values_filter_values[$values_filter_key], 
                             $this->values_filter_array[$values_filter_key]))   {
                        
                        if($counter>0)  {
                            $out.=" AND ";
                        }
                        
                        $out.=(" (".str_replace("***___".$values_filter_key,
                             $this->values_filter_values[$values_filter_key], 
                             $this->values_filter_array[$values_filter_key]).") ");
                             }
                    else    {
                        if($counter==0)  {
                            $out.=" (1=1) ";
                        }
                        //$out.=" (1=1) ";
                    }
                }
                else    {
                    if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                    //$out.=" (1=1) ";
                }
            }
                else    { 
                    if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                    //$out.=" (1=1) ";
                }
			}
            else    { 
                if($counter==0)  {
                        $out.=" (1=1) ";
                    }
                //$out.=" (1=1) ";
            }
            
            $counter++;
        }
        
        $out .= ")";
        
        }
        
        //echo $out;
            
        return $out;
    }
    
    function prepareFilterArray($params)    {
        if (isset($params['part_num'])) {
            $this->current_part_num = (int)$params['part_num'];
            //echo $this->current_part_num."sssss";
        }
        
        $param_keys = array_keys($params);
        foreach($param_keys as $param_key)  { 
            //echo "sss";
            if ($params[$param_key]=="null")
               $params[$param_key]=null;
            
            if (array_key_exists($param_key, $this->filters_values))  {
                
                $this->filters_values[$param_key] = $params[$param_key];
            }
            
            if (array_key_exists($param_key, $this->values_filter_values))  {
                
                $this->values_filter_values[$param_key] = $params[$param_key];
            }
        }
    }
    
    function prepareCustomActionParamArray($action_name, $params) {
        if(array_key_exists($action_name, $this->custom_action_instructions)&&
                array_key_exists($action_name, $this->custom_action_params))    {
            
        }   else {
            echo "Не обнаружено соответствующего действия или его параметров!";
            return null;
        }
    }
    
    function getFilterJSParams()    {
        $counter = 0;
        $out = "";
        $filter_keys = array_keys($this->filters_array);
        foreach($filter_keys as $filter_key)    {
            if($counter>0)  {
                $out.=", ";
            }
            
            $out.=($filter_key.":'".$this->class_name."_filt_".$filter_key."'");
            
            $counter++;
        }
        
        $filter_keys = array_keys($this->values_filter_array);
        foreach($filter_keys as $filter_key)    {
            if($counter>0)  {
                $out.=", ";
            }
            
            $out.=($filter_key.":'".$this->class_name."_filt_".$filter_key."'");
            
            $counter++;
        }
        
        return $out;
    }
    
    function getFilterJSTAdaptParams()    {
        $counter = 0;
        $out = "";
        $filter_keys = array_keys($this->filters_values);
        foreach($filter_keys as $filter_key)    {
            if($counter>0)  {
                $out.=", ";
            }
            
            $out.=($filter_key.":'".$this->filters_values[$filter_key]."'");
            
            $counter++;
        }
        
        $filter_keys = array_keys($this->values_filter_values);
        foreach($filter_keys as $filter_key)    {
            if($counter>0)  {
                $out.=", ";
            }
            
            $out.=($filter_key.":'".$this->values_filter_values[$filter_key]."'");
            
            $counter++;
        }
        
        return $out;
    }
    
    function getFiltersArrays() {
        //print_r($this->filters_array);
        //print_r(array_merge ($this->filters_array, 
        //            $this->filter_values_select_keys));
        if (sizeof($this->filter_values_select_keys)==0)
            $db_filters_array = $this->getArraysByKeys($this->filters_array, $this->filters_key_filters);
        else
            $db_filters_array = $this->getArraysByKeys(array_merge ($this->filters_array, 
                    $this->filter_values_select_keys), $this->filters_key_filters);
        $filter_keys = array_keys($db_filters_array);
        $modified_filter_array = array();
        foreach($filter_keys as $filter_key)    {
            array_unshift($db_filters_array[$filter_key], array("id"=>-1, 
                "select_name"=>"Любое значение"));
            $modified_filter_array[$this->class_name."_filt_".$filter_key] = 
                $db_filters_array[$filter_key];    
        }
        //print_r($db_filters_array);
        return $modified_filter_array;
    }
    
    function getValuesFilterArray() {
        $filter_keys = array_keys($this->values_filter_values);
        $modified_filter_array = array();
        foreach($filter_keys as $filter_key)    {
            $modified_filter_array[$this->class_name."_filt_".$filter_key] = 
                $this->values_filter_values[$filter_key];    
        }
        //print_r($db_filters_array);
        return $modified_filter_array;
    }
    
    function resetAllFilters()  {
        $this->base_filter = " (closed<>1) ";
        
        reset($this->filters_values);
        while (list ($key, $val) = each ($this->filters_values) ) :
            $this->filters_values[$key]=null;
        endwhile;
        
        reset($this->values_filter_values);
        while (list ($key, $val) = each ($this->values_filter_values) ) :
            $this->values_filter_values[$key]=null;
        endwhile;
        //print_r($this->filters_values);
        //print_r($this->values_filter_values);
    }
    
    function generateFiltersForm($filtered_object_container)  {
        
        echo "<div id=\"dict_filter_form\" class=\"filter_form_default\">";
        if ((sizeof($this->filters_array)>0)||(sizeof($this->values_filter_array)>0)) {
        $this->object_adapter->writeFilterFormWithValues($this->getFiltersArrays(), 
                $this->getValuesFilterArray(), array_merge($this->filters_values, $this->values_filter_values));
        $this->generate_link_button("Применить фильтр", "link_button_default",
            $this->generateSelectJSWithFilter("", 0, $filtered_object_container),$this->class_name."_filter_link_id");
        }
        else
            echo "Нет параметров фильтрации";
        echo "</div>";
        
    }
    
    function generateCustomFiltersForm($filtered_object_container, $select_mode, $action_caption)  {
        
        echo "<div id=\"dict_filter_form\" class=\"filter_form_default\">";
        if ((sizeof($this->filters_array)>0)||(sizeof($this->values_filter_array)>0)) {
        $this->object_adapter->writeFilterFormWithValues($this->getFiltersArrays(), 
                $this->getValuesFilterArray(), array_merge($this->filters_values, 
                $this->values_filter_values));
        $this->generate_link_button($action_caption, "link_button_default",
            $this->generateSelectJSWithFilterAndMode("", 0, $filtered_object_container, $select_mode),
                $this->class_name."_filter_link_id");
        }
        else
            echo "Нет параметров фильтрации";
        echo "</div>";
        
    }
    
    function generatePagerForm($pager_object_container)  {
        
        echo "<div id=\"dict_pager_form\" class=\"pager_form_default\">";
        //if (sizeof($this->filters_array)>0) {
        //$this->object_adapter->writeFilterForm($this->getFiltersArrays(), $this->getValuesFilterArray());
        //echo $this->current_part_num;
        $this->generate_link_button("Первые ".$this->part_capacity, "link_button_default",
            $this->generateSelectJSWithFilter("", 0, $pager_object_container),$this->class_name."_first_page_link_id");
        if ($this->current_part_num-1>=0)   {
            $this->generate_link_button("Пред. ".$this->part_capacity, "link_button_default",
                $this->generateSelectJSWithFilter("", $this->current_part_num-1, $pager_object_container),$this->class_name."_prev_page_link_id");
        }
        $this->generate_link_button("След. ".$this->part_capacity, "link_button_default",
            $this->generateSelectJSWithFilter("", $this->current_part_num+1, $pager_object_container),$this->class_name."_next_page_link_id");
        $this->generate_link_button(">>*10", "link_button_default",
            $this->generateSelectJSWithFilter("", $this->current_part_num+10, $pager_object_container),$this->class_name."_next_page_link_id");
        $this->generate_link_button(">>*30", "link_button_default",
            $this->generateSelectJSWithFilter("", $this->current_part_num+30, $pager_object_container),$this->class_name."_next_page_link_id");
        $this->generate_link_button(">>*100", "link_button_default",
            $this->generateSelectJSWithFilter("", $this->current_part_num+100, $pager_object_container),$this->class_name."_next_page_link_id");
        //}
        //else
        //    echo "Нет параметров фильтрации";
        echo "</div>";
        
    }
    
    function generateInsertForm() {
        echo "<div id=\"dict_manip_form\" class=\"manip_form_default\">
            <div class=\"current_object_identity\"></div>";
        $this->object_adapter->writeInsertEditForm(null,$this->getSelectArrays());
        $this->write_div("dict_table_manip_res_div","","");
        echo "<br/>";
        $this->generate_link_button("Добавить", "link_button_default",
            $this->generateInsertJSWithFilter(0, "dict_table_manip_res_div", ""),$this->class_name."_add_link_id");
        $this->generate_link_button_with_style("Изменить", "change_button_default",
            $this->generateUpdateJSWithFilter($this->class_name."_part_num", "dict_table_manip_res_div", ""),
            $this->class_name."_upd_link_id", "visibility: hidden;");
        if ($_SESSION['enable_deleting'])
            $this->generate_link_button_with_style("Удаление", "change_button_default",
                $this->generateDeleteJSWithFilter($this->class_name."_part_num", "dict_table_manip_res_div", ""),
                    $this->class_name."_del_link_id", "visibility: hidden;");
        echo "</div>";
    }
    
    function generateFastIdSelectListForm($ajax_container_id, $set_field_id, $outher_suffix) {
        return "<div id=\"{$this->class_name}_fast_select_id_{$outher_suffix}\"></div>".$this->object_adapter->getFormWithNumWithValuesWithFreeParamsAndTemplate(
                null, $this->fast_id_select_params, array(), $outher_suffix,
                array(), array(), $this->fast_id_select_form_template, 
                array( "onKeyDown"=>(" if(event.keyCode==13) { ".$this->generateFastIdSelectListFormJS
                ("{$this->class_name}_fast_select_id_{$outher_suffix}", $set_field_id, $outher_suffix)." } ") ));
    }
    
    function generateFastIdSelectListFormJS($ajax_container_id, $set_field_id, $outher_suffix) {
        $fast_id_js_params = " set_field_id:'{$set_field_id}' ";
        $fast_id_select_params = array_keys($this->fast_id_select_params);
        foreach($fast_id_select_params as $fast_id_select_param)    {
            $fast_id_js_params .= ", {$fast_id_select_param}:'{$this->fast_id_select_params[$fast_id_select_param]}{$outher_suffix}' ";
        }
        $result_js = "ajaxGetRequest('".$GLOBALS['out_table_php']."', '{$this->class_name}', 
         '{$GLOBALS['fast_id_list_select_mode']}', { ".$fast_id_js_params." },
         '0', '{$ajax_container_id}');";
        return $result_js; 
    }
    
    function getFastIdSelectList($params) {
        //print_r($_GET);
        $set_field_array = array("set_field_id"=>$_GET['set_field_id']);
        $list_data_instruction = $this->getTextFromTemplate($_GET,$this->fast_id_select_sql_template);
        //echo $list_data_instruction;
        $list_data = $this->dbconnector->query_both_to_array($list_data_instruction);
        if (count($list_data)>0) {
            for($c=0;$c<count($list_data);$c++)
              echo "<li  class=\"fast_id_select\" onclick=\" $('.fast_id_select').removeClass('active_fast'); if (!this.className.match(new RegExp('(\\s|^)'+'active_fast'+'(\\s|$)'))) this.className += (' '+'active_fast'); \">".$this->getTextFromTemplate(array_merge ($set_field_array, $list_data[$c]), 
              $this->fast_id_list_row_template)."</li>";
        }
        return "В разработке";
    }
    
    function generateDictContainer()    {
        
    }
    
    function generateSelectJS($filter, $load_indicator_id, $result_container_id)    {
        return "ajaxGetRequest('".$GLOBALS['out_table_php']."', '{$this->class_name}', 
         '{$GLOBALS['select_mode']}', { },
         '{$load_indicator_id}', '{$result_container_id}');";
    }
    
    function generateSelectJSWithFilter($filter, $load_indicator_id, $result_container_id)    {
        return "ajaxGetRequest('".$GLOBALS['out_table_php']."', '{$this->class_name}', 
         '{$GLOBALS['select_mode']}', { ".$this->getFilterJSParams()." },
         '{$load_indicator_id}', '{$result_container_id}');";
    }
    
    function generateSelectJSWithFilterAndMode($filter, $load_indicator_id, $result_container_id, $select_mode)    {
        return "ajaxGetRequest('".$GLOBALS['out_table_php']."', '{$this->class_name}', 
         '{$select_mode}', { ".$this->getFilterJSParams()." },
         '{$load_indicator_id}', '{$result_container_id}');";
    }
    
    function generateSelectJSWithFilterTAdaptParams($filter, $load_indicator_id, $result_container_id)    {
        return "ajaxGetRequest('".$GLOBALS['out_detail_table_php']."', '{$this->class_name}', 
         '{$GLOBALS['select_mode']}', { ".$this->getFilterJSTAdaptParams()." },
         '{$load_indicator_id}', '{$result_container_id}');";
    }
    
    function generateInsertJS($load_indicator_id, $result_container_id, $refresh_after_js)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['insert_manip_mode']}', { ".$this->object_adapter->generateAddInsertJSParams($GLOBALS['insert_manip_mode'])." },
         '{$load_indicator_id}', '{$result_container_id}'); } );";
    }
    
    function generateUpdateJS($load_indicator_id, $result_container_id, $refresh_after_js)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['update_manip_mode']}', { ".$this->object_adapter->generateAddInsertJSParams($GLOBALS['update_manip_mode'])." },
         '{$load_indicator_id}', '{$result_container_id}'); } );";
    }
    
    function generateDeleteJS($load_indicator_id, $result_container_id, $refresh_after_js)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['delete_manip_mode']}', { ".$this->object_adapter->generateAddInsertJSParams($GLOBALS['delete_manip_mode'])." },
         '{$load_indicator_id}', '{$result_container_id}'); } );";
    }
    
    function generateInsertJSWithFilter($load_indicator_id, $result_container_id, $refresh_after_js)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['insert_manip_mode']}', { ".$this->object_adapter->generateAddInsertJSParams($GLOBALS['insert_manip_mode'])." },
         '{$load_indicator_id}', '{$result_container_id}', { ".$this->getFilterJSParams()." }); } );";
    }
    
    function generateInsertJSWithFilterWithExternalRefreshJS($load_indicator_id, $result_container_id, $refresh_after_js, $current_row_num)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['insert_manip_mode']}', { ".
         $this->object_adapter->generateAddInsertJSParamsWithNum($GLOBALS['insert_manip_mode'],$current_row_num)." },
         '{$load_indicator_id}', '{$result_container_id}', null, function (next_function) { return ".$refresh_after_js." } ); } );";
    }
    
    function generateInsertJSWithFilterWithExternalRefreshJSNoConfirm($load_indicator_id, $result_container_id, $refresh_after_js, $current_row_num)  {
        return " ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['insert_manip_mode']}', { ".
         $this->object_adapter->generateAddInsertJSParamsWithNum($GLOBALS['insert_manip_mode'],$current_row_num)." },
         '{$load_indicator_id}', '{$result_container_id}', null, function (next_function) { return ".$refresh_after_js." } ); ";
    }
    
    function generateUpdateJSWithFilter($load_indicator_id, $result_container_id, $refresh_after_js)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['update_manip_mode']}', { ".$this->object_adapter->generateAddInsertJSParams($GLOBALS['update_manip_mode'])." },
         '{$load_indicator_id}', '{$result_container_id}', { ".$this->getFilterJSParams()." }); } );";
    }
    
    function generateLocalUpdateJS($load_indicator_id, $result_container_id, $partial_js_params)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['partial_update_manip_mode']}', { ".$partial_js_params." },
         '{$load_indicator_id}', '{$result_container_id}'); } );";
    }
    
    function generateLocalUpdateJSWithFilterWithExternalRefreshJS($load_indicator_id, $result_container_id, $partial_js_params, $refresh_after_js)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['partial_update_manip_mode']}', { ".$partial_js_params." },
         '{$load_indicator_id}', '{$result_container_id}', null, function (next_function) { return ".$refresh_after_js." }); } );";
    }
    
    function generateLocalUpdateJSWithFilterWithExternalRefreshJSNoConfirm($load_indicator_id, $result_container_id, $partial_js_params, $refresh_after_js)  {
        return " ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['partial_update_manip_mode']}', { ".$partial_js_params." },
         '{$load_indicator_id}', '{$result_container_id}', null, 
         function (next_function) { return ".$refresh_after_js." }); ";
    }
    
    function generateDeleteJSWithFilter($load_indicator_id, $result_container_id, $refresh_after_js)  {
        return " actionConfirm(function (action_function) { closeConfirm(); ajaxGetRequest('".$GLOBALS['add_update_delete_php']."', '{$this->class_name}', 
         '{$GLOBALS['delete_manip_mode']}', { ".$this->object_adapter->generateAddInsertJSParams($GLOBALS['delete_manip_mode'])." },
         '{$load_indicator_id}', '{$result_container_id}', { ".$this->getFilterJSParams()." }); } );";
    }
    
    function insertDataObject($get_params)  {
        $additional_instruction = "";
        
        if (array_count_values($this->multi_addit_instructions)>0)  {
            //
            $addit_instr_keys = array_keys($this->multi_addit_instructions);
            foreach ($addit_instr_keys as $addit_instr_key) {
                if (isset($get_params[$addit_instr_key]))   {
                    $search_str=$get_params[$addit_instr_key];
                    //print_r($get_params);
                    while (true) {
                        if (substr_count($search_str,"***___")>0) {
                            $pref_marker_end_pos = strpos($search_str, "***___")+6;
                            //echo ">".$pref_marker_end_pos;
                            $search_str = substr($search_str, $pref_marker_end_pos);
                            //echo ">>".$search_str;
                            if (strlen($search_str)>0)  {
       
                                if (substr_count($search_str,"***___")>0) {
                                    $next_prev_marker_start = strpos($search_str, "***___");
                                    //echo ">>>".$next_prev_marker_start;
                                    if ($next_prev_marker_start>0)  {
                                        $cut_value = substr($search_str, 0,$next_prev_marker_start);
                                        $search_str = substr($search_str, $next_prev_marker_start);
                                    }
                                    else    {
                                        $cut_value="";
                                        continue;
                                    }
                                    //
                                }
                                else    {
                                    $cut_value = $search_str;
                                    $search_str = "";
                                }
                                
                                //echo ">>>>".$cut_value;
                                //echo ">>>>>".$search_str;
                                
                                if (strlen($cut_value)>0)   {
                                    $num_len = strspn($cut_value, "1234567890");
                                    //echo ">>>>>>".$num_len;
                                    if($num_len>0)  {
                                        
                                        $cut_value = substr($cut_value, 0,$num_len);
                                        //echo ">>>>>>>".$cut_value;
                                        $template_modified = $this->
                                                multi_addit_instructions[$addit_instr_key];
                                        if ($template_modified!=str_replace("***___{$addit_instr_key}", 
                                            $cut_value,$template_modified)) {
                                                $template_modified = str_replace("***___{$addit_instr_key}", 
                                                    $cut_value,$template_modified);
                                                $additional_instruction .= $template_modified;
                                            }
                                         else
                                             break;
                                    }   
                                    else
                                        break;
                                }
                                    
                            }   else
                                break;
                            
                        }
                        else
                            break;
                    }
                }
            }
        }
        return $this->dbconnector->exec_with_prepare_and_params($this->
                insert_instruction_template.$additional_instruction, $get_params);
    }
    
    function updateDataObject($get_params)  {
        return $this->dbconnector->exec_with_prepare_and_params($this->update_instruction_template, $get_params);
    }
    
    function deleteDataObject($get_params)  {
        return $this->dbconnector->exec_with_prepare_and_params($this->delete_instruction_template, $get_params);
    }
    
    function fastObjAppend($get_params)   {
        $update_instruction = "INSERT INTO `".$this->table_name."` ( ";
        $template_modified = $this->addit_fast_sql_template;
        if ($template_modified==null)
            $template_modified = "";
        if (isset($get_params['id']))   {
            unset($get_params['id']);
        }
            $get_params_keys = array_keys($get_params);
            $update_prop_count = sizeof($get_params);
            $instruction_params = array();
            
            foreach($get_params_keys as $get_params_key)    {
                
                $instruction_params[":".$get_params_key] = $get_params[$get_params_key];
                $update_instruction .= " `{$get_params_key}` ";
                if ($update_prop_count>1)   
                    $update_instruction .= ", ";
                else
                    $update_instruction .= " ";
                $update_prop_count--;
            }
            
            $update_instruction .= ") VALUES( ";
            $update_prop_count = sizeof($get_params);
            
            foreach($get_params_keys as $get_params_key)    {
                $update_instruction .= " :{$get_params_key} ";
                if ($update_prop_count>1)   
                    $update_instruction .= ", ";
                else
                    $update_instruction .= " ";
                $update_prop_count--;
            }
            
            $update_instruction .= ");";
            //echo $update_instruction;
            //print_r($instruction_params);
            return "Выполнено ".$this->dbconnector->
                    exec_with_prepare_and_params($update_instruction.$template_modified, $instruction_params);
    }
    
    function updateObjectPartial($get_params)   {
        $update_instruction = "UPDATE `".$this->table_name."` SET ";
        if (isset($get_params['id']))   {
            $update_id = $get_params['id'];
            unset($get_params['id']);
            $get_params_keys = array_keys($get_params);
            $update_prop_count = sizeof($get_params);
            $instruction_params = array();
            foreach($get_params_keys as $get_params_key)    {
                if(array_key_exists($get_params_key, $this->base64_encode_inline_props))
                    $instruction_params[":".$get_params_key] = base64_encode($get_params[$get_params_key]);
                else
                    $instruction_params[":".$get_params_key] = $get_params[$get_params_key];
                $update_instruction .= "`{$get_params_key}`=:{$get_params_key} ";
                if ($update_prop_count>1)   
                    $update_instruction .= ", ";
                else
                    $update_instruction .= " ";
                $update_prop_count--;
            }
            
            $update_instruction .= " WHERE id={$update_id}";
            return "Выполнено ".$this->dbconnector->exec_with_prepare_and_params($update_instruction, $instruction_params);
        }
        else
            return "Не задан ключ изменяемого объекта!";
    }
    
    function getSelectArray()   {
        $select_rows=$this->dbconnector->query_both_to_array(
            "SELECT id, ".$this->object_adapter->select_display_field.
                " as select_name FROM ".$this->with_relative_view_name.
                " WHERE ".$this->base_filter." AND (closed<>1)".
                    ($this->custom_sel_array_order_clause==null?"":
                    $this->custom_sel_array_order_clause).";");
                //AND ".$this->getFilterWhereClause().";");
        //print_r($select_rows);
        return $select_rows;
    }
    
    function generateDetailInfoTable()  {
        $result = "<div class=\"dict_detail_default\" style=\"\">
            <table id=\""
            .$this->class_name.$GLOBALS['dict_table_base']
            ."\" class=\"tablesorter\" border=\"0\">";

        if ($this->select_collection!=null) {
        //echo "Всего: ".sizeof($this->select_collection);
            $current_row_num = 0;    
            foreach( $this->select_collection as $current_object )  {
                $result .= $this->object_adapter->getDetailInfoRow($current_object);
            }
        }
        $result .= "</table></div>";
        return $result;
    }
    
    function generateDetailInfo($detail_keys)   {
        //print_r($detail_keys);
        if(isset($detail_keys['id'])) {
            if($this->detail_info_template!=null) {
                $template_modified = $this->detail_info_template;
                $linked_detail_keys=array_keys($this->linked_detail_entities);
                //print_r($this->linked_detail_entities);
                $this->base_filter="(id={$detail_keys['id']})";
                if ($this->detail_view_name!=null)
                    $this->with_relative_view_name = $this->detail_view_name;
                $this->selectFullWithRelativeWithoutFilters();
                $template_modified = str_replace("***___SELF", 
                    $this->generateDetailInfoTable(),$template_modified);
                foreach($linked_detail_keys as $linked_detail_key)  {
                    $reflectionClass = new ReflectionClass($linked_detail_key."TableAdapter");
                    $DictTAdapt = $reflectionClass->newInstanceArgs(array($this->dbconnector,
                        "",$linked_detail_key));
                    //echo $linked_detail_key."TableAdapter";
                    $dict_link_array = $this->linked_detail_entities[$linked_detail_key];
                    $dict_linked_keys = array_keys($dict_link_array);
                    foreach($dict_linked_keys as $dict_linked_key)  {
                        if (isset($detail_keys[$dict_linked_key]))  {
                            $DictTAdapt->base_filter = str_replace("***___".$dict_linked_key,
                             $detail_keys[$dict_linked_key], $dict_link_array[$dict_linked_key]);
                            if ($DictTAdapt->detail_view_name!=null)
                                $DictTAdapt->with_relative_view_name = $DictTAdapt->detail_view_name;
                            $DictTAdapt->selectFullWithRelativeWithoutFilters();
                            
                            $template_modified = str_replace("***___".$linked_detail_key, 
                                    $DictTAdapt->generateDetailInfoTable(),$template_modified);
                        } else echo "Нет ключа связанной сущности для детализированного отображения!";
                    }
                    //return $template_modified;
                }
                return "<input type=\"button\" value=\"Закрыть\" 
            onclick=\"closePopup(); return false;\" />".$template_modified;
            }   else
                return "Пустой шаблон вывода детализированной информации!";
        }   else
            return "Не указан ключ для вывода детализированной информации!";
    }
    
    function generateRelatedDetailInfo($detail_keys)   {
        if(isset($detail_keys['id'])) {
            
        }   else
            return "Не указан ключ для вывода детализированной информации!";
    }
    
}

?>