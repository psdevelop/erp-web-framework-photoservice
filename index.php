<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

ini_set('display_errors',1);

require_once("classes/index_html_page.class.php");
require_once("classes/dbconnector.class.php");
require_once("classes/auth.class.php");
require_once("classes/configuration.php");
require_once("classes/main_menu.class.php");
require_once("classes/view_forms/report.class.php");
require_once("classes_header.php");

class IndexPage extends IndexHTMLPage
{
 function MainText($Connector)
 {  
        $main_menu = new MainMenuClass();
        $main_menu->writeMenu();
        
        $class_name = "Person";
        if (isset($_SESSION['operator_id']))
                $class_name="Call";
        
        if (isset($_GET['class_name'])) 
            $class_name=$_GET['class_name'];
        
        if(array_key_exists("report_mode", $_GET))  {
            $reflectionClass = new ReflectionClass($class_name."Report");
            $ReportObject = $reflectionClass->newInstanceArgs(array($Connector,$class_name));
            $ReportObject->generateReportExecutionPanel($class_name.$GLOBALS['dict_container_base']);
            $this->write_div($class_name.$GLOBALS['dict_container_base'],"","");
        }
        else    {
        $reflectionClass = new ReflectionClass($class_name."TableAdapter");
        $DictTAdapt = $reflectionClass->newInstanceArgs(array($Connector,
            "",$class_name));
        $this->writeJScript(" function reloadCurrentDict() { ".$DictTAdapt->getCurrentSelectJSWithFilter()." } ");
        if (($class_name=="Call"))  {
        if (array_key_exists("sector_manager_id", $DictTAdapt->values_filter_values))
            $DictTAdapt->values_filter_values['sector_manager_id']=$_SESSION['manager_id']; 
        //if (array_key_exists("sector_operator_id", $DictTAdapt->values_filter_values))
        //    $DictTAdapt->values_filter_values['sector_operator_id']=$_SESSION['operator_id'];
        if (array_key_exists("operator_id", $DictTAdapt->filters_values))
            $DictTAdapt->filters_values['operator_id']=$_SESSION['operator_id'];
        
        }
        if (($class_name=="Order"))  {
        if (array_key_exists("sector_manager_id", $DictTAdapt->values_filter_values))
            $DictTAdapt->values_filter_values['sector_manager_id']=$_SESSION['manager_id'];  
        if (array_key_exists("sector_operator_id", $DictTAdapt->values_filter_values))
            $DictTAdapt->values_filter_values['sector_operator_id']=$_SESSION['operator_id'];
        
        }
        if (($class_name=="KinderGarten"))  {
        if (array_key_exists("sector_manager_id", $DictTAdapt->values_filter_values))
            $DictTAdapt->values_filter_values['sector_manager_id']=$_SESSION['manager_id'];  
        if (array_key_exists("sector_operator_id", $DictTAdapt->values_filter_values))
            $DictTAdapt->values_filter_values['sector_operator_id']=$_SESSION['operator_id'];
        
        }
        if (($class_name=="Meeting"))  {
        if (array_key_exists("sector_manager_id", $DictTAdapt->values_filter_values))
            $DictTAdapt->values_filter_values['sector_manager_id']=$_SESSION['manager_id'];  
        if (array_key_exists("sector_operator_id", $DictTAdapt->values_filter_values))
            $DictTAdapt->values_filter_values['sector_operator_id']=$_SESSION['operator_id'];
        
        }
        //$this->write_div("master_dict_div","","");
        if (
                (   ($class_name=="User")&&(!$_SESSION['enable_admin'])&&
                (!($_SESSION['login']==$GLOBALS['dbuser']))     )
                ||
                (isset($_SESSION['operator_id'])&&($class_name!="Call"))
                )  {
            echo "Не доступно!";
        }   else    {
            $DictTAdapt->generateFiltersForm($class_name.$GLOBALS['dict_container_base']);
            echo "<table border=\"0\"><tr><td>";
            $DictTAdapt->generateAObjectsHTML();
            $DictTAdapt->writeActionsForms();
            echo "</td><td>";
            $DictTAdapt->generateFastManipHTML();
            echo "</td></tr></table>";
            echo "<table border=\"0\"><tr><td valign=\"top\">";
            $this->write_div($class_name.$GLOBALS['dict_container_base'],"","");
            echo "</td><td valign=\"top\">";
            echo "<div id=\"{$GLOBALS['dict_detail_base']}\" class=\"hidden\"></div>";
            echo "</td></tr></table>";
            $DictTAdapt->generateInsertForm();
            $DictTAdapt->generateDictDetail();
    
            $this->writeJScript($this->getStartJS($Connector, $class_name));
        }
        }
 }
 
 function getStartJS($Connector, $start_class = "Person")  {
    $class_name = $start_class;
    if (isset($_GET['class_name'])) 
        $class_name=$_GET['class_name'];
    
    $reflectionClass = new ReflectionClass($class_name."TableAdapter");
    $DictTAdapt = $reflectionClass->newInstanceArgs(array($Connector,
        "",$class_name));
    
    return $DictTAdapt->generateSelectJSWithFilter("",0,$class_name.$GLOBALS['dict_container_base']);
    //return $DictTAdapt->generateSelectJS("",0,"master_dict_div");
 }
 
}

$Page = new IndexPage("Главная страница");

$Page->Write();

?>