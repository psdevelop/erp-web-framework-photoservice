<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class CallsStatusesObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("call_status_id"=>"CallStatus");//,"order_id"=>"Order");
        $this->fields_prev_text_array = array("id"=>"ID","call_status_id"=>"Статус","code"=>"Код",
            "call_date"=>"Дата","comment"=>"Комментарий к статусу","meet_datetime"=>"Дата встречи");
        $this->fields_width_array = array("id"=>1,"call_status_id"=>75,"call_id"=>200,"code"=>1,"call_date"=>10,
            "comment"=>10,"meet_datetime"=>10);
        $this->select_display_field = "id";
        $this->manip_form_template = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>
            <td>***___call_date<br/>***___meet_datetime<br/>***___call_status_id<br/>***___comment</td>
            <td><span style=\"visibility:hidden;\">***___call_id</span></td><td>***___id***___code</td></tr>
            </table>";
        $this->date_time_fields = array("call_date"=>"2011-22-09 00:00", "meet_datetime"=>"2011-22-09 00:00");
        $this->with_button_clear_fields = array("call_date"=>"2011-22-09 00:00", "meet_datetime"=>"2011-22-09 00:00");
        $this->hidden_keys = array("id"=>"hidden","code"=>"hidden" ,"call_id"=>"hidden" ,"meet_datetime"=>"hidden");
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