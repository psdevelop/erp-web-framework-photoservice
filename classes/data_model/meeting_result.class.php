<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once(dirname(__FILE__)."/data_object.class.php");

class MeetingResult extends DataObject  {
    public $meeting_id;
    public $stock_id;
    public $plot_id;
    public $plots_array;
    public $meeting_result_type_id;
    public $meeting_date;
    public $planned_shooting_date;
    public $meeting_result_comment;
    public $planned_child_count;
    public $planned_group_count;
    public $planned_small_gr_count;
    public $planned_shooting_place;
    
    function __construct($meeting_result)    {
        parent::__construct($meeting_result['id'], null);
        $this->meeting_id = $meeting_result['meeting_id'];
        $this->stock_id = $meeting_result['stock_id'];
        $this->plot_id = $meeting_result['plot_id'];
        $this->meeting_date = $meeting_result['meeting_date'];
        $this->relative_props['manager_id'] = $meeting_result['manager_id'];
        $this->meeting_result_type_id = $meeting_result['meeting_result_type_id'];
        $this->relative_props['call_id'] = $meeting_result['call_id'];
        $this->planned_shooting_date = $meeting_result['planned_shooting_date'];
        $this->meeting_result_comment = $meeting_result['meeting_result_comment'];
        $this->plots_array = null;
        $this->planned_child_count=0;
        $this->planned_group_count=0;
        $this->planned_small_gr_count=0;
        $this->planned_shooting_place="Спортивный зал";
        $this->relative_props['stock_name'] = $meeting_result['stock_name'];
        $this->relative_props['plot_name'] = $meeting_result['plot_name'];
        $this->relative_props['manager_name'] = $meeting_result['manager_name'];
        $this->relative_props['meeting_result_type_name'] = $meeting_result['meeting_result_type_name'];
        $this->relative_props['meeting_name'] = $meeting_result['meeting_name'];
        $this->relative_props['call_name'] = $meeting_result['call_name'];
    }
}

?>