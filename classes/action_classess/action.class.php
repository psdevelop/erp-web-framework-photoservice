<?php

/**01.12.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/tools.class.php");
require_once("classes/configuration.php");

class Action extends Tools  {
    public $action_name;
    public $operation_group;
    public $system_id;
    public $button_color="";
    public $table_adapater=null;
    public $show_over=false;
    public $styler;
    
    function __construct($name, $operations, $system_id, $button_color, $table_adapter)    {
        $this->action_name = $name;
        $this->operation_group = $operations;
        $this->system_id = $system_id;
        $this->button_color = $button_color;
        $this->table_adapater = $table_adapter;
    }
    
    function getActionJS($object, $current_row_num)  {
        $action_js=" ";
        $index = 0;
        foreach($this->operation_group as $operation)   {
            //if($index==0)
            //{
            //    $index++;
            //    continue;
            //}
                
            $action_js .= " ".$operation->getOperationJS
                    ($object, $current_row_num, $this->getActionCompleteFunction())." ";
            
        }
        return $action_js;   
        
    }
    
    function getAbsActionJS($object, $current_row_num, $hide_function)  {
        $action_js=" ";
        $index = 0;
        foreach($this->operation_group as $operation)   {
            //if($index==0)
            //{
            //    $index++;
            //    continue;
            //}
                
            $action_js .= " ".$operation->getAbsOperationJS
                    ($object, $current_row_num, $this->getAbsActionCompleteFunction($hide_function))." ";
            
        }
        return $action_js;   
        
    }
        
    function getAbsActionCompleteFunction($hide_function)    {
        return " actionCompleteFunction( act_counter, function (complete_function) { {$hide_function} reloadCurrentDict(); } ); ";
    } 
    
    function getActionCompleteFunction()    {
        return " actionCompleteFunction( act_counter, function (complete_function) { reloadCurrentDict(); } ); ";
    }
    
    function getAbstractActionFormHTML()    {
        return $this->getInnerABSActionFormHTML(
                $this->table_adapater->getObjectAdapterInstance()->getDataClassInstance(), "_abs_");
    }
    
    function getAbstractActionJS($object)    {
        $result_js="";
        foreach($this->operation_group as $operation)   {
            $result_js .= " ".$operation->getAbstractFillJS
                    ($object, $this->system_id."_abs_")." ";
        }
        return $result_js;
    }
    
    function getInnerABSActionFormHTML($object, $current_row_num)  {
        $current_content_div = $this->system_id."_content".$current_row_num;
        //$action_html = "<div style=\"background-color:{$this->button_color}; margin:1px; padding:5px;\">
        //    <a href=\"#anchor_{$current_content_div}\" onclick=\" showDivModal('$current_content_div'); \">{$this->action_name}</a></div>";
        $action_html = "";
        $action_html .= "<span id=\"anchor_{$current_content_div}\"/><div id=\"{$current_content_div}\" class=\"hidden\" style=\" background-color:#317EC6; position: absolute; 
            left:10; top:300; z-index:30;\" ><div class=\"current_object_identity\" 
            style=\"background-color:#FFFFFF; width:300px; margin: 10px; padding:5px; \"></div>
            <table border=\"0\" style=\" border-color: {$this->button_color};\"><tr><td>
            <center>Состав действия {$this->action_name}</center></td>
            </tr><tr><td><table border=\"0\"><tr>";
        $act_count = sizeof($this->operation_group);
        foreach($this->operation_group as $operation)   {
            $action_html .= "<td>".$operation->getAbsOperationHTML
                    ($object, $this->system_id.$current_row_num)."</td>";
        }
        $action_html .= "</tr></table></td></tr><tr><td><center>";
        $hide_function = "closeDivModal('$current_content_div');";
        $action_html .= "<table width=\"100%\" border=\"0\"><tr><td align=\"left\"><a href=\"#\" onclick=\"  actionConfirm(function (action_function) 
            { closeConfirm(); var act_counter=[{$act_count},0];  ".
            $this->getAbsActionJS($object, $this->system_id.$current_row_num, $hide_function)."  } ); \">
            >>>Выполнить действие ".$this->action_name."</a></td><td align=\"right\">
            <a href=\"#anchor_{$current_content_div}\" onclick=\" cancelNonCloseAction(); closeDivModal('$current_content_div'); \">
            Отмена</a></td></tr></table>";
        $action_html .= "</center></td></tr></table></div>";
        return $action_html;
    }
    
    function getAbsActionButton($object)    {
        $current_row_num="_abs_";
        $current_content_div = $this->system_id."_content".$current_row_num;
        $fill_script = $this->getAbstractActionJS($object);
        if ($this->styler!=null)  {
            $action_style = $this->styler->getFullStyle();
            $action_html = "<div style=\" {$action_style} margin:1px; padding:5px;\">
                <a href=\"#anchor_{$current_content_div}\" onclick=\" clearMultiSetsAndKeys(); {$fill_script} showDivModal('$current_content_div'); \">{$this->action_name}</a>
                </div>";
                }
        else
            $action_html = "<div style=\"background-color:{$this->button_color}; margin:1px; padding:5px;\">
                <a href=\"#anchor_{$current_content_div}\" onclick=\" clearMultiSetsAndKeys(); {$fill_script} showDivModal('$current_content_div'); \">{$this->action_name}</a>
                </div>";
        return $action_html;
    }
    
    function getAbsActionButtonWithStyler($object, $styler)    {
        $current_row_num="_abs_";
        $current_content_div = $this->system_id."_content".$current_row_num;
        $fill_script = $this->getAbstractActionJS($object);
        $action_html = "<div style=\"background-color:{$this->button_color}; margin:1px; padding:5px;\">
            <a href=\"#anchor_{$current_content_div}\" onclick=\" {$fill_script} showDivModal('$current_content_div'); \">{$this->action_name}</a></div>";
        return $action_html;
    }
    
    function getActionFormHTML($object, $current_row_num)  {
        $current_content_div = $this->system_id."_content".$current_row_num;
        
        $action_html = "<div style=\"background-color:{$this->button_color}; margin:1px; padding:5px;\">
            <a href=\"#anchor_{$current_content_div}\" onclick=\"showDivModal('$current_content_div'); \">{$this->action_name}</a></div>";
        $action_html .= "<span id=\"anchor_{$current_content_div}\"/><div id=\"{$current_content_div}\" class=\"hidden\" style=\" background-color:#317EC6; position: absolute; 
            left:10; top:300; z-index:30;\" ><table border=\"0\" style=\" border-color: {$this->button_color};\"><tr><td>
            <center>Состав действия {$this->action_name}</center></td>
            </tr><tr><td><table border=\"0\"><tr>";
        $act_count = sizeof($this->operation_group);
        foreach($this->operation_group as $operation)   {
            $action_html .= "<td>".$operation->getOperationHTML
                    ($object, $this->system_id.$current_row_num)."</td>";
        }
        $action_html .= "</tr></table></td></tr><tr><td><center>";
        
        $action_html .= "<table width=\"100%\" border=\"0\"><tr><td align=\"left\"><a href=\"#\" onclick=\" actionConfirm(function (action_function) 
            { closeConfirm(); var act_counter=[{$act_count},0];  ".$this->getActionJS($object, $this->system_id.$current_row_num)."  } ); \">
             >>>Выполнить действие ".$this->action_name."</a></td><td align=\"right\">
             <a href=\"#anchor_{$current_content_div}\" onclick=\" closeDivModal('$current_content_div'); \">
             Отмена</a></td></tr></table>";
        $action_html .= "</center></td></tr></table></div>";
        return $action_html;
    }
}

?>