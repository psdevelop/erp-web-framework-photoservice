<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

    function prepareParamArray($get_array)  {
        $get_param_array = array();
        $get_keys_array = array_keys($get_array);
        foreach ($get_keys_array as $get_key)   {
            if(($get_key!="class_name")&&($get_key!="request_mode"))    {
                $get_param_array[":".$get_key] = $get_array[$get_key];
            }
        }
        return $get_param_array;
    }

    ini_set('display_errors',1);
    session_start();
    date_default_timezone_set('Europe/Moscow');

    require_once("classes/dbconnector.class.php");
    require_once("classes/configuration.php");
    require_once("classes/auth.class.php");
    require_once("classes_header.php");
    
    //$prop_keys = array_keys($_GET);
    $class_name="";
    if (isset($_GET['class_name'])) 
	    $class_name=$_GET['class_name'];
    else
        die("Не задано имя класса для скрипта манипуляции!");	
	//else 
	//    $class_name="";
    
    $request_mode="";
    if (isset($_GET['request_mode'])) 
	    $request_mode=$_GET['request_mode'];
    else
        die("Не задан режим для скрипта манипуляции!");	
	//else 
	//    $select_table_name="";

    $Connector = new DbConnector($GLOBALS['dbhost'],$GLOBALS['dbname'],$_SESSION['login'],$_SESSION['psw']);
    $UserAuth = new UserAuthentification($Connector);
    
    //print_r($_GET);
    
    if ($UserAuth->checkLogin())    {
    
    $reflectionClass = new ReflectionClass($class_name."TableAdapter");
    $DictTAdapt = $reflectionClass->newInstanceArgs(array($Connector,
        "", $class_name));
    
    
        
    if ($request_mode==$GLOBALS['insert_manip_mode'])   {
        //$DictTAdapt->insertDataObject();
        //$MODIFY_GET = $_GET;

        $instr_param_array = $DictTAdapt->object_adapter->prepareParamArray($_GET, $request_mode);
        $default_sessions_keys = array_keys($DictTAdapt->default_insert_session_params);
        foreach($default_sessions_keys as $default_sessions_key)    {
            if(array_key_exists($default_sessions_key, $instr_param_array))    {
                if($instr_param_array[$default_sessions_key]==null)
                    $instr_param_array[$default_sessions_key]=
                        $_SESSION[$DictTAdapt->default_insert_session_params[$default_sessions_key]];
            }   else
                $instr_param_array[$default_sessions_key]=
                    $_SESSION[$DictTAdapt->default_insert_session_params[$default_sessions_key]];
        }
        //print_r($instr_param_array);
        $DictTAdapt->insertDataObject($instr_param_array);
    }   else if ($request_mode==$GLOBALS['update_manip_mode'])   {
        //$DictTAdapt->insertDataObject();
        $instr_param_array = $DictTAdapt->object_adapter->prepareParamArray($_GET, $request_mode);
        //print_r($instr_param_array);
        $DictTAdapt->updateDataObject($instr_param_array);
    }   else if ($request_mode==$GLOBALS['delete_manip_mode'])   {
        //$DictTAdapt->insertDataObject();
        $instr_param_array = $DictTAdapt->object_adapter->prepareParamArray($_GET, $request_mode);
        //print_r($instr_param_array);
        $DictTAdapt->deleteDataObject($instr_param_array);
    }   else if ($request_mode==$GLOBALS['partial_update_manip_mode'])   {
        //$DictTAdapt->insertDataObject();
        $instr_param_array = $DictTAdapt->object_adapter->prepareParamArray($_GET, $request_mode);
        //print_r($instr_param_array);
        echo $DictTAdapt->updateObjectPartial($instr_param_array);
    }   else if ($request_mode==$GLOBALS['fast_append_manip_mode'])   {
        //$DictTAdapt->insertDataObject();
        $MODIFY_GET = $_GET;
        $default_sessions_keys = array_keys($DictTAdapt->default_fast_append_session_params);
        foreach($default_sessions_keys as $default_sessions_key)    {
            if(array_key_exists($default_sessions_key, $MODIFY_GET))    {
                if($MODIFY_GET[$default_sessions_key]==null)
                    $MODIFY_GET[$default_sessions_key]=
                        $_SESSION[$DictTAdapt->default_fast_append_session_params[$default_sessions_key]];
            }   else
                $MODIFY_GET[$default_sessions_key]=
                    $_SESSION[$DictTAdapt->default_fast_append_session_params[$default_sessions_key]];
        }
        //print_r($_SESSION);
        $instr_param_array = $DictTAdapt->object_adapter->prepareParamArray($MODIFY_GET, $request_mode);
        //print_r($instr_param_array);
        echo $DictTAdapt->fastObjAppend($instr_param_array);
    }   else    {
        
    }

    //$DictTAdapt = new PersonTableAdapter($Connector,$select_table_name,"Person",$select_table_name);
    //$DictTAdapt->selectWithRelative();
    //$DictTAdapt->writeTable();
    
    if ($GLOBALS['global_ajax_timeout']>0)
        sleep($GLOBALS['global_ajax_timeout']);
    }   else
        echo "Нет аутентификации!";

?>