<?php

/* 24/11/2011
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class TeamItemObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("team_type_id"=>"TeamType", "person_type_id"=>"PersonType",
            "person_id"=>"Person");
        $this->fields_prev_text_array = array("id"=>"ID","team_type_id"=>"Тип задачи", 
            "person_type_id"=>"Роль", "person_id"=>"Персона", "action_datetime"=>"Время",
            "team_object_id"=>"ID объекта задачи");
        $this->fields_width_array = array("id"=>10,"team_type_id"=>25, 
            "person_type_id"=>25, "person_id"=>25, "team_object_id"=>10,
            "action_datetime"=>20);
        $this->select_display_field = "team_type_name";
        $this->date_time_fields = array("action_datetime"=>"2011-22-09 00:00");
        $this->manip_form_template = "<table>
            <tr><td>***___team_type_id</td><td>***___person_type_id***___code</td><td>***___id</td></tr>
            <tr><td>***___person_id</td><td>***___action_datetime</td><td>***___team_object_id</td></tr>
            </table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden", "team_object_id"=>"hidden");
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Наименование задачи",75);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        parent::write_td($object->team_type_name,75);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>