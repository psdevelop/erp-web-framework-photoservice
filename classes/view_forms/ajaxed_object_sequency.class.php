<?php

/**25.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

class AjaxedObjectsSequency 
{
    public $ajaxed_objects;
    public $sequence_template=null;
    public $recursive_mode=false;
    
    function __construct($ajaxed_objects, $sequence_template, $recursive_mode=false) {
        $this->ajaxed_objects = $ajaxed_objects;
        $this->sequence_template = $sequence_template;
        $this->recursive_mode = $recursive_mode;
    }
    
    function getOnChangeJSForObjectNum($order_num)  {
        $change_js="";
        
        foreach($this->ajaxed_objects as $ajaxed_object)    {
            if($ajaxed_object->order_num!=$order_num)   {
                if(in_array($order_num, $ajaxed_object->parent_order_nums)) {
                    $change_js .= (" ".$ajaxed_object->load_js." ");
                    continue;
                }
            }
        }
        return $change_js;
    }
    
    function getSequency()    {
        $aobj_code = "";
        //print_r($this->ajaxed_objects);
        $index=1;
        $template_modified = $this->sequence_template;
        foreach($this->ajaxed_objects as $ajaxed_object)    {
            
            $curr_aobj_code = $ajaxed_object->getObjectHTMLCustomJS(
                    $this->getOnChangeJSForObjectNum($ajaxed_object->order_num));
            $aobj_code .= $curr_aobj_code;
            if($template_modified!=null)
                $template_modified = str_replace("***___".$ajaxed_object->adapter_class, 
                                    $curr_aobj_code,$template_modified);
            $index++;
        }
        //echo $aobj_code;
        if($template_modified!=null)
            return $template_modified;
        else
            return $aobj_code;
    }
    
}

?>
