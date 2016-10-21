<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");
require_once("classes/view_forms/tag_style.class.php"); 
require_once("classes/view_forms/tag_style_collection.class.php");

class CallObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array(//"kg_id"=>"KinderGarten", 
            "operator_id"=>"Person", 
            //"call_status_id"=>"CallStatus",
            "stock_id"=>"Stock");
        $this->filter_values_keys = array("start_call_date"=>30, "end_call_date"=>30, 
            "like_str"=>30, "calls_statuses_id"=>200, "district_id"=>200, 
            "sector_operator_id"=>20,"sector_manager_id"=>20, "call_status_id"=>20,
            "repeat_call_datetime"=>20, "meeting_status_id"=>20, "ready_to_call_datetime"=>20,
            "middle_childs_count"=>15, "call_kg_status"=>5, "empty_call_stock"=>5);
        $this->foreigen_keys_filters = array("operator_id"=>"(person_type_id=1)");
        $this->fields_prev_text_array = array("code"=>"Код","kg_id"=>"Детский сад","operator_id"=>"Оператор",
            "call_status_id"=>"Текущий статус звонка", "call_date"=>"Дата звонка", "start_call_date"=>"С даты", 
            "end_call_date"=>"До даты", "calls_comment"=>"Комментарий", "like_str"=>"Вхождение строки",
            "calls_statuses_id"=>"Звонок имел статус","stock_id"=>"Акция",
            "repeat_call_datetime"=>"Время перезвона", "district_id"=>"Округ/район",
            "sector_operator_id"=>"По оператору района","sector_manager_id"=>"По менеджеру района",
            "repeat_call_datetime"=>"По дате перезвона",
            "meeting_status_id"=>"Статус встречи",
            "ready_to_call_datetime"=>"Дата готовности сада <=: ",
            "middle_childs_count"=>"Среднее число детей отснятых ранее <=: ",
            "call_kg_status"=>"Только постоянные",
            "empty_call_stock"=>"Без акции");
        $this->fields_width_array = array("code"=>10,"kg_id"=>20,"operator_id"=>20,
            "call_status_id"=>20,"call_date"=>20, "calls_comment"=>50,"stock_id"=>20,
            "repeat_call_datetime"=>10, "district_id"=>20,
            "sector_operator_id"=>20,"sector_manager_id"=>20,"repeat_call_datetime"=>20,
            "empty_call_stock"=>20);
        $this->select_display_field = "call_name";
        $this->manip_form_template = "<table>
            <tr><td colspan=\"3\">***___kg_id***___id***___code</td></tr>
            <tr><td colspan=\"2\">***___operator_id</td><td>***___call_date</td></tr>
            <tr><td colspan=\"2\">***___calls_comment</td><td>***___stock_id</td></tr>
            </table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden", "kg_id"=>"hidden");
        $this->date_time_fields = array("call_date"=>"2011-22-09 00:00",
            "repeat_call_datetime"=>"2011-22-09 00:00",
            "ready_to_call_datetime"=>"2011-22-09 00:00");
        $this->date_fields = array("start_call_date"=>"","end_call_date"=>"");
        $this->with_button_clear_fields = array("call_date"=>"2011-22-09 00:00",
            "repeat_call_datetime"=>"2011-22-09 00:00");
        $this->text_area_fields = array("calls_comment"=>3);
        $this->checkbox_fields = array("call_kg_status"=>"1","empty_call_stock"=>"1");
        $this->filter_form_template = "<table><tr><td>***___start_call_date</td><td>***___end_call_date</td><td>***___like_str</td></tr>
            <tr><td>***___call_status_id</td><td>***___calls_statuses_id</td><td>***___stock_id</td></tr>
            <tr><td>***___meeting_status_id</td><td>***___operator_id</td><td>***___district_id</td></tr>
            <tr><td colspan=\"3\"><table border=\"0\"><tr><td>***___sector_operator_id</td><td>***___sector_manager_id</td><td>***___repeat_call_datetime</td></tr></table></td></tr>
            <tr><td>***___ready_to_call_datetime</td><td>***___middle_childs_count</td><td>***___call_kg_status</td></tr>
            <tr><td>***___empty_call_stock</td><td></td><td></td></tr>
            </table>";
        //$this->fields_access_rules = array(
        //    "filters_rules"=>array("operator_id"=>
        //            (!array_key_exists('operator_id', $_SESSION)||is_null($_SESSION['operator_id']))
        //        )
        //    );
        $time_attention_styler =  new TagStyle("td", "time_attention_styler");
        $time_attention_styler->background_color = "#FF6A00";
        $abort_styler =  new TagStyle("td", "abort_styler");
        $abort_styler->background_color = "#303030";
        $meet_styler =  new TagStyle("td", "meet_styler");
        $meet_styler->background_color = "#7FC9FF";
        $noanswer_styler =  new TagStyle("td", "noanswer_styler");
        $noanswer_styler->background_color = "#F3FFD6";
        $this->object_tag_styles = new TagStyleCollection(
                array($time_attention_styler, $abort_styler, $meet_styler, $noanswer_styler));
        $this->detail_info_template = "<table>
            <tr><td><b>ID</b>***___id</td><td><b>Акция:</b> ***___stock_name</td><td><b>]Оператор:</b> ***___operator_name</td></tr>
            <tr><td><b>Дата звонка:</b>***___call_date<br/><b>Посл. статус:</b> ***___call_status_name</td><td colspan=\"2\"><b>История статусов:</b> ***___all_call_statuses_names</td></tr>
            <tr><td colspan=\"2\"><b>Комментарий:</b>***___calls_comment</td><td></td></tr></table>";
        $this->list_item_template = "<table>
            <tr><td><b>ID</b>***___id <br/><b>Акция:</b><br/> ***___stock_name</td><td><b>Оператор:</b> ***___operator_name</td><td><b>Посл. статус:</b> ***___call_status_name</td></tr>
            <tr><td colspan=\"2\"><b>Дата звонка:</b> ***___call_date<br/><b>Комментарий:</b> ***___calls_comment</td><td><!--***___FASTCall--></td></tr>
            </table>";
        
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Дата звонка",40);
        //parent::write_header_td("Акция",75);
        parent::write_header_td("Детский сад",350);
        //parent::write_header_td("Инф. ДС",200);
        parent::write_header_td("Акция/ Оператор/ Комментарий",100);
        //parent::write_header_td("Статус звонка",70);
        parent::write_header_td("Статус звонка",350);
        //parent::write_header_td("Комментарий",120);
        parent::write_header_td("Подробно",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr".$this->getTBODYTRATTR().">";
        $style_criteries_array = array("time_attention_styler"=>
                ((date('Y-m-d H:i',time())>=$object->relative_props['repeat_call_datetime'])&&($object->relative_props['repeat_call_datetime']!=null)&&
                (($object->relative_props['call_status_id']==$GLOBALS['repeat_call_status_id'])||
                        ($object->relative_props['call_status_id']==$GLOBALS['noanswer_call_status_id']))),
                "abort_styler"=>
                ($object->relative_props['call_status_id']==$GLOBALS['abort_call_status_id']),
                "meet_styler"=>
                ($object->relative_props['call_status_id']==$GLOBALS['meet_call_status_id']),
                "noanswer_styler"=>
                ($object->relative_props['call_status_id']==$GLOBALS['noanswer_call_status_id']));
        //echo time();
        //print_r($style_criteries_array);
        $kg_ready="<br/>Готовность:<b>Нет</b>";
        if($object->relative_props['ready_to_call']=="1")
            $kg_ready="<br/>Готовность:<b><span class=\"salate_back_t\">
                Да</span></b>&nbsp;Дата гот.:<b><span class=\"salate_back_t\">".
                date('d.m.y H:i',strtotime(
                        $object->relative_props['ready_to_call_datetime']))."</span></b>";
        $repeat_call_text="";
        if (($object->relative_props['call_status_id']==$GLOBALS['repeat_call_status_id'])||
           ($object->relative_props['call_status_id']==$GLOBALS['noanswer_call_status_id']))    {
        if($object->relative_props['repeat_call_datetime']!=null)
            $repeat_call_text="<br/>Вр. перезв.:<b>
                <span class=\"red_back_bt\">".
                date('d.m.y H:i',strtotime($object->relative_props['repeat_call_datetime'])).
                "</span></b>";
           }
        $meeting_status_text="";
        $meeting_data="";
        if(($object->relative_props['meeting_datetime']!=null)&&($object->relative_props['call_status_id']==$GLOBALS['meet_call_status_id']))
            $meeting_status_text="<br/>Вр. встречи:<b>
                <span class=\"mblue_back_t\">".
                date('d.m.y H:i',strtotime($object->relative_props['meeting_datetime'])).
                "</span></b>";
        if($object->relative_props['meeting_status_id']!=null)   {
            $meeting_data = 
                    (isset($object->relative_props['mrepeat_datetime'])?"<u>Время переноса </u>".
                    $object->relative_props['mrepeat_datetime']:"").
                    (isset($object->relative_props['last_meet_comment'])?"<u>Комментарии по встречам </u>".
                    $object->relative_props['last_meet_comment']:"");
            if (strlen($meeting_data)>0) 
                $meeting_data = "<br/><b>Данные по встрече: </b>".$meeting_data;
            if($object->relative_props['meeting_status_id']==$GLOBALS['meet_reposition_status']) 
                $repeat_call_text.="<br/>Статус встречи: <b>
                <span class=\"red_back_wt\">Перенос встречи</span></b>";
            if($object->relative_props['meeting_status_id']==$GLOBALS['abort_meet_status']) 
                $repeat_call_text.="<br/>Статус встречи: <b>
                <span class=\"red_back_wt\">Отказ от встречи</span></b>";
            if($object->relative_props['meeting_status_id']==$GLOBALS['back_to_operator_status']) 
                $repeat_call_text.="<br/>Статус встречи: <b>
                <span class=\"red_back_wt\">Возврат встречи оператору</span></b>";
        }
            
        parent::write_td($object->getId(),25);
        parent::write_td_with_styler(
                date('d.m.y H:i',strtotime($object->call_date)),40,
                $style_criteries_array);
        //parent::write_td_with_styler($object->relative_props['stock_name'],75,$style_criteries_array);
        parent::write_td_with_styler("<b>".$object->relative_props['kg_status']."</b><br/>".
                $object->relative_props['kg_name'].$kg_ready.
                (isset($object->relative_props['kg_comment'])&&(strlen(trim($object->relative_props['kg_comment']))>0)?
                "<br/><b>Комментарий к ДС: </b>".$object->relative_props['kg_comment']:""),350,$style_criteries_array);
        //parent::write_td(,200);
        $comment_out="";
        if($object->calls_comment!=null)
            $comment_out="<br/>Коммент.:".$object->calls_comment;
        parent::write_td("Акция: <span class=\"yell_back_t\">".
                $object->relative_props['stock_name'].
                "</span><br/>Оператор: <b>".
                $object->relative_props['operator_name']."</b>".$comment_out.
                $meeting_status_text.$meeting_data.
                $repeat_call_text,100);
        //parent::write_td($object->relative_props['call_status_name'],70);
        parent::write_td("<span class=\"white_back_bt\">".$object->relative_props['call_status_name'].
                "</span><br/>установлен: <b>".
                date('d.m.y H:i',strtotime($object->relative_props['status_datetime'])).
                "</b>",350);
        //parent::write_td(,120);
        parent::write_td($this->get_link_button("Подробно","",$this->generateDetailFillScript($object),"").
                "<br/>".$this->generateBlankDetailHREF($object),70);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>
