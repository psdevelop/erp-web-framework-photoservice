<?php

/**27.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class SectorTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "sectors", 
            $class_name, "sectors_with_relative"); 
        $this->dict_header = "Районы";
        $this->add_update_procedure_name = ""; 
        $this->filters_array = array("operator_id"=>"Person", 
            "manager_id"=>"Person","district_id"=>"District");
        $this->filters_values = array("operator_id"=>null, 
            "manager_id"=>null,"district_id"=>null);
        $this->insert_instruction_template = "insert into `sectors`(`id`,`operator_id`,`manager_id`, 
            `district_id`, `sector_name`) values(null,:operator_id,:manager_id,:district_id,
            :sector_name); SET @code=:code;"; 
        $this->update_instruction_template = "update `sectors` SET `operator_id`=:operator_id,
            `manager_id`=:manager_id, `district_id`=:district_id, `sector_name`=:sector_name 
            where `id`=:id;  SET @code=:code;";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` 
            ('sector', :id, @dcount);";
        $this->custom_sel_array_order_clause = " order by sector_full_name ASC ";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
