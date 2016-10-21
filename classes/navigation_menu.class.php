<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/tools.class.php");

class NavigationItem extends Tools {
    protected $items_array;
    protected $text;
    protected $href = "#";
    protected $js_instruction;
    protected $item_id;
    protected $item_class;
    
    function __construct($text, $js_instruction, $item_id, $item_class, $href)  {
        parent::__construct("default_menu_class");
        $this->items_array = array();
        $this->text = $text;
        $this->js_instruction = $js_instruction;
        $this->item_id = $item_id;
        $this->item_class = $item_class;
        $this->href = $href;
    }
    
    function addChildItemJS($text, $js_instruction, $item_id, $item_class) {
        $new_menu_item = new NavigationItem($text, $js_instruction, $item_id, $item_class, "#");
        $new_item_index = sizeof($this->items_array);
        $this->items_array[$new_item_index] = $new_menu_item;
        return $new_menu_item;
    }
    
    function addChildItemJSAndHref($text, $js_instruction, $item_id, $item_class, $href) {
        $new_menu_item = new NavigationItem($text, $js_instruction, $item_id, $item_class, $href);
        $new_item_index = sizeof($this->items_array);
        $this->items_array[$new_item_index] = $new_menu_item;
        return $new_menu_item;
    }
    
    function outSelf()  {
        echo "<li>";
        if ($this->href=="#")
            $this->generate_link_button($this->text, $this->item_class, $this->js_instruction, $this->item_id);
        else
            $this->generate_link_button_with_href($this->text, $this->item_class, $this->js_instruction, $this->item_id, $this->href);
        $this->outChildsItems("","");
        echo "</li>";
    }
    
    function outChildsItems($ul_id, $ul_class)   {
        $childs_items_count = sizeof($this->items_array);
        if($childs_items_count>0)   {
            echo "<ul id=\"{$ul_id}\" class=\"{$ul_class}\">";
            for ($i=0; $i<$childs_items_count; $i++)   {
                $this->items_array[$i]->outSelf();
            }
            echo "</ul>";
        }
    }
}

class NavigationMenu extends NavigationItem {
    protected $menu_class;
    protected $menu_id;
    
    function __construct($menu_id, $menu_class)  {
        parent::__construct("", "", "", "", "");
        $this->menu_id = $menu_id;
        $this->menu_class = $menu_class;
    }
    
    function generateMenu()   {
        echo "<div id=\"{$this->menu_id}\" class=\"{$this->menu_class}\">";
        $this->outChildsItems($this->menu_id, $this->menu_class);
        echo "</div>";
    }
}

?>