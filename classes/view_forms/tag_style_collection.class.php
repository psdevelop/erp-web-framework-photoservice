<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

class TagStyleCollection 
{
    public $styles_iterators;
    
    function __construct($styles_array) {
        $this->styles_iterators = new ArrayIterator($styles_array);
    }
    
    function getStylesByTagAndObjectProps($tag_name, $obj_props)    {
        $styles_code = "";
        foreach($this->styles_iterators as $style_iterator)    {
            $styles_code .= $style_iterator->getStyle($tag_name, $obj_props);
        }
        return $styles_code;
    }
    
}

?>
