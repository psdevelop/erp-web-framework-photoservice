<?php

/** 14.12.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class ShootingsStatuses extends DataObject  {
    public $shooting_id;
    public $shooting_status_id;
    public $shooting_date;
    public $comment;
    
    function __construct($shootings_statuses)    {
        parent::__construct($shootings_statuses['id'], null);
        $this->shooting_id = $shootings_statuses['shooting_id'];
        $this->shooting_status_id = $shootings_statuses['shooting_status_id'];
        $this->shooting_date = $shootings_statuses['shooting_date'];
        $this->comment = $shootings_statuses['comment'];
    }
}

?>
