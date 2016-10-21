<?php

/**24.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class TeamItem extends DataObject  {
    public $person_type_id;
    public $person_id;
    public $team_object_id;
    public $team_type_id;
    public $action_datetime;
    
    function __construct($team_type)    {
        parent::__construct($team_type['id'], null);
        $this->person_type_id = $team_type['person_type_id'];
        $this->person_id = $team_type['person_id'];
        $this->team_object_id = $team_type['team_object_id'];
        $this->team_type_id = $team_type['team_type_id'];
        $this->action_datetime = $team_type['action_datetime'];
        $this->relative_props['team_type_name'] = $team_type['team_type_name'];
        $this->relative_props['person_type_name'] = $team_type['person_type_name'];
        $this->relative_props['person_name'] = $team_type['person_name'];
    }
}

?>