<?php

/**25.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

class AjaxedObject 
{
    public $object_id;
    public $load_js;
    public $on_change_js="";
    public $tag_name;
    public $elm_caption="";
    public $styler;
    public $load_after_write_html=false;
    public $order_num;
    public $parent_order_nums = array();
    public $reseted_order_nums = array();
    public $adapter_class;
    public $content_type;
    public $additional_js_params;
    public $recursive_mode=false;
    
    function __construct($id, $content_type, $order_num, $adapter_class, $js_params, 
            $parent_order_nums, $reseted_order_nums, $elm_caption, $recursive_mode=false, $entity_suffix=null) {
        $this->object_id = $id;
        $this->tag_name = "div";
        $this->content_type = $content_type;
        $this->recursive_mode = $recursive_mode;
        if ($content_type==$GLOBALS['active_cont_select_type']) {
            $this->tag_name = "select";
        } else if ($content_type==$GLOBALS['active_list_div_type']) {
            $this->tag_name = "div";
        } else  {
            
        }
        
        $reflectionClass = new ReflectionClass($adapter_class."TableAdapter");
        if ($this->recursive_mode) {
            if (is_null($entity_suffix))
                $SelectDictTAdapt = $reflectionClass->newInstanceArgs(array(null,
                    "", $adapter_class, true));
            else
                $SelectDictTAdapt = $reflectionClass->newInstanceArgs(array(null,
                    "", $adapter_class, true, $entity_suffix));
                    
        }   else
        {
            if (is_null($entity_suffix))
            $SelectDictTAdapt = $reflectionClass->newInstanceArgs(array(null,
                "", $adapter_class));
            else    {
                $SelectDictTAdapt = $reflectionClass->newInstanceArgs(array(null,
                    "", $adapter_class, false, $entity_suffix));
                //echo "[[[".$adapter_class."---".$entity_suffix."]]]";
            }
        }
        
        $this->load_js = $SelectDictTAdapt->getContentJSByType($content_type, $js_params, $id);
        $this->order_num = $order_num;
        $this->elm_caption = $elm_caption;
        $this->adapter_class = $adapter_class;
        
        $this->parent_order_nums = $parent_order_nums;
        $this->reseted_order_nums = $reseted_order_nums;
    }
    
    function getObjectResetJS() {
            return " document.getElementById('{$object_id}').innerHTML=''; ";
    }
    
    function getAfterJSHTML()   {
        if ($this->load_after_write_html) 
            return "<script language=\"JavaScript\" type=\"text/javascript\"> ".
                $this->load_js." </script>";
        else 
            return "";
    }
    
    function getObjectHTMLCustomJS($change_js)    {
        if ($this->content_type==$GLOBALS['active_cont_select_type']) {
            return "<table border=\"0\"><tr><td>{$this->elm_caption}</td></tr><tr><td><{$this->tag_name} id=\"{$this->object_id}\" name=\"$this->object_id\" 
                onchange=\" {$change_js} \"> </{$this->tag_name}></td></tr></table>".$this->getAfterJSHTML();
        } else if ($this->content_type==$GLOBALS['active_list_div_type']) {
            return "<table border=\"0\"><tr><td>{$this->elm_caption}</td></tr><tr><td><{$this->tag_name} id=\"{$this->object_id}\" name=\"$this->object_id\" 
                onchange=\" {$change_js} \"> </{$this->tag_name}></td></tr></table>".$this->getAfterJSHTML();
        }
    }
    
}

?>
