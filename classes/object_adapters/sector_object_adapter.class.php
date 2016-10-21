<?php

/* 27.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class SectorObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("operator_id"=>"Person", "manager_id"=>"Person",
            "district_id"=>"District");
        $this->foreigen_keys_filters = array("operator_id"=>"(person_type_id=1)", "manager_id"=>"(person_type_id=2)");
        $this->fields_prev_text_array = array("code"=>"Код","operator_id"=>"Оператор","manager_id"=>"Менеджер",
            "sector_name"=>"Наименование сектора", "district_id"=>"Округ");
        $this->fields_width_array = array("operator_id"=>200,"manager_id"=>200,"sector_name"=>20,
            "district_id"=>20);
        $this->select_display_field = "sector_full_name";
        $this->manip_form_template = "<table><tr><td>***___manager_id</td><td>***___operator_id</td><td>***___code</td></tr>
            <tr><td colspan=\"2\">***___district_id</td><td>***___sector_name***___id</td></tr>
            </table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden");
        $this->filter_form_template = "<table><tr><td colspan=\"2\">***___district_id</td><td></td></tr>
            <tr><td>***___manager_id</td><td>***___operator_id</td><td></td></tr>
            </table>";
        $this->detail_info_template = "<table>
            <tr><td><b>ID</b>***___id <b>Оператор:</b> ***___operator_name</td><td><b>Менеджер:</b> ***___manager_name</td><td></td></tr>
            <tr><td colspan=\"2\"><b>Округ:</b> ***___district_name</td><td><b>Звонок:</b> ***___call_name</td></tr>
            </table>";
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        //parent::write_header_td("Оператор",75);
        parent::write_header_td("Менеджер/Оператор",200);
        parent::write_header_td("Округ",120);
        parent::write_header_td("Наименование",70);
        //parent::write_header_td("Посл. результат",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        //parent::write_td($object->relative_props['operator_name'],75);
        parent::write_td($object->relative_props['manager_name']."/".
                $object->relative_props['operator_name'],200);
        parent::write_td($object->relative_props['district_name'],120);
        parent::write_td($object->sector_name,120);
        //parent::write_td($object->relative_props['meeting_result_type_name'],70);
        //echo "</tr>";
     }
}

?>
