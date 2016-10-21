<?php

/**
 * @author 
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class PersonTypeObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array();
        $this->select_display_field = "person_type_name";
        $this->fields_prev_text_array = array("person_type_name"=>"Наименование должности");
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden");
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr class=\"{$this->header_tr_css_style}\">";
        parent::write_header_td("ID",50);
        parent::write_header_td("Наименование должности",200);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr class=\"{$this->tr_css_style}\">";
        parent::write_td($object->getId(),50);
        parent::write_td($object->person_type_name,200);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
     
}

?>