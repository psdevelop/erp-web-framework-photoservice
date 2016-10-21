<?php

/**21.11.2011
 * @author Poltarokov SP
 * @copyright 2011
 */
 
    ini_set('display_errors',1);
    session_start();
    date_default_timezone_set('Europe/Moscow');

    require_once("classes/dbconnector.class.php");
    require_once("classes/configuration.php");
    require_once("classes/auth.class.php");
    require_once("classes_header.php");

    $class_name="";
    if (isset($_GET['class_name'])) 
	    $class_name=$_GET['class_name'];	
    else 
	    die("Не задан параметр сущности для скрипта выборки!");
    
    $request_mode="";
    if (isset($_GET['request_mode'])) 
	    $request_mode=$_GET['request_mode'];	
	//else 
	//    $select_table_name="";

    $Connector = new DbConnector($GLOBALS['dbhost'],$GLOBALS['dbname'],$_SESSION['login'],$_SESSION['psw']);
    $UserAuth = new UserAuthentification($Connector);
    
    if ($UserAuth->checkLogin())    {
    
    $reflectionClass = new ReflectionClass($class_name."TableAdapter");
    $DictTAdapt = $reflectionClass->newInstanceArgs(array($Connector,
        "", $class_name));

    //echo
    //$DictTAdapt->prepareFilterArray($_GET);
    //print_r($_GET);
    echo "<html><head><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
        </head><body>".$DictTAdapt->generateDetailInfo($_GET)."</body></html>";
    
    if ($GLOBALS['global_ajax_timeout']>0)
        sleep($GLOBALS['global_ajax_timeout']);
    }   else
        echo "Нет аутентификации!";

?>
