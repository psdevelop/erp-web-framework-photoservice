<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

interface ObjectManipInterface    {
    function writeTableHeader($linked_props);
    function writeTableRow($object, $linked_props);
}

?>