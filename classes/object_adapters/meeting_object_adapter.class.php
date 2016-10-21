<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class MeetingObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("operator_id"=>"Person", "manager_id"=>"Person", //"call_id"=>"Call"
            "meeting_result_type_id"=>"MeetingResultType");
        $this->filter_values_keys = array("start_meeting_date"=>30, "end_meeting_date"=>30, "district_id"=>200, 
            "sector_operator_id"=>20,"sector_manager_id"=>20,"meet_empty_status"=>20);
        $this->foreigen_keys_filters = array("operator_id"=>"(person_type_id=1)", "manager_id"=>"(person_type_id=2)");
        $this->fields_prev_text_array = array("code"=>"Код","operator_id"=>"Оператор","manager_id"=>"Менеджер",
            "call_id"=>"Звонок","meeting_date"=>"Дата встречи", "meeting_time"=>"Время встречи", 
            "start_meeting_date"=>"С даты", "end_meeting_date"=>"До даты","meeting_result_type_id"=>"Результат",
            "district_id"=>"Округ/район",
            "sector_operator_id"=>"По оператору района","sector_manager_id"=>"По менеджеру района",
            "repeat_meet_call_datetime"=>"Время перезвона встречи",
            "repeat_meet_datetime"=>"Время переноса встречи",
            "meet_empty_status"=>"Только активные (новые, без статуса)",
            "on_control"=>"На контроле");
        $this->fields_width_array = array("operator_id"=>200,"manager_id"=>200,"call_id"=>20,
            "meeting_date"=>30,"meeting_time"=>20,"meeting_result_type_id"=>20,
            "district_id"=>20,
            "sector_operator_id"=>20,"sector_manager_id"=>20,"repeat_meet_call_datetime"=>15,
            "repeat_meet_datetime"=>15, "meet_empty_status"=>5, "on_control"=>5);
        $this->select_display_field = "meeting_name";
        $this->manip_form_template = "<table><tr><td>***___manager_id</td><td>***___operator_id</td><td>***___code</td></tr>
            <tr><td>***___meeting_date</td><td>***___meeting_time</td><td>***___id</td></tr>
            <tr><td>***___call_id</td></tr></table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden","call_id"=>"hidden");
        $this->date_time_fields = array("repeat_meet_call_datetime"=>"2011-22-09 00:00",
            "repeat_meet_datetime"=>"2011-22-09 00:00");
        $this->date_fields = array("meeting_date"=>"2011-22-09",
            "start_meeting_date"=>"","end_meeting_date"=>"");
        $this->time_fields = array("meeting_time"=>"09:00");
        $this->with_button_clear_fields = array("meeting_date"=>"2011-22-09 00:00",
            "meeting_time"=>"2011-22-09 00:00", "repeat_meet_call_datetime"=>"2011-22-09 00:00",
            "repeat_meet_datetime"=>"2011-22-09 00:00");
        $this->checkbox_fields = array("meet_empty_status"=>"1", "on_control"=>"1");
        $this->filter_form_template = "<table><tr><td>***___start_meeting_date</td><td>***___end_meeting_date</td><td>***___district_id</td></tr>
            <tr><td>***___manager_id</td><td>***___operator_id</td><td>***___meeting_result_type_id</td></tr>
            <tr><td colspan=\"3\"><table border=\"0\"><tr><td>***___sector_operator_id</td><td>***___sector_manager_id</td><td>***___meet_empty_status</td></tr></table></td></tr>
            </table>";
        $this->detail_info_template = "<table>
            <tr><td><b>ID</b>***___id <b>Оператор:</b> ***___operator_name</td><td><b>Менеджер:</b> ***___manager_name</td><td></td></tr>
            <tr><td><b>Дата встречи:</b> ***___meeting_date<b>, время:</b> ***___meeting_time</td><td><b>Звонок:</b> ***___call_name</td><td></td></tr>
            <tr><td><b>Посл. результат:</b> ***___meeting_result_type_name</td><td colspan=\"2\"><b>История:</b> ***___meeting_statuses_names</td></tr></table>";
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        //parent::write_header_td("Оператор",75);
        parent::write_header_td("Менеджер/Оператор",200);
        parent::write_header_td("Звонок",120);
        parent::write_header_td("Дата/Время встречи",70);
        //parent::write_header_td("Посл. результат",70);
        parent::write_header_td("Последний результат, комментарии",120);
        parent::write_header_td("Подробно",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        $repeat_dt_text="";
        if(isset($object->relative_props['on_control'])&&
                ($object->relative_props['on_control']!=0)) {
            $repeat_dt_text .= "<br/><nobr><b><span class=\"red_back_bt\">НА КОНТРОЛЕ</span></b></nobr>";
        }
        if(($object->relative_props['repeat_meet_datetime']!=null)&&
                ($object->relative_props['meeting_result_type_id']==$GLOBALS['meet_reposition_status'])) {
            $repeat_dt_text .= "<br/>Вр. переноса: <nobr><b><span class=\"red_back_bt\">".
                    date('d.m.y H:i',strtotime(
                            $object->relative_props['repeat_meet_datetime']))."</span></b></nobr>";
        }
        if(($object->relative_props['repeat_meet_call_datetime']!=null)&&
                ($object->relative_props['meeting_result_type_id']==$GLOBALS['recall_meet_status'])) {
            $repeat_dt_text .= "<br/>Вр. перезвона: <nobr><b><span class=\"red_back_bt\">".
                    date('d.m.y H:i',strtotime(
                            $object->relative_props['repeat_meet_call_datetime']))."</span></b></nobr>";
        }
        parent::write_td($object->getId(),25);
        //parent::write_td($object->relative_props['operator_name'],75);
        parent::write_td($object->relative_props['manager_name']."/".
                $object->relative_props['operator_name'],200);
        parent::write_td($object->relative_props['call_name'].
                (isset($object->relative_props['kg_comment'])&&(strlen(trim($object->relative_props['kg_comment']))>0)?
                "<br/><b>Комментарий к ДС: </b>".$object->relative_props['kg_comment']:""),120);
        parent::write_td("<nobr><span class=\"yell_back_t\">".date('d.m.y',strtotime($object->meeting_date)).
                " ".$object->meeting_time."</span></nobr>".$repeat_dt_text,120);
        //parent::write_td($object->relative_props['meeting_result_type_name'],70);
        parent::write_td("<span class=\"white_back_bt\">".$object->relative_props['meeting_result_type_name']."</span><br/>".
                $object->relative_props['meeting_statuses_names'],120);
        parent::write_td($this->get_link_button("Подробно","",$this->generateDetailFillScript($object),"").
                "<br/>".$this->generateBlankDetailHREF($object),70);
        //echo "</tr>";
     }
}

?>
