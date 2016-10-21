<?php

/**01.12.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

//include_once(dirname(__FILE__)."/data_object.class.php");

class Operation  {
    public $operation_name = null;
    public $operation_caption = "Проведение операции";
    public $class_name = null; 
    public $table_adapter_object = null;
    public $operation_object;
    public $operation_type = null; //$linked_entity_appeninline_params_update_typed_type = "linked_entity_append_type";
    //$inline_params_update_type = "inline_params_update_type";
    //$external_params_update_type = "external_params_update_type";
    //$custom_action_type = "custom_action_type";
    public $request_mode = null;//$GLOBALS['insert_manip_mode'] or $GLOBALS['update_manip_mode']
    //or $GLOBALS['delete_manip_mode'] or $GLOBALS['partial_update_manip_mode'] or
    //$GLOBALS['fast_append_manip_mode'] or!!!!!!!!!!!!!!!!$GLOBALS['custom_action_manip_mode']
    public $inline_props = array();
    public $from_obj_params = array(); //какие поля бъкта в какие переменные для епредачи попадают
    //!!!!!ВНИМАНИЕ НАУЧИТЬся передавать скрипту не ид объекта-тэга? а значение
    public $form_input_params = array();//описавает поля ввода для неформенных типов т/е/ для custom_action_type? 
    //либо перечень полей воода без суффикса для форменных? еслии не указаны то скрывать или запрещать 
    //на изменения поля
    public $default_input_values = array();
    protected $dbconnector;
    public $hidden_keys = array();
    public $hidden_inline_params = array();
    
    function __construct($dbconnector, $operation_name, $class_name, 
            $operation_type, $operation_caption, $from_obj_params,
            $form_input_params, $default_input_values, $inline_props, $table_adapter)    {
        $this->operation_name = $operation_name;
        $this->class_name = $class_name;
        $this->operation_type = $operation_type;
        $this->dbconnector = $dbconnector;
        $this->from_obj_params = $from_obj_params;
        $this->operation_caption = $operation_caption;
        $this->form_input_params = $form_input_params;
        $this->default_input_values = $default_input_values;
        $this->inline_props = $inline_props;
        
        if ($table_adapter!=null)   {
            $this->table_adapter_object = $table_adapter;
        }   else    {
            $reflectionClass = new ReflectionClass($this->class_name."TableAdapter");
            $this->table_adapter_object = $reflectionClass->newInstanceArgs(array($this->dbconnector,
                "", $this->class_name));
            
        }
        
        $this->operation_object = $this->table_adapter_object->
                object_adapter->getDataClassInstance();
        
    }
    
    function showCustomHiddenKeys($custom_showed_form_fields)   {
        $this->table_adapter_object->
                showCustomHiddenKeys($custom_showed_form_fields);
    }

    function acceptCustomHiddenKeys($custom_hidden_form_fields)  {
        $this->table_adapter_object->
                acceptCustomHiddenKeys($custom_hidden_form_fields);
    }
    
    function getOperationHTML($base_object, $current_row_num) {
        $from_obj_params_keys = array_keys($this->from_obj_params);
        foreach($from_obj_params_keys as $from_obj_params_key)  {
            if(array_key_exists($this->from_obj_params[$from_obj_params_key], 
                    (array)$this->operation_object))    {
                $obj_key_name = $this->from_obj_params[$from_obj_params_key];
                $this->operation_object->$obj_key_name = $base_object->$from_obj_params_key;
            }
        }
        $default_input_values_keys = array_keys($this->default_input_values);
        foreach($default_input_values_keys as $default_input_values_key)  {
            if(array_key_exists($default_input_values_key, 
                    (array)$this->operation_object))    {
                $this->operation_object->$default_input_values_key = 
                        $this->default_input_values[$default_input_values_key];
            }
        }
        $operation_html="<table><tr><td>";
        if($this->operation_type == $GLOBALS['linked_entity_append_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $class_prefix.$current_row_num;
            $operation_html .= $this->table_adapter_object->
                object_adapter->getInsertEditFormWithNumWithValuesAndAutoHidden
                    ($this->operation_object, 
                        $this->table_adapter_object->getSelectArraysWithoutParentLists($this->from_obj_params), 
                        $local_prop_suffix, 
                        (array)$this->operation_object);
                //$current_res_div = $this->class_name.$current_row_num;
                $current_res_div = $this->class_name.$current_row_num."_action_res_div";
                $operation_html .=  "<div id=\"{$current_res_div}\"></div>";
        } else if($this->operation_type == $GLOBALS['inline_params_update_type'])  {
            $operation_html .= "<table><tr>";
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $class_prefix.$current_row_num;
            $operation_html .= $this->table_adapter_object->getInlineForm($this->inline_props, 
                    null,
                    $base_object, false, $local_prop_suffix, $current_row_num);
            $operation_html .= "</tr></table>";
        }   else if($this->operation_type == $GLOBALS['external_params_update_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $class_prefix.$current_row_num;
            $operation_html .= $this->table_adapter_object->getExtParamsForm
                    ($this->inline_props, null,
                    $base_object, false, $local_prop_suffix, $current_row_num);
                //$current_res_div = $this->class_name.$current_row_num;
                $local_manip_id = $this->class_name.$current_row_num."_action_res_div";
                $operation_html .= "<div id={$local_manip_id}></div>";
        }
        else    {
            
        }
        
        //$operation_html .= 
        $operation_html .= "</td></tr><tr><td>{$this->operation_caption}</td></tr></table>";
        return $operation_html;
    }
    
    function getAbsOperationHTML($base_object, $current_row_num) {
        $from_obj_params_keys = array_keys($this->from_obj_params);
        foreach($from_obj_params_keys as $from_obj_params_key)  {
            if(array_key_exists($this->from_obj_params[$from_obj_params_key], 
                    (array)$this->operation_object))    {
                $obj_key_name = $this->from_obj_params[$from_obj_params_key];
                $this->operation_object->$obj_key_name = $base_object->$from_obj_params_key;
            }
        }
        $default_input_values_keys = array_keys($this->default_input_values);
        foreach($default_input_values_keys as $default_input_values_key)  {
            if(array_key_exists($default_input_values_key, 
                    (array)$this->operation_object))    {
                $this->operation_object->$default_input_values_key = 
                        $this->default_input_values[$default_input_values_key];
            }
        }
        $operation_html="<table><tr><td>";
        if($this->operation_type == $GLOBALS['linked_entity_append_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $this->operation_name.$class_prefix.$current_row_num;
            $operation_html .= $this->table_adapter_object->
                object_adapter->getInsertEditFormWithNumWithValuesAndAutoHidden
                    ($this->operation_object, 
                        $this->table_adapter_object->getSelectArraysWithoutParentLists($this->from_obj_params), 
                        $local_prop_suffix, 
                        (array)$this->operation_object);
                //$current_res_div = $this->class_name.$current_row_num;
                $current_res_div = $this->operation_name.$this->class_name.$current_row_num."_action_res_div";
                $operation_html .=  "<div id=\"{$current_res_div}\"></div>";
        } else if($this->operation_type == $GLOBALS['inline_params_update_type'])  {
            $operation_html .= "<table><tr>";
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $this->operation_name.$class_prefix.$current_row_num;
            $operation_html .= $this->table_adapter_object->getInlineForm($this->inline_props, 
                    null, $base_object, false, $local_prop_suffix, $current_row_num,
                    $this->hidden_inline_params);
            $operation_html .= "</tr></table>";
        }   else if($this->operation_type == $GLOBALS['external_params_update_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $this->operation_name.$class_prefix.$current_row_num;
            $operation_html .= $this->table_adapter_object->getExtParamsForm
                    ($this->inline_props, null,
                    $base_object, false, $local_prop_suffix, $current_row_num);
                //$current_res_div = $this->class_name.$current_row_num;
                $local_manip_id = $this->operation_name.$this->class_name.$current_row_num."_action_res_div";
                $operation_html .= "<div id={$local_manip_id}></div>";
        }
        else    {
            
        }
        
        //$operation_html .= 
        $operation_html .= "</td></tr><tr><td>{$this->operation_caption}</td></tr></table>";
        return $operation_html;
    }
    
    function getOperationJS($base_object, $current_row_num, $complete_function) {
        if($this->operation_type == $GLOBALS['inline_params_update_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $class_prefix.$current_row_num;
            $result_js = $this->table_adapter_object->getAllInlineJS($this->inline_props, 
                    $local_prop_suffix, false, $base_object, $current_row_num, $complete_function);
            return $result_js;
        } else if($this->operation_type == $GLOBALS['linked_entity_append_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $elm_suffix = $class_prefix.$current_row_num;
            $current_res_div = $this->class_name.$current_row_num."_action_res_div"; 
            return $this->table_adapter_object->
                    generateInsertJSWithFilterWithExternalRefreshJSNoConfirm(0,$current_res_div,
                    $complete_function,$elm_suffix);
        } else if($this->operation_type == $GLOBALS['external_params_update_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $class_prefix.$current_row_num;
            $local_manip_id = $this->class_name.$current_row_num."_action_res_div";
            $result_js = $this->table_adapter_object->getAllExtJS($this->inline_props, 
                    $local_prop_suffix, false, $base_object, $current_row_num, 
                    $complete_function, $local_manip_id);
            return $result_js;
        }
    }
    
    function getAbsOperationJS($base_object, $current_row_num, $complete_function) {
        if($this->operation_type == $GLOBALS['inline_params_update_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $this->operation_name.$class_prefix.$current_row_num;
            $result_js = $this->table_adapter_object->getAllInlineJS($this->inline_props, 
                    $local_prop_suffix, false, $base_object, $current_row_num, $complete_function);
            return $result_js;
        } else if($this->operation_type == $GLOBALS['linked_entity_append_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $elm_suffix = $this->operation_name.$class_prefix.$current_row_num;
            $current_res_div = $this->operation_name.$this->class_name.$current_row_num."_action_res_div"; 
            return $this->table_adapter_object->
                    generateInsertJSWithFilterWithExternalRefreshJSNoConfirm(0,$current_res_div,
                    $complete_function,$elm_suffix);
        } else if($this->operation_type == $GLOBALS['external_params_update_type'])  {
            $class_prefix = $this->table_adapter_object->class_name;
            if ($this->table_adapter_object->short_name!=null) 
                $class_prefix = $this->table_adapter_object->short_name;
            $local_prop_suffix = $this->operation_name.$class_prefix.$current_row_num;
            $local_manip_id = $this->operation_name.$this->class_name.$current_row_num."_action_res_div";
            $result_js = $this->table_adapter_object->getAllExtJS($this->inline_props, 
                    $local_prop_suffix, false, $base_object, $current_row_num, 
                    $complete_function, $local_manip_id);
            return $result_js;
        }
    }
    
    function getAbstractFillJS($base_object, $current_row_num)    {
        $result_js=" fillEditForm( { ";
        $index=0;
        $from_obj_params_keys = array_keys($this->from_obj_params);
        $class_prefix = $this->table_adapter_object->class_name;
        if ($this->table_adapter_object->short_name!=null) 
            $class_prefix = $this->table_adapter_object->short_name;
        $local_prop_suffix = $this->operation_name.$class_prefix.$current_row_num;
        foreach($from_obj_params_keys as $from_obj_params_key)  {
            if(array_key_exists($this->from_obj_params[$from_obj_params_key], 
                    (array)$this->operation_object))    {
                $obj_key_name = $this->from_obj_params[$from_obj_params_key];
                //$this->operation_object->$obj_key_name = $base_object->$from_obj_params_key;
                if($index>0) $result_js .= " , ";
                $result_js .= " {$obj_key_name}{$local_prop_suffix}:'".
                        str_replace("\"","&quot;",str_replace("\n","<br>", addslashes(
                                $base_object->$from_obj_params_key
                                )))."' ";
                $index++;
            }
        }
        $default_input_values_keys = array_keys($this->default_input_values);
        foreach($default_input_values_keys as $default_input_values_key)  {
            if(array_key_exists($default_input_values_key, 
                    (array)$this->operation_object))    {
                //$this->operation_object->$default_input_values_key = 
                //        $this->default_input_values[$default_input_values_key];
                if($index>0) $result_js .= " , ";
                $result_js .= " {$default_input_values_key}{$local_prop_suffix}:'".
                        str_replace("\"","&quot;",str_replace("\n","<br>", addslashes(
                        $this->default_input_values[$default_input_values_key]
                                )))."' ";
                $index++;
            }
        }
        $inline_values_keys = array_keys($this->inline_props);
        foreach($inline_values_keys as $inline_values_key)  {
            if(array_key_exists($inline_values_key, 
                    $this->operation_object->getFullPropArray()))    {
                //$this->operation_object->$default_input_values_key = 
                //        $this->default_input_values[$default_input_values_key];
                if($index>0) $result_js .= " , ";
                $full_oarray = $base_object->getFullPropArray();
                $result_js .= " {$this->inline_props[$inline_values_key]}{$local_prop_suffix}:'".
                        str_replace("\"","&quot;",str_replace("\n","<br>", addslashes(
                        $full_oarray[$inline_values_key]
                                )))."' ";
                $index++;
            }
        }
        if (sizeof($this->inline_props)>0)  {
            
            if ($this->operation_type==$GLOBALS['external_params_update_type']) {
                $tmp_inline_keys = array_keys($this->inline_props);
                $ext_id_assign_count = 0;
                foreach ($tmp_inline_keys as $tmp_inline_key)   {
                    if ($this->inline_props[$tmp_inline_key]=="id") {
                        $ext_id_assign_count++;
                        if($index>0) $result_js .= " , ";
                        $result_js .= " id{$local_prop_suffix}:'".
                            str_replace("\"","&quot;",str_replace("\n","<br>", addslashes(
                            $full_oarray[$tmp_inline_key]
                                    )))."' ";
                        break;
                    }
                }
                if($ext_id_assign_count==0)
                    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!ошибка, сообщите программисту!!!!!!!!!!!!!!!!";
            }
            else    {
                if($index>0) $result_js .= " , ";
                $result_js .= " id{$local_prop_suffix}:'".
                        $full_oarray['id']."' ";
            }
        }
        $result_js .= " } ); ";
        return $result_js;
    }
       
}

?>