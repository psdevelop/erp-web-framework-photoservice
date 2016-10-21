<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class ShootingObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("manager_id"=>"Person", "stock_id"=>"Stock");
        $this->filter_values_keys = array("start_shooting_date"=>30, "end_shooting_date"=>30, 
            "district_id"=>200, "state_id"=>20);
        $this->foreigen_keys_filters = array("manager_id"=>"(person_type_id=2)");
        $this->fields_prev_text_array = array("code"=>"Код","manager_id"=>"Менеджер","order_id"=>"Заказ",
            "stock_id"=>"Акция","shooting_date"=>"Дата съемки", "shooting_time"=>"Время съемки", 
            "child_count"=>"Количество детей", 
            "start_shooting_date"=>"С даты", "end_shooting_date"=>"До даты", 
            "kg_id"=>"Детский сад", "district_id"=>"Округ/район","real_count"=>"Фактич. кол-во",
            "back_count"=>"Возврат", "handling_count"=>"Кол. в обработке", "print_count"=>"Кол. на печать", 
            "to_client_count"=>"Кол. клиенту", "full_kompl_count"=>"Кол. полн. комплектов",
            "big_photos_count"=>"Кол. больших фото", "small_photos_count"=>"Кол. маленьких фото", 
            "state_id"=>"Область: ");
        $this->fields_width_array = array("manager_id"=>200,"order_id"=>20,"stock_id"=>200,
            "shooting_date"=>30,"shooting_time"=>20, "child_count"=>20, 
            "kg_id"=>250, "district_id"=>20,"real_count"=>10,
            "back_count"=>10, "handling_count"=>10, "print_count"=>10, 
            "to_client_count"=>10, "full_kompl_count"=>10,
            "big_photos_count"=>10, "small_photos_count"=>10, "state_id"=>20);
        $this->select_display_field = "code";
        $this->manip_form_template = "<table><tr><td>***___manager_id</td><td>***___order_id</td><td>***___stock_id</td></tr>
            <tr><td>***___shooting_date</td><td>***___shooting_time</td><td>***___child_count</td></tr>
            <tr><td>***___code</td><td>***___id</td><td></td></tr></table>";
        $this->hidden_keys = array("id"=>"hidden","code"=>"hidden","order_id"=>"hidden");
        $this->date_fields = array("shooting_date"=>"2011-22-09",
            "start_shooting_date"=>"","end_shooting_date"=>"");
        $this->time_fields = array("shooting_time"=>"09:00");
        $this->with_button_clear_fields = array("shooting_date"=>"2011-22-09",
            "shooting_time"=>"09:00");
        $this->filter_form_template = "<table><tr><td>***___start_shooting_date</td><td>***___end_shooting_date</td><td>***___stock_id</td></tr>
            <tr><td>***___manager_id</td><td>***___district_id</td><td>***___state_id</td></tr></table>";
        $this->detail_info_template = "<table>
            <tr><td><b>ID</b>***___id</td><td><b>Акция:</b> ***___stock_name</td><td><b>Менеджер:</b> ***___manager_name</td></tr>
            <tr><td><b>Дата съемки:</b>***___shooting_date<b>, время:</b> ***___shooting_time<b>, детей:</b> ***___child_count</td><td><b>Заказ:</b> ***___order_name</td><td><b>Сюжеты:</b>***___plot_name</td></tr>
            <tr><td><b>Фактич. кол-во:</b>***___real_count<b>, возврат:</b> ***___back_count</td><td></td><td></td></tr></table>";
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        //parent::write_header_td("Менеджер",75);
        parent::write_header_td("Менеджер/Дата-время съемки",130);
        parent::write_header_td("Заказ",200);
        parent::write_header_td("Акция/Статус",120);
        parent::write_header_td("Комплекты",70);
        parent::write_header_td("Кол-во детей/Комментарий",120);
        parent::write_header_td("Детский сад",120);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        $handling_delivery = "";
        if ($object->relative_props['teams_items_names']!=null) {
            if ($object->relative_props['teams_items_names']!="")   {
                $handling_delivery = "</b><br/><b>Обработка-доставка:</b> ".
                    $object->relative_props['teams_items_names'];
            }
        }
        $shooting_status = " не установлен";
        if($object->relative_props['shooting_status_name']!=null) {
            $shooting_status = $object->relative_props['shooting_status_name'];
        }
        parent::write_td($object->getId(),25);
        //parent::write_td($object->relative_props['manager_name'],75);
        //date
        parent::write_td("<b>Менеджер:</b><br/>".$object->relative_props['manager_name'].
                "<br/><nobr><b>Дата: </b>".date('d.m.Y',strtotime($object->shooting_date)).
                "</nobr> <br/><nobr><b>Время: </b>".$object->shooting_time."</nobr>",130);
        parent::write_td($object->relative_props['order_name'],200);
        parent::write_td($object->relative_props['stock_name'].
                "<br/><b>Последний статус: </b>".$shooting_status,120);
        
        parent::write_td("
                <b>Полн. компл.: </b>{$object->relative_props['full_kompl_count']}
                <br/><b>Больш. фото: </b>{$object->relative_props['big_photos_count']}
                <br/><b>Мал. фото: </b>{$object->relative_props['small_photos_count']}
                ",70);
        parent::write_td("<b>Детей: </b>".$object->child_count."<br/><b>Факт. кол-во: </b>
                {$object->relative_props['real_count']}
                <br/><b>В обработке: </b>{$object->relative_props['handling_count']}
                <br/><b>На печать: </b>{$object->relative_props['print_count']}
                <br/><b>Клиенту: </b>{$object->relative_props['to_client_count']}
                <br/><b>Возврат: </b>{$object->relative_props['back_count']}
                <br/><b>Комментарий: </b>".
                $object->relative_props['shooting_comment'],120);
        parent::write_td($object->relative_props['kg_name'].$handling_delivery,120);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
}

?>
