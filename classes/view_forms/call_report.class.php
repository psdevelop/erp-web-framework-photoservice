<?php

/**01-02-2012
 * @author Poltarokov SP
 * @copyright 2012
 */

require_once("classes/view_forms/report.class.php");

class CallReport extends Report  {

    function __construct($dbconnector)    {
        parent::__construct($dbconnector, "Call");
    }
    
}

?>
