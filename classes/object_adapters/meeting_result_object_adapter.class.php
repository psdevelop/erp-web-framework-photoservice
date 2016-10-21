<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class MeetingResultObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("manager_id"=>"Person", //"meeting_id"=>"Meeting",
            "plot_id"=>"Plot", "stock_id"=>"Stock", "meeting_result_type_id"=>"MeetingResultType");
        $this->foreigen_keys_filters = array("manager_id"=>"(person_type_id=2)");
        $this->fields_prev_text_array = array("code"=>"Код","call_id"=>"Звонок","manager_id"=>"Менеджер",
            "meeting_id"=>"Встреча","plot_id"=>"Сюжет", "stock_id"=>"Акция", "planned_shooting_date"=>"Планируемая дата", 
            "meeting_result_comment"=>"Комментарий", "meeting_result_type_id"=>"Результат",
            "meeting_date"=>"Дата статуса", "plots_array"=>"Состав сюжетов для заказа",
            "planned_child_count"=>"Запланировано детей", "planned_group_count"=>"Запланировано групп", 
            "planned_small_gr_count"=>"Запланировано яслей", 
            "planned_shooting_place"=>"Запланированное место съемки");
        $this->fields_width_array = array("code"=>10,"call_id"=>200,"manager_id"=>200,
            "meeting_id"=>20,"plot_id"=>200, "stock_id"=>200, "planned_shooting_date"=>20,
            "meeting_result_comment"=>18,"meeting_result_type_id"=>200,"meeting_date"=>10,
            "planned_child_count"=>8, "planned_group_count"=>8, "planned_small_gr_count"=>8, 
            "planned_shooting_place"=>15);
        $this->select_display_field = "code";
        $this->manip_form_template = "<table>
            <table><!--<tr><td colspan=\"2\">___call_id</td><td>___manager_id</td></tr>-->
            <tr><td colspan=\"2\">***___meeting_id***___meeting_date</td><td>***___stock_id</td></tr>
            <tr><td>***___meeting_result_type_id</td><td colspan=\"2\">***___plot_id</td></tr>
            <tr><td colspan=\"2\">***___meeting_result_comment</td><td>***___planned_shooting_date</td></tr>
            <tr><td>***___plots_array</td><td colspan=\"2\">***___planned_shooting_place<br/>
            ***___id***___code</td></tr><tr><td>***___planned_child_count</td>
            <td>***___planned_group_count</td><td>***___planned_small_gr_count</td></tr></table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden", "meeting_id"=>"hidden", "plots_array"=>"hidden",
            "planned_child_count"=>"hidden", "planned_group_count"=>"hidden", "planned_small_gr_count"=>"hidden", 
            "planned_shooting_place"=>"hidden");
        $this->date_time_fields = array("planned_shooting_date"=>"2011-22-09 00:00",
            "meeting_date"=>"2011-22-09 00:00");
        $this->with_button_clear_fields = array("planned_shooting_date"=>"2011-22-09 00:00",
            "meeting_date"=>"2011-22-09 00:00");
        $this->mixed_with_select_choise_inputs = array("planned_shooting_place"=>
                array("Спортивный зал"=>"Спортивный зал",
                    "Музыкальный зал"=>"Музыкальный зал"));
        $this->text_area_fields = array("meeting_result_comment"=>2);
        $this->filter_form_template = "<table>
            <!--<tr><td colspan=\"2\">___call_id</td><td>___meeting_id</td></tr>-->
            <tr><td colspan=\"2\">***___plot_id</td><td>***___stock_id</td></tr>
            <tr><td>***___meeting_result_type_id</td><td colspan=\"2\">***___manager_id</td></tr></table>";
        $this->disabled_inputs = array("meeting_id"=>"meeting_id");
        $this->ids_array_fields = array("plots_array"=>"Plot");
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Дата статуса",50);
        parent::write_header_td("Звонок",230);
        parent::write_header_td("Менеджер",50);
        parent::write_header_td("Встреча",230);
        parent::write_header_td("Сюжет",50);
        parent::write_header_td("Акция",50);
        parent::write_header_td("Результат",50);
        parent::write_header_td("Планируемая дата",40);
        parent::write_header_td("Комментарий",70);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        parent::write_td($object->meeting_date,50);
        parent::write_td($object->relative_props['call_name'],75);
        parent::write_td($object->relative_props['manager_name'],200);
        parent::write_td($object->relative_props['meeting_name'],120);
        parent::write_td($object->relative_props['plot_name'],120);
        parent::write_td($object->relative_props['stock_name'],70);
        parent::write_td($object->relative_props['meeting_result_type_name'],70);
        parent::write_td($object->planned_shooting_date,70);
        parent::write_td($object->meeting_result_comment,70);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>
