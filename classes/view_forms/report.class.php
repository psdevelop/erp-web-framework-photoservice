<?php

/**01-02-2012
 * @author Poltarokov SP
 * @copyright 2012
 */

class Report  {
    protected $report_action_caption = "Сформ. отчет";
    protected $element_display_template = null;
    protected $master_table_adapter = null;
    protected $report_mode = 1;
    protected $report_class = null;
    protected $default_report_name = null;
    protected $json_out_type = false;

    function __construct($dbconnector, $class_name, $report_class="")    {
        $reflectionClass = new ReflectionClass($class_name."TableAdapter");
        $this->master_table_adapter = $reflectionClass->newInstanceArgs
                (array($dbconnector,"",$class_name));
        $this->master_table_adapter->resetAllFilters();
        $this->report_class = $report_class;
    }
    
    function setDefaultReportName($default_report_name) {
        $this->default_report_name = $default_report_name;
    }
    
    function acceptAjaxParams($params, $entity_suffix="") {
        //$this->master_table_adapter->assignNumSuffix($entity_suffix);
        $this->master_table_adapter->prepareFilterArray($params);
    }
    
    function generateReportExecutionPanel($filtered_object_container)  {
        $this->master_table_adapter->generateCustomFiltersForm($filtered_object_container, 
                $GLOBALS['out_report_mode'], $this->report_action_caption);
    }
    
    function getReportBody() {
        
    }
    
    function setJSONOutType($json_out_type) {
        $this->json_out_type = $json_out_type;
    }
    
    function gen_data_provider($data_array) {
        $data = array();
        $json_entities = array_keys($data_array);
        $count = count($data_array[$json_entities[0]][1]);
        for ($i = 0; $i < $count; $i++) {
            $data_item = array();
            
            foreach($json_entities as $json_entity) {
                $data_item[$data_array[$json_entity][0]] = $data_array[$json_entity][1][$i];
            }
            
            $data[] = $data_item;//array('country'=>tempnam('',''),'litres'=>rand(0, $max));
        }
        return $data;
    }
}

?>
