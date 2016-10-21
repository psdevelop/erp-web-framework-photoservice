<?php

/*
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class KinderGartenObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("sector_id"=>"Sector");
        $this->filter_values_keys = array("district_id"=>200, 
            "sector_operator_id"=>20,"sector_manager_id"=>20);
        $this->fields_prev_text_array = array("code"=>"Номер/Имя","kg_area"=>"Район примечание","kg_adress"=>"Адрес",
            "kg_phones"=>"Телефоны","kg_contact_person"=>"Контактное лицо", "kg_status"=>"Статус",
            "district_id"=>"Округ","email"=>"Эл. почта", "kg_comment"=>"Комментарий",
            "sector_id"=>"Район",
            "sector_operator_id"=>"По оператору района","sector_manager_id"=>"По менеджеру района", 
            "ready_to_call"=>"Готовность ДС", "ready_to_call_datetime"=>"Время готовн.",
            "KinderGarten_fast_id_search_code"=>"Строка-вхождение в номер ДС",
            "KinderGarten_fast_id_search_kg_adress"=>"Строка-вхождение в адрес ДС");
        $this->fields_width_array = array("code"=>10,"kg_area"=>20,"kg_adress"=>30,
            "kg_phones"=>30,"kg_contact_person"=>20, "kg_status"=>20, "district_id"=>200,
            "email"=>20, "kg_comment"=>50, "sector_id"=>20,
            "sector_operator_id"=>20,"sector_manager_id"=>20, 
            "ready_to_call"=>10, "ready_to_call_datetime"=>10,
            "KinderGarten_fast_id_search_code"=>10,
            "KinderGarten_fast_id_search_kg_adress"=>15);
        $this->select_display_field = "CONCAT(code, ' ', kg_area, ' ', kg_adress)";
        $this->manip_form_template = "<table><tr><td colspan=\"2\">***___sector_id</td><td>***___code</td></tr>
            <tr><td>***___kg_adress</td><td>***___kg_phones</td><td>***___kg_contact_person</td></tr>
            <tr><td>***___kg_status***___id</td><td>***___kg_area</td><td></td></tr>
            <tr><td>***___email</td><td colspan=\"2\">***___kg_comment</td></tr></table>";
        $this->hidden_keys = array("id"=>"hidden");
        $this->text_area_fields = array("kg_comment"=>3);
        $this->date_time_fields = array("ready_to_call_datetime"=>"2011-22-09 00:00");
        $this->checkbox_fields = array("ready_to_call"=>0);
        $this->filter_form_template = "<table><tr><td colspan=\"2\">***___sector_id</td><td>***___district_id</td></tr>
            <tr><td colspan=\"3\"><table border=\"0\"><tr><td>***___sector_operator_id</td><td>***___sector_manager_id</td></tr></table></td></tr>
            </table>";
        $this->mixed_with_select_choise_inputs = array("kg_status"=>
                array("Перспективный"=>"Перспективный",
                    "Постоянный"=>"Постоянный"));
        $this->slider_fields = array("stocks_info"=>150);
        $this->detail_info_template = "<table>
            <tr><td><b>ID</b>***___id <b>Номер/Имя:</b> ***___code</td><td><b>Район:</b> ***___sector_full_name<br/><b>Адрес:</b> ***___kg_adress</td><td><b>Конт. телефон:</b> ***___kg_phones<br/><b>Эл. почта:</b> ***___email</td></tr>
            <tr><td><b>Статус:</b> ***___kg_status<b>, Конт. лицо:</b> ***___kg_contact_person</td><td colspan=\"2\"><b>Комментарий:</b> ***___kg_comment</td></tr>
            <tr><td colspan=\"2\"><b>Инф. по акциям:</b> ***___stocks_info</td><td><b></b></td></tr></table>";
        $this->list_item_template = "<table>
            <tr><td><b>ID</b>***___id <br/><b>Номер/Имя:</b><br/> ***___code</td><td><b>Район:</b> ***___sector_full_name<br/><b>Адрес:</b> ***___kg_adress</td></tr>
            <tr><td><b>Статус:</b> ***___kg_status<br/><b>Среднее число детей:</b> ***___kg_childs_middle_count</td><td>***___FASTCall</td></tr>
            </table>";
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Номер/Имя",75);
        parent::write_header_td("Район",200);
        parent::write_header_td("Адрес",120);
        parent::write_header_td("Конт. телефон/Эл. почта",120);
        //parent::write_header_td("",70);
        parent::write_header_td("Конт. лицо/Комментарий",170);
        parent::write_header_td("Статус",70);
        parent::write_header_td("Инф. по акциям",200);
        //parent::write_header_td("Правка",70);
        $linked_props_header = "";
        if ($linked_props!=null)    {
            if (sizeof($linked_props)>0)    {
                if (isset($linked_props['detail_headers']))    {
                    $linked_detail_props = $linked_props['detail_headers'];
                    //$linked_detail_props_keys = array_keys($linked_detail_props);
                    foreach ($linked_detail_props as $linked_detail_prop) {
                        $linked_props_header .= " ".$linked_detail_prop['detail_header'];
                    }
                }
                parent::write_header_td($linked_props_header,70);
            }
        }
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {

        parent::write_td($object->getId(),25);
        parent::write_td($object->code,75);
        parent::write_td($object->relative_props['sector_full_name'],200);
        parent::write_td($object->kg_adress,120);
        parent::write_td($object->kg_phones."<br/>".$object->email,120);
        //parent::write_td(,120);
        parent::write_td($object->kg_contact_person."<br/>".$object->kg_comment,170);
        parent::write_td($object->kg_status,70);
        //parent::write_td($object->relative_props['stocks_info'],200);
        parent::write_td_with_action($object->relative_props['stocks_info'],200,"stocks_info",$object->getId());
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        $linked_props_links = "";
        if ($linked_props!=null)    {
            if (sizeof($linked_props)>0)    {
                if (isset($linked_props['detail_rows']))    {
                    $linked_detail_props = $linked_props['detail_rows'];
                    //$linked_detail_props_keys = array_keys($linked_detail_props);
                    foreach ($linked_detail_props as $linked_detail_prop) {
                        $linked_props_links .= " ".$this->get_link_button("[".$linked_detail_prop['name']."]","",
                                $linked_detail_prop['jscript'],"");
                        
                    }
                }
                parent::write_td($linked_props_links,70);
            }
        }

     }
}

?>
