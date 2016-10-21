<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/tools.class.php");
require_once("classes/configuration.php");
require_once("classes/object_adapters/object_manip.interface.php");

abstract class ObjectAdapter extends Tools implements ObjectManipInterface {
    
    protected $table_name;
    protected $class_name;
    protected $add_js_function;
    protected $edit_js_function;
    protected $delete_js_function;
    protected $td_css_style="dictionary_table_text";
    protected $tr_css_style="dictionary_table_text";
    protected $header_td_css_style="dictionary_table_text";
    protected $header_tr_css_style="dictionary_header_tr";
    protected $header_text_css_style="dictionary_table_header_text";
    public $foreigen_keys=array();
    public $foreigen_keys_filters = array();
    public $select_display_field;
    public $filter_values_keys=array();
    protected $fields_width_array = array();
    protected $fields_prev_text_array = array();
    protected $manip_form_template=null;
    protected $filter_form_template=null;
    public $hidden_keys = array();
    protected $date_fields = array();
    protected $time_fields = array();
    protected $date_time_fields = array();
    protected $text_area_fields = array();
    protected $inline_input_fields = array();
    protected $mixed_with_select_choise_inputs = array();
    protected $object_tag_styles = null;
    protected $slider_fields = null;
    protected $checkbox_fields = array();
    protected $detail_info_template = null;
    protected $disabled_inputs = array();
    protected $with_button_clear_fields = array();
    protected $list_item_template = null;
    public $ids_array_fields = array();
    protected $fast_search_fields = array();
    protected $fields_access_rules = array();
    //protected 

    function __construct($table_name, $class_name, $td_css_style, $text_css_class_name)    {
        parent::__construct($text_css_class_name);
        $this->table_name = $table_name;
        $this->class_name = $class_name;
        //$this->table_name = $add_js_function;
        //$this->table_name = $edit_js_function;
        //$this->table_name = $delete_js_function;
        $this->td_css_style = $td_css_style;    
    }
    
    function setFastSearchFields($fast_search_fields)   {
        $this->fast_search_fields = $fast_search_fields;
    }
    
    function getTBODYTRATTR($object, $current_row_num) {
        $row_style=" class=\"white_tr\" ";
        if(($current_row_num%2)==0) {
            //$row_style=" style=\"background-color:#7FC9FF;\" ";
            $row_style=" class=\"highlighted_tr\" ";
        }
        return  " {$row_style} onClick=\" setSelectionId(this,'selected_row'); ".
                $this->generateEditFillScriptWithNum($object, $current_row_num)." \" ";
    }
    
    function writeTableRowFull($object, $linked_props, $current_row_num)    {
        echo "<tr".$this->getTBODYTRATTR($object, $current_row_num).">";
        $this->writeTableRow($object, $linked_props);
        echo "<td><div id=\"actions_{$this->class_name}{$current_row_num}\" 
                      style=\"display:none;\"><b>Нет действий для объектов данного типа</b></div></td>";
        echo "</tr>";
     } 
     
    function writeTableRowFullWithoutClosedTag($object, $linked_props, $current_row_num)    {
        echo "<tr".$this->getTBODYTRATTR($object, $current_row_num).">";
        $this->writeTableRow($object, $linked_props);
        //echo "</tr>";
     }
     
    function getCustomizedRow($object, $customized_template)    {
        $result = "<tr><td>";
        if($customized_template!=null)    {
            $prop_array = $object->getFullPropArray();
            $result .= $this->getMeFormFromTemplate($prop_array,$customized_template);
        }   else
                $result .= "Нет шаблона вывода строки детализированной информации!";
        $result .= "</td></tr>";    
        return $result;
    } 
    
    function getDetailInfoRow($object)    {
        $result = "<tr><td>";
        if($this->detail_info_template!=null)    {
            $prop_array = $object->getFullPropArray();
            $result .= $this->getMeFormFromTemplate($prop_array,$this->detail_info_template);
        }   else
                $result .= "Нет шаблона вывода строки детализированной информации!";
        $result .= "</td></tr>";    
        return $result;
    }
    
    function getListItemRow($object)    {
        $result = "<tr><td>";
        if($this->list_item_template!=null)    {
            $prop_array = $object->getFullPropArray();
            $result .= $this->getMeFormFromTemplate($prop_array,$this->list_item_template);
        }   else
                $result .= "Нет шаблона вывода строки списочного представления объекта!";
        $result .= "</td></tr>";    
        return $result;
    }
    
    function write_header_td($text, $width)    {
        echo "<th style=\" background-color: #A0A0A0; width:{$width};\">";
        parent::write_text($text, $this->header_text_css_style);
        echo "</th>";
    }
    
    function write_td($text, $width)    {
        echo "<td style=\"width:{$width};\">";
        parent::write_text($text, ""); //$this->text_css_class_name
        echo "</td>";
    }
    
    function write_td_with_action($text, $width, $prop_name, $id_prm)    {
        echo "<td style=\"width:{$width};\">";
        if (isset($this->slider_fields[$prop_name]))    {
            echo "<span id=\"anchor_{$this->class_name}_{$prop_name}_{$id_prm}\"></span><p class=\"slide\"><div id=\"{$this->class_name}_{$prop_name}_panel_btn{$id_prm}\" class=\"hidden_panel\" 
                                        style=\"height: {$this->slider_fields[$prop_name]}px;\">";
            parent::write_text($text, "");
            echo "</div><a id=\"{$this->class_name}_{$prop_name}_btn{$id_prm}\" href=\"#anchor_{$this->class_name}_{$id_prm}\" 
                                    class=\"btn-slide\" onclick=\" $('#{$this->class_name}_{$prop_name}_panel_btn{$id_prm}').
                                        slideToggle('slow'); $(this).toggleClass('active'); \">
                                        Подробно</a></p>";
        }   
        else
            parent::write_text($text, ""); //$this->text_css_class_name
        echo "</td>";
    }
    
