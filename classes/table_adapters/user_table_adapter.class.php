<?php

/**25.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/table_adapters/table_adapter.class.php");
require_once("classes/table_adapters/table_adapter.interface.php");

class UserTableAdapter extends TableAdapter  implements TableAdapterInterface  {
    
    function __construct($dbconnector, $table_name, 
        $class_name)    { 
        parent::__construct($dbconnector, "users", 
            $class_name, "users_with_relative"); 
        $this->dict_header = "Учетные записи";
        $this->add_update_procedure_name = ""; 
        $this->inline_input_properties = array("password"=>"password",
            "enable_admin"=>"enable_admin", "enable_deleting"=>"enable_deleting");
        $this->base64_encode_inline_props = array("password"=>"password");
        $this->insert_instruction_template = "SET @fictive=:code; SET @userid=NULL; call `add_update_user`(:username, :isactive, :person_id, @userid);"; 
        $this->update_instruction_template = "SET @fictive=:code; SET @userid=:id; call `add_update_user`(:username, :isactive, :person_id, @userid);";
        $this->delete_instruction_template = "SET @dcount=0; call `delete_object_by_type` ('user', :id, @dcount);";
    }
    
    function writeTable()   {
        $this->generateTable();
    }
    
    function writeInsertForm()  {
        
    }
}

?>
