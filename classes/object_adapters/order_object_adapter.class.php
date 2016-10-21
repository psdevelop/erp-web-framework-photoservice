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

class OrderObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array(//"kg_id"=>"KinderGarten", 
            "manager_id"=>"Person",  "stock_id"=>"Stock");
        $this->filter_values_keys = array("start_order_date"=>30, "end_order_date"=>30, "like_str"=>30, 
            "plot_id"=>30, "district_id"=>200, "sector_operator_id"=>20,"sector_manager_id"=>20,
            "kg_id"=>20, "order_status_id"=>20, "order_empty_status"=>20, "order_has_reordering"=>20,
            "end_shoot_date"=>20, "start_shoot_date"=>20, "on_date_orders"=>20, "state_id"=>20,
            "moscow_end_disrict"=>20, "district_id"=>20, "piter_end_disrict"=>20);
        $this->foreigen_keys_filters = array("manager_id"=>"(person_type_id=2)");
        $this->fields_prev_text_array = array("code"=>"Код","kg_id"=>"Детский сад","manager_id"=>"Менеджер",
            "stock_id"=>"Акция","plot_id"=>"Сюжет", "order_date"=>"Дата заказа", "shooting_date"=>"Дата съемки",
            "shooting_time"=>"Время съемки", "planned_child_count"=>"Запланир. кол-во", "order_comment"=>"Комментарий", 
            "start_order_date"=>"С даты", "end_order_date"=>"До даты", "like_str"=>"Вхождение строки",
            "shooting_place"=>"Место съемки","group_count"=>"Групп","little_group_count"=>"Яслей",
            "repeat_call_datetime"=>"Дата переноса/перезвона","our_fault"=>"Перенос нами", "district_id"=>"Округ/район", 
            "their_fault"=>"Перенос клиентом", "order_status_id"=>"Статус заказа",
            "sector_operator_id"=>"По оператору района","sector_manager_id"=>"По менеджеру района", 
            "order_empty_status"=>"Только активные (новые, без статуса)",
            "order_has_reordering"=>"Имел перенос",
            "end_shoot_date"=>"Дата съемки<=: ", "start_shoot_date"=>"Дата съемки >=: ",
            "on_date_orders"=>"Все заказы на дату: ", "state_id"=>"Область: ",
            "moscow_end_disrict"=>"Москва и область", "piter_end_disrict"=>"Петербург и область");
        $this->fields_width_array = array("code"=>10,"kg_id"=>20,"manager_id"=>20,
            "stock_id"=>20,"plot_id"=>20, "order_date"=>20,
            "shooting_date"=>30,"shooting_time"=>20, "planned_child_count"=>20,
            "order_comment"=>30,"shooting_place"=>25,"group_count"=>20,"little_group_count"=>20,
            "repeat_call_datetime"=>10, "our_fault"=>10, "district_id"=>20, "their_fault"=>10,
            "sector_operator_id"=>20,"sector_manager_id"=>20,
            "order_empty_status"=>20, "order_status_id"=>20, "order_has_reordering"=>5, 
            "on_date_orders"=>20, "state_id"=>20, "moscow_end_disrict"=>20, "piter_end_disrict"=>20);
        $this->select_display_field = "order_name";
        $this->manip_form_template = "<table><tr><td colspan=\"3\">***___kg_id</td></tr>
            <tr><td>***___order_date</td><td>***___manager_id</td><td>***___stock_id</td></tr>
            <tr><td>***___shooting_date</td><td>***___shooting_time</td><td>***___planned_child_count</td></tr>
            <tr><td>***___order_comment</td><td>***___shooting_place</td><td>***___id***___code</td></tr>
            <tr><td>***___group_count</td><td>***___little_group_count</td><td></td></tr></table>";
        $this->hidden_keys = array("id"=>"hidden", "code"=>"hidden", "kg_id"=>"hidden");
        $this->fast_search_fields = array("kg_id"=>null);
        $this->date_fields = array("shooting_date"=>"2011-22-09", 
            "start_order_date"=>"","end_order_date"=>"",
            "start_shoot_date"=>"","end_shoot_date"=>"","on_date_orders"=>"");
        $this->time_fields = array("shooting_time"=>"09:00");
        $this->date_time_fields = array("order_date"=>"2011-22-09 00:00",
            "repeat_call_datetime"=>"2011-22-09 00:00");
        $this->with_button_clear_fields = array("order_date"=>"2011-22-09 00:00",
            "repeat_call_datetime"=>"2011-22-09 00:00", "shooting_date"=>"2011-22-09",
            "shooting_time"=>"09:00");
        $this->text_area_fields = array("order_comment"=>3);
        $this->checkbox_fields = array("our_fault"=>0, "their_fault"=>0, 
            "order_empty_status"=>0, "order_has_reordering"=>0, "moscow_end_disrict"=>0, "piter_end_disrict"=>0);
        $this->filter_form_template = "<table><tr><td>***___start_order_date</td><td>***___end_order_date</td><td>***___like_str</td></tr>
            <tr><td>***___start_shoot_date</td><td>***___end_shoot_date</td><td></td></tr>       
            <tr><td>***___manager_id***___moscow_end_disrict***___piter_end_disrict</td><td>***___stock_id</td><td>***___plot_id</td></tr>
            <tr><td colspan=\"3\"><table border=\"0\"><tr><td>***___sector_operator_id</td><td>***___sector_manager_id</td></tr></table></td></tr>
            <tr><td>***___district_id</td><td>***___state_id</td><td>***___kg_id</td></tr>
            <tr><td colspan=\"3\"><table border=\"0\"><tr><td>***___order_status_id</td><td>***___order_empty_status</td><td>***___order_has_reordering</td><td>***___on_date_orders</td></tr></table></td></tr></table>";
        $this->mixed_with_select_choise_inputs = array("shooting_place"=>
                array("Спортивный зал"=>"Спортивный зал",
                    "Музыкальный зал"=>"Музыкальный зал"));
        $time_attention_styler =  new TagStyle("td", "time_attention_styler");
        $time_attention_styler->background_color = "#FF6A00";
        $abort_styler =  new TagStyle("td", "abort_styler");
        $abort_styler->background_color = "#303030";
        $shooting_styler =  new TagStyle("td", "shooting_styler");
        $shooting_styler->background_color = "#4CFF00";
        $noanswer_styler =  new TagStyle("td", "noanswer_styler");
        $noanswer_styler->background_color = "#DAFF7F";
        $this->object_tag_styles = new TagStyleCollection(
                array($time_attention_styler, $abort_styler, $shooting_styler, $noanswer_styler));
        $this->detail_info_template = "<table>
            <tr><td colspan=\"2\"><b>ID</b>***___id <b>Сюжеты:</b> ***___plot_name</td><td><b>Менеджер:</b> ***___manager_name</td></tr>
            <tr><td><b>Дата заказа:</b>***___order_date<br/><b>Посл. статус:</b> ***___order_status_name</td><td colspan=\"2\">
            <b>История статусов:</b> ***___order_statuses_names<br/><b>Съемочная группа:</b> ***___teams_items_names</td></tr>
            <tr><td><b>Дата/время съемки<br/>Кол. детей/групп/яслей</b><br/>***___shooting_date/***___shooting_time<br/>
                ***___planned_child_count/***___group_count/***___little_group_count</td><td colspan=\"2\"><b>Место съемки/Комментарий:</b><br/>***___shooting_place/***___order_comment</td></tr></table>";
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Менеджер/Дата заказа",75);
        //parent::write_header_td("Статус ДС",75);
        parent::write_header_td("Статус, инф. ДС",200);
        //parent::write_header_td("Инф. ДС",200);
        //parent::write_header_td("Менеджер",120);
        parent::write_header_td("Акция/Сюжеты",130);
        //parent::write_header_td("Сюжеты",70);
        parent::write_header_td("Дата/время съемки<br/>Кол. детей/групп/яслей",200);
        //parent::write_header_td("Время съемки",70);
        //parent::write_header_td("",70);
        //parent::write_header_td("Статус",70);
        parent::write_header_td("Статус",120);
        parent::write_header_td("Место съемки/Комментарий",110);
        //parent::write_header_td("Комментарий",70);
        parent::write_header_td("Подробно",70);
        if ($linked_props!=null)    {
            if (sizeof($linked_props)>0)    {
                if (isset($linked_props['detail_headers']))    {
                    $linked_detail_props = $linked_props['detail_headers'];
                    //$linked_detail_props_keys = array_keys($linked_detail_props);
                    foreach ($linked_detail_props as $linked_detail_prop) {
                        parent::write_header_td($linked_detail_prop['detail_header'],70);
                    }
                }
            }
        }
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        $prev_id_label="";
        if ($object->relative_props['order_status_id']==
                $GLOBALS['check_order_status']) {
            $prev_id_label = ">>";
        } else if ($object->relative_props['order_status_id']==
                $GLOBALS['control_check_order_status']) {
            $prev_id_label = "+";
        } else if ($object->relative_props['order_status_id']==
                $GLOBALS['active_order_status']) {
            $prev_id_label = "В";
        }   else
        { }
        $style_criteries_array = array("time_attention_styler"=>
                ((date('Y-m-d H:i',time())>=$object->relative_props['repeat_call_datetime'])&&
                ($object->relative_props['repeat_call_datetime']!=null)&&
                ($object->relative_props['order_status_id']==$GLOBALS['ondate_repeat_order_status_id'])),
                "abort_styler"=>
                ($object->relative_props['order_status_id']==$GLOBALS['abort_order_status_id']),
                "shooting_styler"=>
                ($object->relative_props['order_status_id']==$GLOBALS['active_order_status']),
                "noanswer_styler"=>
                (($object->relative_props['order_status_id']==$GLOBALS['ondate_repeat_order_status_id'])||
                        ($object->relative_props['order_status_id']==$GLOBALS['unknown_repeat_order_status_id']))); 
        
        $fault_comment = "";
        if (($object->relative_props['our_fault']!='0')||
                ($object->relative_props['their_fault']!='0'))  {
            $fault_comment = "<br/><b>Был перенос по причине: </b>";
            if ($object->relative_props['our_fault']!='0')  {
                $fault_comment .= " Перенос нами ";
            }
            if ($object->relative_props['their_fault']!='0')  {
                $fault_comment .= " Перенос клиентом ";
            }
        }
        
        $repeat_call_comment = "";
        if (($object->relative_props['repeat_call_datetime']!=null)&&
                (($object->relative_props['repeat_call_datetime']>strtotime('1971-01-01'))
            ||($object->relative_props['order_status_id']==$GLOBALS['ondate_repeat_order_status_id'])
            ||($object->relative_props['order_status_id']==$GLOBALS['unknown_repeat_order_status_id']))
             ){
            $repeat_call_comment = "<br/><b>Дата переноса/перезвона</b><br/>".
                date('d.m.Y H:i',strtotime($object->relative_props['repeat_call_datetime']));
        }
        
        $shooting_status = "";
        if($object->relative_props['shooting_status_name']!=null)   {
            $shooting_status = "<br/><b>Статус съемки: </b>".
                $object->relative_props['shooting_status_name'];
        }
        
        parent::write_td("<span style=\"font-size:18px;\"><b>".$prev_id_label.
                "</b></span><br/>".$object->getId(),25);
        parent::write_td_with_styler("<b>".$object->relative_props['manager_name'].
                "</b><br/><b>Дата заказа: </b><nobr>".
                date('d.m.Y H:i',strtotime($object->order_date)).
                "</nobr>",75,$style_criteries_array);
        //parent::write_td($object->relative_props['kg_status'],75);
        parent::write_td_with_styler("<b>".$object->relative_props['kg_status'].
                "</b><br/>".$object->relative_props['kg_name'].
                (isset($object->relative_props['kg_comment'])&&(strlen(trim($object->relative_props['kg_comment']))>0)?
                "<br/><b>Комментарий к ДС: </b>".$object->relative_props['kg_comment']:"")."</b><br/><b>Съем. группа:</b> ".
                $object->relative_props['teams_items_names'],200,$style_criteries_array);
        //parent::write_td_with_styler(,120,$style_criteries_array);
        parent::write_td("<b>".$object->relative_props['stock_name']."</b><br/><b>Сюжеты:</b> ".
                $object->relative_props['plot_name'],130);
        //parent::write_td(,70);
        parent::write_td("<b>".date('d.m.Y',strtotime($object->shooting_date)).
                " ".$object->shooting_time."</b><br/>".
                $object->planned_child_count."/".$object->group_count."/".
                $object->little_group_count.$repeat_call_comment,200);
        //parent::write_td(,70);
        //parent::write_td(,70);
        parent::write_td("Последний: <br/><b>".$object->relative_props['order_status_name'].
                "</b>".$fault_comment,120);
        //parent::write_td(,70);
        parent::write_td("<b>Место съемки: </b>".$object->shooting_place.
                "<br/><b>Комментарий: </b>".$object->order_comment.
                $shooting_status,110);
        //parent::write_td(,70);
        parent::write_td($this->get_link_button("Подробно","",$this->generateDetailFillScript($object),"").
                "<br/>".$this->generateBlankDetailHREF($object),70);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        if ($linked_props!=null)    {
            if (sizeof($linked_props)>0)    {
                if (isset($linked_props['detail_rows']))    {
                    $linked_detail_props = $linked_props['detail_rows'];
                    //$linked_detail_props_keys = array_keys($linked_detail_props);
                    foreach ($linked_detail_props as $linked_detail_prop) {
                        parent::write_td($this->get_link_button($linked_detail_prop['name'],"",
                                $linked_detail_prop['jscript'],""),70);
                    }
                }
            }
        }
        //echo "</tr>";
     }
}

?>