    function write_td_with_styler($text, $width, $styler_criteries)    {
        if ($this->object_tag_styles==null)
            $style_code = "";
        else
            $style_code = $this->object_tag_styles->
                getStylesByTagAndObjectProps("td", $styler_criteries);
        
        echo "<td style=\" {$style_code} width:{$width};\">";
        parent::write_text($text, ""); //$this->text_css_class_name
        echo "</td>";
    }
    
    function generate_add_button()  {
        
    }
    
    function getDataClassInstance() {
        $reflectionClass = new ReflectionClass($this->class_name);
        return $reflectionClass->newInstanceArgs(array(null));
    }
    
    function writeInsertEditForm($object, $select_arrays)  {
        
        $form_elements_array = array();
        
        if ($object==null)  {
            $parse_object = $this->getDataClassInstance();
        }   else    {
            $parse_object = $object;
        }
        $prop_array = $parse_object->getPropArray();
        
        
        $foreign_key_names = array_keys($this->foreigen_keys);
        $select_array_names = array_keys($select_arrays);

        foreach($foreign_key_names as $foreign_key_name)    {
            foreach($select_array_names as $select_array_name)    {
                if ($foreign_key_name==$select_array_name)  {
                    $current_select_array = $select_arrays[$select_array_name];
                    if ($this->manip_form_template==null)
                        echo $this->write_select_field($foreign_key_name, $current_select_array);
                    else    
                        $form_elements_array[$foreign_key_name] = 
                            $this->write_select_field($foreign_key_name, $current_select_array);
                    unset($prop_array[$select_array_name]);
                }
            }
        }
        
        $prop_keys = array_keys($prop_array);
        $prop_values = array_values($prop_array);
        foreach ($prop_keys as $prop_key)  {
            if ($this->manip_form_template==null)
                echo $this->write_input_text_field($prop_key, $prop_array[$prop_key]);
            else
                $form_elements_array[$prop_key] = 
                    $this->write_input_text_field($prop_key, $prop_array[$prop_key]);
        }
        
        $this->getFormFromTemplate($form_elements_array, $this->manip_form_template);
        
     }
     
     function writeInsertEditFormWithNum($object, $select_arrays, $current_row_num)  {
        
        $form_elements_array = array();
        
        if ($object==null)  {
            $parse_object = $this->getDataClassInstance();
        }   else    {
            $parse_object = $object;
        }
        $prop_array = $parse_object->getPropArray();
        
        
        $foreign_key_names = array_keys($this->foreigen_keys);
        $select_array_names = array_keys($select_arrays);

        foreach($foreign_key_names as $foreign_key_name)    {
            foreach($select_array_names as $select_array_name)    {
                if ($foreign_key_name==$select_array_name)  {
                    $current_select_array = $select_arrays[$select_array_name];
                    if ($this->manip_form_template==null)
                        echo $this->write_select_field_with_num($foreign_key_name, $current_select_array, $current_row_num);
                    else    
                        $form_elements_array[$foreign_key_name] = 
                            $this->write_select_field_with_num($foreign_key_name, $current_select_array, $current_row_num);
                    unset($prop_array[$select_array_name]);
                }
            }
        }
        
        $prop_keys = array_keys($prop_array);
        $prop_values = array_values($prop_array);
        foreach ($prop_keys as $prop_key)  {
            if ($this->manip_form_template==null)
                echo $this->write_input_text_field_with_num($prop_key, $prop_array[$prop_key], $current_row_num);
            else
                $form_elements_array[$prop_key] = 
                    $this->write_input_text_field_with_num($prop_key, $prop_array[$prop_key], $current_row_num);
        }
        
        $this->getFormFromTemplate($form_elements_array, $this->manip_form_template);
        
     }
     
     function writeInsertEditFormWithNumWithValuesAndAutoHidden
        ($object, $select_arrays, $current_row_num, $select_values)  {
        
        $form_elements_array = array();
        
        if ($object==null)  {
            $parse_object = $this->getDataClassInstance();
        }   else    {
            $parse_object = $object;
        }
        $prop_array = $parse_object->getPropArray();
        
        
        $foreign_key_names = array_keys($this->foreigen_keys);
        $select_array_names = array_keys($select_arrays);

        foreach($foreign_key_names as $foreign_key_name)    {
            foreach($select_array_names as $select_array_name)    {
                if ($foreign_key_name==$select_array_name)  {
                    $current_select_array = $select_arrays[$select_array_name];
                    $curr_select_value = null;
                    if(isset($select_values[$foreign_key_name]))
                        $curr_select_value = $select_values[$foreign_key_name];
                    if ($this->manip_form_template==null)
                        echo $this->write_select_field_with_num_and_value($foreign_key_name, $current_select_array, $current_row_num, $curr_select_value);
                    else    
                        $form_elements_array[$foreign_key_name] = 
                            $this->write_select_field_with_num_and_value($foreign_key_name, $current_select_array, $current_row_num, $curr_select_value);
                    unset($prop_array[$select_array_name]);
                }
            }
        }
        
        $prop_keys = array_keys($prop_array);
        $prop_values = array_values($prop_array);
        foreach ($prop_keys as $prop_key)  {
            if ($this->manip_form_template==null)
                echo $this->write_input_text_field_with_num($prop_key, $prop_array[$prop_key], $current_row_num);
            else
                $form_elements_array[$prop_key] = 
                    $this->write_input_text_field_with_num($prop_key, $prop_array[$prop_key], $current_row_num);
        }
        
        $this->getFormFromTemplate($form_elements_array, $this->manip_form_template);
        
     }
     
