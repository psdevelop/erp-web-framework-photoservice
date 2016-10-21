<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class PlotObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array();
        $this->fields_prev_text_array = array("id"=>"ID","plot_name"=>"Наименование сюжета","code"=>"Код");
        $this->fields_width_array = array("id"=>10,"plot_name"=>25,"code"=>15);
        $this->select_display_field = "plot_name";
        $this->manip_form_template = "<table><tr><td>***___plot_name</td><td>***___code</td><td>***___id</td></tr>
            </table>";
        $this->hidden_keys = array("id"=>"hidden");
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Код",30);
        parent::write_header_td("Наименование сюжета",75);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        parent::write_td($object->code,30);
        parent::write_td($object->plot_name,75);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>
