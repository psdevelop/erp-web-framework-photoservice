<?php

/* 25.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class UserObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("person_id"=>"Person");
        $this->fields_prev_text_array = array("id"=>"ID","username"=>"Логин", "person_id"=>"Пользователь",
            "isactive"=>"Активность", "enable_admin"=>"Разр. администр.", "enable_deleting"=>"Разр. удаление");
        $this->fields_width_array = array("id"=>10,"username"=>25, "person_id"=>35,
            "isactive"=>10, "enable_admin"=>10, "enable_deleting"=>10);
        $this->select_display_field = "username";
        $this->manip_form_template = "<table><tr><td>***___username</td><td>***___isactive</td><td>***___person_id</td></tr>
            <tr><td></td><td>***___code</td><td>***___id</td></tr>
            </table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden");
        $this->checkbox_fields = array("enable_admin"=>0, "enable_deleting"=>0);
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Логин",75);
        parent::write_header_td("Активный",75);
        parent::write_header_td("Пользователь",150);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        parent::write_td($object->username,75);
        parent::write_td($object->isactive,75);
        parent::write_td($object->relative_props['person_name'],150);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>