     function getInsertEditFormWithNumWithValuesAndAutoHidden
        ($object, $select_arrays, $current_row_num, $select_values)  {
        
        $form_elements_array = array();
        $result = "";
        
        //print_r($select_values);
        
        if ($object==null)  {
            $parse_object = $this->getDataClassInstance();
        }   else    {
            $parse_object = $object;
        }
        $prop_array = $parse_object->getPropArray();
        
        
        $foreign_key_names = array_keys($this->foreigen_keys);
        $select_array_names = array_keys($select_arrays);

        foreach($foreign_key_names as $foreign_key_name)    {
            foreach($select_array_names as $select_array_name)    {
                if ($foreign_key_name==$select_array_name)  {
                    $current_select_array = $select_arrays[$select_array_name];
                    $curr_select_value = null;
                    if(isset($select_values[$foreign_key_name]))
                        $curr_select_value = $select_values[$foreign_key_name];
                    if ($this->manip_form_template==null)
                        $result .= $this->write_select_field_with_num_and_value($foreign_key_name, $current_select_array, $current_row_num, $curr_select_value);
                    else    
                        $form_elements_array[$foreign_key_name] = 
                            $this->write_select_field_with_num_and_value($foreign_key_name, $current_select_array, $current_row_num, $curr_select_value);
                    unset($prop_array[$select_array_name]);
                }
            }
        }
        
        $ids_array_key_names = array_keys($this->ids_array_fields);
        $select_array_names = array_keys($select_arrays);

        foreach($ids_array_key_names as $ids_array_key_name)    {
            foreach($select_array_names as $select_array_name)    {
                if ($ids_array_key_name==$select_array_name)  {
                    $current_select_array = $select_arrays[$select_array_name];
                    $curr_select_value = null;
                    if(isset($select_values[$ids_array_key_name]))
                        $curr_select_value = $select_values[$ids_array_key_name];
                    if ($this->manip_form_template==null)
                        $result .= $this->write_ids_select_field_with_num_and_value($ids_array_key_name, $current_select_array, $current_row_num, $curr_select_value);
                    else    
                        $form_elements_array[$ids_array_key_name] = 
                            $this->write_ids_select_field_with_num_and_value($ids_array_key_name, $current_select_array, $current_row_num, $curr_select_value);
                    unset($prop_array[$select_array_name]);
                }
            }
        }
        
        $prop_keys = array_keys($prop_array);
        $prop_values = array_values($prop_array);
        foreach ($prop_keys as $prop_key)  {
            if ($this->manip_form_template==null)
                $result .= $this->write_input_text_field_with_num($prop_key, $prop_array[$prop_key], $current_row_num);
            else
                $form_elements_array[$prop_key] = 
                    $this->write_input_text_field_with_num($prop_key, $prop_array[$prop_key], $current_row_num);
        }
        
        if ($this->manip_form_template==null)
            return $result;
        else
            return $this->getMeFormFromTemplate($form_elements_array, $this->manip_form_template);
        
     }
     
     function getFormWithNumWithValuesWithFreeParamsAndTemplate
        ($object, $params, $param_values, $current_row_num, $select_arrays, $select_values,
             $custom_template, $events_js)  {
        $form_elements_array = array();
        $result = "";
        
        //print_r($select_values);
        
        if ($object==null)  {
            $parse_object = $this->getDataClassInstance();
        }   else    {
            $parse_object = $object;
        }
        $prop_array = $parse_object->getPropArray();
        
        $params_keys = array_keys($params);
        foreach ($params_keys as $param_key)  {
            if ($custom_template==null)
                $result .= $this->write_input_text_field_with_num_and_events($params[$param_key], 
                    (isset($param_values[$param_key])?$param_values[$param_key]:
                    (isset($prop_array[$param_key])?$prop_array[$param_key]:null)), 
                    $current_row_num, $events_js);
            else
                $form_elements_array[$param_key] = 
                    $this->write_input_text_field_with_num_and_events($params[$param_key], 
                    (isset($param_values[$param_key])?$param_values[$param_key]:
                    (isset($prop_array[$param_key])?$prop_array[$param_key]:null)), 
                    $current_row_num, $events_js);
        }
        
        if ($custom_template==null)
            return $result;
        else
            return $this->getMeFormFromTemplate($form_elements_array, $custom_template);
        
        
     }
     
