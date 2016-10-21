<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/object_adapters/object_adapter.class.php"); 
require_once("classes/object_adapters/object_manip.interface.php");
require_once("classes/configuration.php");

class PersonObjectAdapter extends ObjectAdapter implements ObjectManipInterface  {
     //protected $foreigen_keys = array("person_type_id"=>"person_types");
     
     function __construct($table_name, $class_name)    {
        parent::__construct($table_name, $class_name, "dictionary_table_text", "dictionary_table_text");
        $this->foreigen_keys = array("person_type_id"=>"PersonType");
        $this->fields_prev_text_array = array("code"=>"Код","first_name"=>"Имя","last_name"=>"Фамилия",
            "sur_name"=>"Отчество","stationare_phones"=>"Гор. телефон", "person_type_id"=>"Должность",
            "dismissal_date"=>"Дата увольнения","mobile_phones"=>"Моб. телефон", "employment_date"=>"Дата приема");
        $this->fields_width_array = array("code"=>20,"first_name"=>30,"last_name"=>30,
            "sur_name"=>30,"stationare_phones"=>40, "person_type_id"=>150,
            "dismissal_date"=>20,"mobile_phones"=>40, "employment_date"=>20);
        $this->select_display_field = "person_name";
        $this->manip_form_template = "<table><tr><td>***___person_type_id</td><td></td><td>***___code</td></tr>
            <tr><td>***___last_name</td><td>***___first_name</td><td>***___sur_name</td></tr>
            <tr><td colspan=\"3\"><table border=\"0\" width=\"100%\"><tr><td>***___mobile_phones</td><td>***___stationare_phones</td></tr></table></td></tr>
            <tr><td>***___employment_date</td><td>***___id</td><td>***___dismissal_date</td></tr></table>";
        $this->hidden_keys = array("id"=>"hidden");
        $this->date_time_fields = array("employment_date"=>"2011-22-09 00:00",
            "dismissal_date"=>"2011-22-09 00:00");
        $this->with_button_clear_fields = array("employment_date"=>"2011-22-09 00:00",
            "dismissal_date"=>"2011-22-09 00:00");
     }
     
     function writeTableHeader($linked_props)    {
        echo "<tr>";
        parent::write_header_td("ID",25);
        parent::write_header_td("Должность",75);
        parent::write_header_td("ФИО",200);
        parent::write_header_td("Гор. телефон",120);
        parent::write_header_td("Моб. телефон",120);
        parent::write_header_td("Дата приема",70);
        parent::write_header_td("Дата увольнения",70);
        //parent::write_header_td("Правка",70);
        echo "</tr>";
     }
     
     function writeTableRow($object, $linked_props)    {
        //echo "<tr>";
        parent::write_td($object->getId(),25);
        parent::write_td($object->relative_props['person_type_name'],75);
        parent::write_td($object->last_name." ".$object->first_name." ".$object->sur_name,200);
        parent::write_td($object->stationare_phones,120);
        parent::write_td($object->mobile_phones,120);
        parent::write_td($object->employment_date,70);
        parent::write_td($object->dismissal_date,70);
        //parent::write_td($this->get_link_button("Править","",$this->generateEditFillScript($object),""),70);
        //echo "</tr>";
     }
     
}

?>