<?php

/**24.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class TeamType extends DataObject  {
    public $team_type_name;
    
    function __construct($team_type)    {
        parent::__construct($team_type['id'], null);
        $this->team_type_name = $team_type['team_type_name'];
    }
}

?>