     function write_input_text_field_with_num_and_events($prop_key, $value, $row_num, $events_js) {
        $inp_width = 50;
        $prev_text = $prop_key;
        if (isset($this->fields_prev_text_array[$prop_key]))
            $prev_text = $this->fields_prev_text_array[$prop_key];
        if (isset($this->fields_width_array[$prop_key]))
            $inp_width = $this->fields_width_array[$prop_key];
            
        $picker_script = "";
        $cont_div = "";
        $clear_button_code = "";
        if (array_key_exists($prop_key, $this->with_button_clear_fields))   {
            $clear_button_code = "<span id=\"anchor".$prop_key.$row_num."\">
            </span><a href=\"#anchor".$prop_key.$row_num."\" onClick=\" document.
            getElementById('".$prop_key.$row_num."').value='';\"><img src=\"images/clear.jpg\"></a>";
        }
        if (isset ($this->date_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    AnyTime.picker( \"".$prop_key.$row_num."\",
                        { format: \"%z-%m-%d\", firstDOW: 1 } );
                </script>";
            $cont_div = "date_cont_div";
        }
        
        if (isset ($this->time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#".$prop_key.$row_num."\").AnyTime_picker(
                        { format: \"%H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $cont_div = "time_cont_div";
        }
        
        if (isset ($this->date_time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#".$prop_key.$row_num."\").AnyTime_picker(
                        { format: \"%z-%m-%d %H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $cont_div = "date_time_cont_div";
            //if ($value==null)   {
            //    $value=date('Y-m-d H:i',time());
            //}
        }
        
        $picker_script = $clear_button_code.$picker_script;
        
        if (isset($this->hidden_keys[$prop_key]))   {
            return $this->get_input_text_hidden($prop_key.$row_num, $inp_width, $value, $prev_text);
        }   else    {
            if (isset($this->text_area_fields[$prop_key]))   {
                    return $this->get_input_text_area($prop_key.$row_num, $inp_width, $value, 
                            $prev_text,$this->text_area_fields[$prop_key]).$picker_script;
            }
            else    {
                    if (isset($this->mixed_with_select_choise_inputs[$prop_key]))
                        return $this->get_input_text_mixed_select($prop_key.$row_num, $inp_width, $value, $prev_text, 
                            $this->mixed_with_select_choise_inputs[$prop_key]).$picker_script;
                    else if (isset($this->checkbox_fields[$prop_key]))
                        return $this->get_input_checkbox($prop_key.$row_num, $inp_width, 
                                $value, $prev_text).$picker_script;
                    else    {
                                if (isset($this->disabled_inputs[$prop_key]))
                                    return $this->get_disabled_input_text($prop_key.$row_num, $inp_width, $value, $prev_text).$picker_script;
                                else 
                                    return $this->get_input_text_with_class_and_events($prop_key.$row_num, 
                                        $inp_width, $value, $prev_text,$cont_div, $events_js).$picker_script; 
                        
                            }
            }
        }
       
     }
     
     function writeFilterForm($select_arrays, $filter_values_array)   {
         
         $form_elements_array = array();
         
         $foreign_key_names = array_keys($this->foreigen_keys);
         $select_array_names = array_keys($select_arrays);

         foreach($foreign_key_names as $foreign_key_name)    {
            foreach($select_array_names as $select_array_name)    {
                if ($this->class_name."_filt_".$foreign_key_name==$select_array_name)  {
                    $current_select_array = $select_arrays[$select_array_name];
                    if ($this->filter_form_template==null) {
                        echo $this->write_filter_select_field($select_array_name, 
                                $foreign_key_name, $current_select_array);
                    
                        //print_r($current_select_array);
                    }
                    else    
                        $form_elements_array[$foreign_key_name] = 
                            $this->write_filter_select_field($select_array_name, 
                                    $foreign_key_name, $current_select_array);
                }
            }
         }
         
         $filter_values_names = array_keys($this->filter_values_keys);
         $values_array_names = array_keys($filter_values_array);

         foreach($values_array_names as $values_array_name)    {
             foreach($filter_values_names as $filter_values_name)   {
                 if ($this->class_name."_filt_".$filter_values_name==$values_array_name)  {
                        if ($this->filter_form_template==null)  {
                            if (isset($select_arrays[$values_array_name]))  {
                                echo $this->write_filter_select_field($values_array_name, 
                                    $filter_values_name, $select_arrays[$values_array_name]);
                            }
                            else
                                echo $this->write_input_text_field_filter($filter_values_name, 
                                    $filter_values_array[$values_array_name], $values_array_name);
                            }
                        else    {
                            if (isset($select_arrays[$values_array_name]))  {
                                $form_elements_array[$filter_values_name] = 
                                    $this->write_filter_select_field($values_array_name, 
                                        $filter_values_name, $select_arrays[$values_array_name]);
                            }
                            else
                                $form_elements_array[$filter_values_name] = 
                                    $this->write_input_text_field_filter($filter_values_name, 
                                        $filter_values_array[$values_array_name], $values_array_name);
                        }
                 }
             }
         }
        
         $this->getFormFromTemplate($form_elements_array, $this->filter_form_template);
        
     }
     
     function writeFilterFormWithValues($select_arrays, $filter_values_array, $select_values)   {
         
         $form_elements_array = array();
         
         $foreign_key_names = array_keys($this->foreigen_keys);
         $select_array_names = array_keys($select_arrays);

         foreach($foreign_key_names as $foreign_key_name)    {
            foreach($select_array_names as $select_array_name)    {
                if ($this->class_name."_filt_".$foreign_key_name==$select_array_name)  {
                    $current_select_array = $select_arrays[$select_array_name];
                    $curr_select_value = null;
                    if(isset($select_values[$foreign_key_name]))
                        $curr_select_value = $select_values[$foreign_key_name];
                    if ($this->filter_form_template==null) {
                        echo $this->write_filter_select_field_with_value($select_array_name, 
                                $foreign_key_name, $current_select_array, $curr_select_value);
                    
                        //print_r($current_select_array);
                    }
                    else    
                        $form_elements_array[$foreign_key_name] = 
                            $this->write_filter_select_field_with_value($select_array_name, 
                                    $foreign_key_name, $current_select_array, $curr_select_value);
                }
            }
         }
         
         $filter_values_names = array_keys($this->filter_values_keys);
         $values_array_names = array_keys($filter_values_array);

         foreach($values_array_names as $values_array_name)    {
             foreach($filter_values_names as $filter_values_name)   {
                 if ($this->class_name."_filt_".$filter_values_name==$values_array_name)  {
                        $curr_select_value = null;
                        if(isset($select_values[$filter_values_name]))
                            $curr_select_value = $select_values[$filter_values_name];
                        if ($this->filter_form_template==null)  {
                            if (isset($select_arrays[$values_array_name]))  {
                                echo $this->write_filter_select_field_with_value($values_array_name, 
                                    $filter_values_name, $select_arrays[$values_array_name], 
                                        $curr_select_value);
                            }
                            else
                                echo $this->write_input_text_field_filter($filter_values_name, 
                                    $filter_values_array[$values_array_name], $values_array_name);
                            }
                        else    {
                            if (isset($select_arrays[$values_array_name]))  {
                                $form_elements_array[$filter_values_name] = 
                                    $this->write_filter_select_field_with_value($values_array_name, 
                                        $filter_values_name, $select_arrays[$values_array_name], 
                                        $curr_select_value);
                            }
                            else
                                $form_elements_array[$filter_values_name] = 
                                    $this->write_input_text_field_filter($filter_values_name, 
                                        $filter_values_array[$values_array_name], $values_array_name);
                        }
                 }
             }
         }
        
         $this->getFormFromTemplate($form_elements_array, $this->filter_form_template);
        
     }
     
     function getFormFromTemplate($elements_array, $template)  {
         if (sizeof($elements_array)>0) {
             if ($template!=null)    {
                 $form_elements_keys = array_keys($elements_array);
                 $template_modified = $template;
                 foreach($form_elements_keys as $form_elements_key) {
                     $template_modified = str_replace("***___".$form_elements_key,
                             $elements_array[$form_elements_key], $template_modified);
                 }
                 
                 echo $template_modified;
                 
             }
         }
         
     } 
     
     function getMeFormFromTemplate($elements_array, $template)  {
         if (sizeof($elements_array)>0) {
             if ($template!=null)    {
                 $form_elements_keys = array_keys($elements_array);
                 $template_modified = $template;
                 foreach($form_elements_keys as $form_elements_key) {
                     $template_modified = str_replace("***___".$form_elements_key,
                             $elements_array[$form_elements_key], $template_modified);
                 }
                 
                 return $template_modified;
                 
             }  else
                    return "";
         }  else
             return "";
         
     } 
     
     function generateAddInsertJSParamsWithNum($manip_mode, $current_row_num) {
        $result="";
        $prop_array = $this->getParamArray($manip_mode);
        
        $prop_keys = array_keys($prop_array);
        $prop_values = array_values($prop_array);
        $limit=0;
        foreach ($prop_keys as $prop_key)  {
            if ($limit>0)
               $result = $result.", "; 
            $result = $result.$prop_key.":'".$prop_key.$current_row_num."'";
            
            $limit++;
        }
        return $result;
     }
     
     function generateAddInsertJSParams($manip_mode) {
        $result="";
        $prop_array = $this->getParamArray($manip_mode);
        
        $prop_keys = array_keys($prop_array);
        $prop_values = array_values($prop_array);
        $limit=0;
        foreach ($prop_keys as $prop_key)  {
            if ($limit>0)
               $result = $result.", "; 
            $result = $result.$prop_key.":'".$prop_key."'";
            
            $limit++;
        }
        return $result;
     }
     
     function generateEditFillScript($object)   {
        //$action_fill_js = " fillContainer(); ";
        $result=" showElement('change_button_default'); fillEditForm({ ";
        $prop_array = $object->getPropArray();
        
        $prop_keys = array_keys($prop_array);
        //$prop_values = array_values();
        $limit=0;
        foreach ($prop_keys as $prop_key)  {
            if ($limit>0)
               $result = $result.", "; 
            $result = $result.$prop_key.":'".str_replace("\"","&quot;",str_replace("\n","<br>", addslashes($prop_array[$prop_key])))."'";
            
            $limit++;
        }
        $result = $result." });";
        return $result;
     }
     
     function generateEditFillScriptWithNum($object, $current_row_num)   {
        $set_currently_name_js = "";
        if (isset($object->relative_props['identity_name']))    {
            $set_currently_name_js = " setInnerHtmlByClass('current_object_identity','<b>Объект: </b>".
                str_replace("\"","&quot;",str_replace("\n","<br>", 
                addslashes($object->relative_props['identity_name'])))."');";
        }
        $action_fill_js = " fillContainer( 'actions_{$this->class_name}{$current_row_num}','actions_container_{$this->class_name}'); ";
        $result=" showElement('change_button_default'); {$action_fill_js} fillEditForm({ ";
        $prop_array = $object->getPropArray();
        
        $prop_keys = array_keys($prop_array);
        //$prop_values = array_values();
        $limit=0;
        foreach ($prop_keys as $prop_key)  {
            if ($limit>0)
               $result = $result.", "; 
            $result = $result.$prop_key.":'".str_replace("\"","&quot;",str_replace("\n","<br>", addslashes($prop_array[$prop_key])))."'";
            
            $limit++;
        }
        $result = $result." }); {$set_currently_name_js} ";
        return $result;
     }
     
     function generateDetailFillScript($object)   {
        $detail_params=" { ";
        $prop_array = $object->getFullPropArray();
        
        $prop_keys = array_keys($prop_array);
        //$prop_values = array_values();
        $limit=0;
        foreach ($prop_keys as $prop_key)  {
            if ($limit>0)
               $detail_params .= ", "; 
            $detail_params .= ("{$prop_key}:'".str_replace("\"","&quot;",str_replace("\n","<br>",addslashes($prop_array[$prop_key]))."'"));
            
            $limit++;
        }
        $detail_params .= " }";
        $result_container_id = $GLOBALS['dict_detail_base'];
        return " showPopup(); ajaxGetRequest('".$GLOBALS['out_detail_php']."', '{$this->class_name}', 
         '{$GLOBALS['select_mode']}', {$detail_params}, '0', '{$result_container_id}');";
     }
     
     function getObjectIntAddrParams($object)   {
         $addr_params = "";
         $prop_array = $object->getFullPropArray();
         $props_keys = array_keys($prop_array);
         foreach($props_keys as $props_key)  {
             
             if(strcmp((int)$prop_array[$props_key],$prop_array[$props_key])==0)    {
                 //echo $props_key;
                 $addr_params .= "&{$props_key}={$prop_array[$props_key]}";
             }
         }
         return $addr_params;
     }
     
     function generateBlankDetailHREF($object)   {
        return "<a href=\"out_detail.php?class_name={$this->class_name}".
                $this->getObjectIntAddrParams($object)."\" target=\"_blank\">В отд. окне</a>";
     }
     
     function getParamArray($manip_mode) {
        $parse_object = $this->getDataClassInstance();
        if ($manip_mode==$GLOBALS['partial_update_manip_mode'])
            $prop_array = $parse_object->getFullPropArray();
        else
            $prop_array = $parse_object->getPropArray();
        if ($manip_mode==$GLOBALS['insert_manip_mode']) {
            unset($prop_array['id']);
        }
        if ($manip_mode==$GLOBALS['delete_manip_mode']) {
            $del_prop_array = array();
            $del_prop_array['id'] = $prop_array['id'];
            $prop_array = $del_prop_array;
        }
        return $prop_array;
     }
     
     function prepareParamArray($get_array, $manip_mode) {
        $requre_params=$this->getParamArray($manip_mode);
        $get_param_array = array();
        $get_keys_array = array_keys($get_array);
        $require_keys_array = array_keys($requre_params);
        foreach ($get_keys_array as $get_key)   {
            foreach($require_keys_array as $require_key)    {
                if(($get_key==$require_key))    {
                    if (($manip_mode==$GLOBALS['partial_update_manip_mode'])||
                        ($manip_mode==$GLOBALS['fast_append_manip_mode'])){
                        $get_param_array[$get_key] = $get_array[$get_key];
                    }
                    else
                        $get_param_array[":".$get_key] = $get_array[$get_key];
                }
            }
        }
        return $get_param_array;
     }
     
     function write_input_text_field_filter($prop_key, $value, $filter_key) {
        $inp_width = 50;
        $prev_text = $prop_key;
        if (isset($this->fields_prev_text_array[$prop_key]))
            $prev_text = $this->fields_prev_text_array[$prop_key];
        if (isset($this->filter_values_keys[$prop_key]))
            $inp_width = $this->filter_values_keys[$prop_key];
            
        $picker_script = "";
        $input_class = "";
        if (isset ($this->date_fields[$prop_key]))  {
            //$picker_script = "
            //    <script>
            //        AnyTime.picker( \"{$filter_key}\",
            //            { format: \"%z-%m-%d\", firstDOW: 1 } );
            //    </script>";
            $input_class = "date_cont_div";
        }
        
        if (isset ($this->time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#{$filter_key}\").AnyTime_picker(
                        { format: \"%H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $input_class = "time_cont_div";
        }
        
        if (isset ($this->date_time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#{$filter_key}\").AnyTime_picker(
                        { format: \"%z-%m-%d %H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $input_class = "date_time_cont_div";
        }
        
        if (isset($this->checkbox_fields[$prop_key]))
            return $this->get_input_checkbox($filter_key, $inp_width, $value, $prev_text).$picker_script;
        else
            return $this->get_input_text_with_class($filter_key, $inp_width, $value, $prev_text, $input_class).$picker_script;
       
     }
     
     function write_input_text_field($prop_key, $value) {
        $inp_width = 50;
        $prev_text = $prop_key;
        if (isset($this->fields_prev_text_array[$prop_key]))
            $prev_text = $this->fields_prev_text_array[$prop_key];
        if (isset($this->fields_width_array[$prop_key]))
            $inp_width = $this->fields_width_array[$prop_key];
            
        $picker_script = "";
        $clear_button_code = "";
        if (array_key_exists($prop_key, $this->with_button_clear_fields))   {
            $clear_button_code = "<span id=\"anchor{$prop_key}\">
            </span><a href=\"#anchor{$prop_key}\" onClick=\" document.
            getElementById('{$prop_key}').value='';\"><img src=\"images/clear.jpg\"></a>";
        }
        if (isset ($this->date_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    AnyTime.picker( \"{$prop_key}\",
                        { format: \"%z-%m-%d\", firstDOW: 1 } );
                </script>";
        }
        
        if (isset ($this->time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#{$prop_key}\").AnyTime_picker(
                        { format: \"%H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
        }
        
        if (isset ($this->date_time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#{$prop_key}\").AnyTime_picker(
                        { format: \"%z-%m-%d %H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
        }
        
        $picker_script = $clear_button_code.$picker_script;
        
        if (isset($this->hidden_keys[$prop_key]))   {
            return $this->get_input_text_hidden($prop_key, $inp_width, $value, $prev_text);
        }   else    {
            if (isset($this->text_area_fields[$prop_key]))   {
                    return $this->get_input_text_area($prop_key, $inp_width, $value, 
                            $prev_text,$this->text_area_fields[$prop_key]).$picker_script;
            }
            else    {
                if (isset($this->mixed_with_select_choise_inputs[$prop_key]))
                    return $this->get_input_text_mixed_select($prop_key, $inp_width, $value, $prev_text, 
                            $this->mixed_with_select_choise_inputs[$prop_key]).$picker_script;
                else if (isset($this->checkbox_fields[$prop_key]))
                    return $this->get_input_checkbox($prop_key, $inp_width, $value, $prev_text).$picker_script;
                else
                {
                    if (isset($this->disabled_inputs[$prop_key]))
                        return $this->get_disabled_input_text($prop_key, $inp_width, $value, $prev_text).$picker_script;
                    else            
                        return $this->get_input_text($prop_key, $inp_width, $value, $prev_text).$picker_script;
                }
            }
        }
       
     }
     
     function write_input_text_field_with_num($prop_key, $value, $row_num) {
        $inp_width = 50;
        $prev_text = $prop_key;
        if (isset($this->fields_prev_text_array[$prop_key]))
            $prev_text = $this->fields_prev_text_array[$prop_key];
        if (isset($this->fields_width_array[$prop_key]))
            $inp_width = $this->fields_width_array[$prop_key];
            
        $picker_script = "";
        $cont_div = "";
        $clear_button_code = "";
        $fast_search_form_code = "";
        
        if (array_key_exists($prop_key, $this->with_button_clear_fields))   {
            $clear_button_code = "<span id=\"anchor".$prop_key.$row_num."\">
            </span><a href=\"#anchor".$prop_key.$row_num."\" onClick=\" document.
            getElementById('".$prop_key.$row_num."').value='';\"><img src=\"images/clear.jpg\"></a>";
        }
        
        if (array_key_exists($prop_key, $this->fast_search_fields))   {
            if(isset($this->fast_search_fields[$prop_key]))
                $fast_search_form_code = 
                    $this->fast_search_fields[$prop_key]->
                    generateFastIdSelectListForm("", $prop_key.$row_num, $this->class_name);
        }
        
        if (isset ($this->date_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    AnyTime.picker( \"".$prop_key.$row_num."\",
                        { format: \"%z-%m-%d\", firstDOW: 1 } );
                </script>";
            $cont_div = "date_cont_div";
        }
        
        if (isset ($this->time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#".$prop_key.$row_num."\").AnyTime_picker(
                        { format: \"%H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $cont_div = "time_cont_div";
        }
        
        if (isset ($this->date_time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#".$prop_key.$row_num."\").AnyTime_picker(
                        { format: \"%z-%m-%d %H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $cont_div = "date_time_cont_div";
            //if ($value==null)   {
            //    $value=date('Y-m-d H:i',time());
            //}
        }
        
        $picker_script = $clear_button_code.$fast_search_form_code.$picker_script;
        
        if (isset($this->hidden_keys[$prop_key]))   {
            return $this->get_input_text_hidden($prop_key.$row_num, $inp_width, $value, $prev_text).$picker_script;
        }   else    {
            if (isset($this->text_area_fields[$prop_key]))   {
                    return $this->get_input_text_area($prop_key.$row_num, $inp_width, $value, 
                            $prev_text,$this->text_area_fields[$prop_key]).$picker_script;
            }
            else    {
                    if (isset($this->mixed_with_select_choise_inputs[$prop_key]))
                        return $this->get_input_text_mixed_select($prop_key.$row_num, $inp_width, $value, $prev_text, 
                            $this->mixed_with_select_choise_inputs[$prop_key]).$picker_script;
                    else if (isset($this->checkbox_fields[$prop_key]))
                        return $this->get_input_checkbox($prop_key.$row_num, $inp_width, 
                                $value, $prev_text).$picker_script;
                    else    {
                                if (isset($this->disabled_inputs[$prop_key]))
                                    return $this->get_disabled_input_text($prop_key.$row_num, $inp_width, $value, $prev_text).$picker_script;
                                else 
                                    return $this->get_input_text_with_class($prop_key.$row_num, 
                                        $inp_width, $value, $prev_text,$cont_div).$picker_script; 
                        
                            }
            }
        }
       
     }
     
     function write_input_text_field_with_num_and_placement($prop_key, $value, $row_num, $placement) {
        $inp_width = 50;
        $prev_text = $prop_key;
        if (isset($this->fields_prev_text_array[$prop_key]))
            $prev_text = $this->fields_prev_text_array[$prop_key];
        if (isset($this->fields_width_array[$prop_key]))
            $inp_width = $this->fields_width_array[$prop_key];
            
        $picker_script = "";
        $cont_div = "";
        if (isset ($this->date_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    AnyTime.picker( \"".$prop_key.$row_num."\",
                        { format: \"%z-%m-%d\", firstDOW: 1 } );
                </script>";
            $cont_div = "date_cont_div";
        }
        
        $fast_search_form_code = "";
        
        if (array_key_exists($prop_key, $this->fast_search_fields))   {
            if(isset($this->fast_search_fields[$prop_key]))
                $fast_search_form_code = 
                    $this->fast_search_fields[$prop_key]->
                    generateFastIdSelectListForm("", $prop_key.$row_num, $this->class_name);
        }
        
        if (isset ($this->time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#".$prop_key.$row_num."\").AnyTime_picker(
                        { format: \"%H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $cont_div = "time_cont_div";
        }
        
        if (isset ($this->date_time_fields[$prop_key]))  {
            $picker_script = "
                <script>
                    $(\"#".$prop_key.$row_num."\").AnyTime_picker(
                        { format: \"%z-%m-%d %H:%i\", labelTitle: \"Время\",
                            labelHour: \"Час\", labelMinute: \"Минуты\" } );
                </script>";
            $cont_div = "date_time_cont_div";
            //if ($value==null)   {
            //    $value=date('Y-m-d H:i',time());
            //}
        }
        
        $picker_script = $fast_search_form_code.$picker_script;
        
        if (isset($this->hidden_keys[$prop_key]))   {
            if ($fast_search_form_code!="")
                return $this->get_disabled_input_text($prop_key.$row_num, $inp_width, $value, $prev_text).$picker_script;
            else
            return $this->get_input_text_hidden($prop_key.$row_num, $inp_width, $value, $prev_text).$picker_script;
        }   else    {
            if (isset($this->text_area_fields[$prop_key]))   {
                    return $this->get_input_text_area($prop_key.$row_num, $inp_width, $value, 
                            $prev_text,$this->text_area_fields[$prop_key]).$picker_script;
            }
            else    {
                    if (isset($this->mixed_with_select_choise_inputs[$prop_key]))
                        return $this->get_input_text_mixed_select($prop_key.$row_num, $inp_width, $value, $prev_text, 
                            $this->mixed_with_select_choise_inputs[$prop_key]).$picker_script;
                    else if (isset($this->checkbox_fields[$prop_key]))
                        return $this->get_input_checkbox($prop_key.$row_num, $inp_width, 
                                $value, $prev_text).$picker_script;
                    else    
                        return $this->get_input_text_with_class_and_placement($prop_key.$row_num, 
                            $inp_width, $value, $prev_text,$cont_div,$placement).$picker_script;
            }
        }
       
     }
     
     function write_select_field($foreign_key_name, $select_array)  {
        $inp_width = 50;
        $prev_text = $foreign_key_name;
        if (isset($this->fields_prev_text_array[$foreign_key_name]))
            $prev_text = $this->fields_prev_text_array[$foreign_key_name];
        if (isset($this->fields_width_array[$foreign_key_name]))
            $inp_width = $this->fields_width_array[$foreign_key_name];
        
        if (isset($this->hidden_keys[$foreign_key_name]))   {
            return $this->generate_select_hidden($foreign_key_name, $select_array, "id", "select_name", 
                $inp_width, $prev_text);
        }   else
            return $this->generate_select($foreign_key_name, $select_array, "id", "select_name", 
                $inp_width, $prev_text);
        //print_r($select_array);
     }
     
     function write_select_field_with_num($foreign_key_name, $select_array, $row_num)  {
        $inp_width = 50;
        $prev_text = $foreign_key_name;
        if (isset($this->fields_prev_text_array[$foreign_key_name]))
            $prev_text = $this->fields_prev_text_array[$foreign_key_name];
        if (isset($this->fields_width_array[$foreign_key_name]))
            $inp_width = $this->fields_width_array[$foreign_key_name];
        
        if (isset($this->hidden_keys[$foreign_key_name]))   {
            return $this->generate_select_hidden($foreign_key_name.$row_num, $select_array, "id", "select_name", 
                $inp_width, $prev_text);
        }   else
            return $this->generate_select($foreign_key_name.$row_num, $select_array, "id", "select_name", 
                $inp_width, $prev_text);
        //print_r($select_array);
     }
     
     function write_select_field_with_num_and_value($foreign_key_name, $select_array, $row_num, $value)  {
        $inp_width = 50;
        $prev_text = $foreign_key_name;
        if (isset($this->fields_prev_text_array[$foreign_key_name]))
            $prev_text = $this->fields_prev_text_array[$foreign_key_name];
        if (isset($this->fields_width_array[$foreign_key_name]))
            $inp_width = $this->fields_width_array[$foreign_key_name];
        
        if (isset($this->hidden_keys[$foreign_key_name]))   {
            return $this->get_input_text_hidden($foreign_key_name.$row_num,
                ////generate_select_hidden($foreign_key_name.$row_num, $select_array, "id", "select_name", 
                $inp_width, $value, $prev_text);
        }   else
            return $this->generate_select_with_value($foreign_key_name.$row_num, $select_array, "id", "select_name", 
                $inp_width, $prev_text, $value);
        //print_r($select_array);
     }
     
     function write_ids_select_field_with_num_and_value($foreign_key_name, $select_array, $row_num, $value)  {
        $inp_width = 50;
        $prev_text = $foreign_key_name;
        if (isset($this->fields_prev_text_array[$foreign_key_name]))
            $prev_text = $this->fields_prev_text_array[$foreign_key_name];
        if (isset($this->fields_width_array[$foreign_key_name]))
            $inp_width = $this->fields_width_array[$foreign_key_name];
        
        if (isset($this->hidden_keys[$foreign_key_name]))   {
            return $this->get_input_text_hidden($foreign_key_name.$row_num,
                ////generate_select_hidden($foreign_key_name.$row_num, $select_array, "id", "select_name", 
                $inp_width, $value, $prev_text);
        }   else
        return $this->generate_ids_select_with_value($foreign_key_name.$row_num, $select_array, "id", "select_name", 
           $inp_width, $prev_text, $value);
        //print_r($select_array);
     }
     
     function write_filter_select_field($filter_key_name, $foreign_key_name, $select_array)  {
        $inp_width = 50;
        $prev_text = $foreign_key_name;
        if (isset($this->fields_prev_text_array[$foreign_key_name]))
            $prev_text = $this->fields_prev_text_array[$foreign_key_name];
        if (isset($this->fields_width_array[$foreign_key_name]))
            $inp_width = $this->fields_width_array[$foreign_key_name];
        
        return $this->generate_select($filter_key_name, $select_array, "id", "select_name", 
                $inp_width, $prev_text);
        //print_r($select_array);
     }
     
     function write_filter_select_field_with_value($filter_key_name, $foreign_key_name, 
             $select_array, $value)  {
        $inp_width = 50;
        $prev_text = $foreign_key_name;
        
        $filter_access=true;
        if (isset($this->fields_access_rules['filters_rules']))    {
            $filter_access_rules=$this->fields_access_rules['filters_rules'];
            if (isset($filter_access_rules[$foreign_key_name])) {
                $filter_access=$filter_access_rules[$foreign_key_name];
            }
        }
        
        if (isset($this->fields_prev_text_array[$foreign_key_name]))
            $prev_text = $this->fields_prev_text_array[$foreign_key_name];
        if (isset($this->fields_width_array[$foreign_key_name]))
            $inp_width = $this->fields_width_array[$foreign_key_name];
        
        return $this->generate_select_with_value($filter_key_name, $select_array, "id", "select_name", 
                $inp_width, $prev_text, $value, $filter_access);
        //print_r($select_array);
     }
    
}

?>