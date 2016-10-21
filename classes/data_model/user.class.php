<?php

/**25.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class User extends DataObject  {
    public $username;
    public $isactive;
    public $person_id;
    
    function __construct($user)    {
        parent::__construct($user['id'], null);
        $this->username = $user['username'];
        $this->isactive = $user['isactive'];
        $this->person_id = $user['person_id'];
        $this->relative_props['person_name'] = $user['person_name'];
        $this->relative_props['password'] = $user['password'];
        $this->relative_props['enable_admin'] = $user['enable_admin'];
        $this->relative_props['enable_deleting'] = $user['enable_deleting'];
    }
}

?>
