<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
 require_once("classes/data_model/person.class.php");
 require_once("classes/data_model/kinder_garten.class.php");
 require_once("classes/data_model/plot.class.php");
 require_once("classes/data_model/stock.class.php");
 require_once("classes/data_model/call_status.class.php");
 require_once("classes/data_model/meeting_result_type.class.php");
 require_once("classes/data_model/call.class.php");
 require_once("classes/data_model/order.class.php");
 require_once("classes/data_model/meeting.class.php");
 require_once("classes/data_model/meeting_result.class.php");
 require_once("classes/data_model/shooting.class.php");
 require_once("classes/data_model/district.class.php");
 require_once("classes/data_model/state.class.php");
 require_once("classes/data_model/orders_plots.class.php");
 require_once("classes/data_model/calls_statuses.class.php");
 require_once("classes/data_model/order_status.class.php");
 require_once("classes/data_model/orders_statuses.class.php");
 require_once("classes/data_model/team_item.class.php");
 require_once("classes/data_model/team_type.class.php");
 require_once("classes/data_model/user.class.php");
 require_once("classes/data_model/sector.class.php");
 require_once("classes/data_model/shooting_status.class.php");
 require_once("classes/data_model/shootings_statuses.class.php");

class ObjectCollection extends ArrayIterator
{
    protected $object_class_name;
    
    function __construct($object_class_name, $data)    {
        parent::__construct($data);
        $this->object_class_name = $object_class_name;    
    }
    
    public function offsetGet( $index )
    {
        if( empty( $this->_cache[$index] ) )
        {
            // по просьбам трудящихся
            //ReflectionClass($object_class_name)->newInstanceArgs(parent::offsetGet[$index]);
            $this->_cache[$index] = new $this->object_class_name( parent::offsetGet($index));
        }
        return $this->_cache[$index];
    }

    public function current()
    {
        $index = parent::key();
        if( empty( $this->_cache[$index] ) )
        {
            // по просьбам трудящихся
            $this->_cache[$index] = new $this->object_class_name( parent::current() );
        }
        return $this->_cache[$index];
    } 
}

?>