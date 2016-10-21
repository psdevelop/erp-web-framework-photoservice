<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

class TagStyle  {
    public $background_color;
    public $width;
    public $height;
    public $background_image;
    public $tag_name;
    public $styler_name;
    
    function __construct($tag_name, $styler_name)
	{
            $this->tag_name = $tag_name;
            $this->styler_name = $styler_name;
            $this->background_color = null;
	}
        
    function getFullStyle() {
        $style = "";
        if($this->background_color!=null)
            $style .= " background-color:{$this->background_color}; ";
        if($this->background_image!=null)
            $style .= " background-image:{$this->background_image}; ";
        if($this->height!=null)
            $style .= " height:{$this->height}px; ";
        if($this->width!=null)
            $style .= " width:{$this->width}px; ";
        return $style; 
    } 
    
    function definedTag($tag_name)  {
        
        //$iarr = array("ddd","eee");
        //$itr = new ArrayIterator($iarr);
        
        if ($this->tag_name == $tag_name)
            return true;
        else
            return false;
    }
    
    function getStyle($tag_name, $criteries_array)  {
        if ($this->definedTag($tag_name))
            return $this->getStyleByCriteries($criteries_array);
        else
            return "";
    }
    
    function getStyleByCriteries($criteries_array)  {
        $criteries_keys = array_keys($criteries_array);
        $actual_critery = false;
        foreach($criteries_keys as $critery_key)  {
            if($critery_key==$this->styler_name)
                $actual_critery = $actual_critery || $criteries_array[$critery_key]; 
        }
        if ($actual_critery)
            return $this->getFullStyle();
        else
            return "";
    }
    
}

?>
