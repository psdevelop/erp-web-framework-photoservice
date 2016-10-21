<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class DistrictTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "districts_with_relative", 
            $class_name, "districts_with_relative");
        $this->dict_header = "Округа";
        $this->filters_array = array("state_id"=>"State");
        $this->filters_values = array("state_id"=>null);
        $this->add_update_procedure_name = "add_update_district"; 
        $this->insert_instruction_template = " SET @pid=NULL; call `add_update_district`(:district_name, :state_id, @pid); SET @fictive=:code;"; 
        $this->update_instruction_template = "SET @pid=:id; call `add_update_district`(:district_name, :state_id, @pid); SET @fictive=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('district', :id, @dcount);";
        $this->custom_sel_array_order_clause = " order by district_st_name ASC ";
        
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>