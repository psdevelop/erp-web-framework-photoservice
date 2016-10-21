<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class PersonTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name, $recursive_mode=false, $suffix=null)    { 
        parent::__construct($dbconnector, "person_with_types", 
            $class_name, "person_with_types");
        $this->dict_header = "Персонал";
        $this->add_update_procedure_name = "add_update_person";
        $this->filters_array = array("person_type_id"=>"PersonType");
        $this->filters_values = array("person_type_id"=>null);
        //echo "[[[".$suffix."]]]";
        if (isset($suffix))
            $this->assignNumSuffix($suffix);
        if ($suffix=="operator_mode") {
            $this->filters_values['person_type_id'] = 1;
            //echo "+++++++++++++++++++++++";
        }
        $this->insert_instruction_template = "SET @pid=NULL; call `add_update_person` (:person_type_id,@pid,:first_name,:last_name,:sur_name,
		      :stationare_phones,:mobile_phones,:employment_date, :code, :dismissal_date);"; 
        $this->update_instruction_template = "SET @pid=:id; call `add_update_person` (:person_type_id,@pid,:first_name,:last_name,:sur_name,
		      :stationare_phones,:mobile_phones,:employment_date, :code, :dismissal_date);";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('person', :id, @dcount);";
        $this->custom_sel_array_order_clause = " order by person_name ASC ";
        
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>