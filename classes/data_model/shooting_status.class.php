<?php

/**20.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class ShootingStatus extends DataObject  {
    public $shooting_status_name;
    
    function __construct($shooting_status)    {
        parent::__construct($shooting_status['id'], null);
        $this->shooting_status_name = $shooting_status['shooting_status_name'];
    }
}

?>
