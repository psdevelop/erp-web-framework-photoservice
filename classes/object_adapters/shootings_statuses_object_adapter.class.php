<?php

/* 14.12.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class ShootingsStatusesObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("shooting_status_id"=>"ShootingStatus");//,"order_id"=>"Order");
        $this->fields_prev_text_array = array("id"=>"ID","shooting_status_id"=>"Статус",
            "code"=>"Код","shooting_date"=>"Дата","comment"=>"Комментарий к статусу");
        $this->fields_width_array = array("id"=>1,"shooting_status_id"=>75,
            "shooting_id"=>200,"code"=>1,"shooting_date"=>10, "comment"=>10);
        $this->select_display_field = "id";
        $this->manip_form_template = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>***___shooting_date***___shooting_status_id
            <br/>***___comment</td><td><span style=\"visibility:hidden;\">***___shooting_id</span></td><td>***___id***___code</td></tr>
            </table>";
        $this->date_time_fields = array("shooting_date"=>"2011-22-09 00:00");
        $this->hidden_keys = array("id"=>"hidden","code"=>"hidden","shooting_id"=>"hidden");
        $this->text_area_fields = array("comment"=>2);
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Код",30);
        parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        parent::write_td($object->code,30);
        parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>