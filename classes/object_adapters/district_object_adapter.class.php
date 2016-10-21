<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class DistrictObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("state_id"=>"State");
        $this->fields_prev_text_array = array("id"=>"ID","district_name"=>"Наименование округа","code"=>"Код",
            "state_id"=>"Область");
        $this->fields_width_array = array("id"=>10,"district_name"=>25,"code"=>15,"state_id"=>200);
        $this->select_display_field = "district_st_name";
        $this->manip_form_template = "<table><tr><td>***___district_name</td><td>***___state_id</td><td>***___id***___code</td></tr>
            </table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden");
        $this->filter_form_template = "<table><tr><td colspan=\"2\">***___state_id</td><td></td></tr>
            </table>";
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Наименование округа",275);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        parent::write_td($object->relative_props['district_st_name'],275);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>