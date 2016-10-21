<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */
 
require_once("classes/html_page.class.php");
require_once("classes/tools.class.php");
require_once("classes/dbconnector.class.php");
require_once("classes/auth.class.php");
require_once("classes/configuration.php");

abstract class IndexHTMLPage extends HTMLPage
{

 function __construct($Title)
 {
    parent::__construct("[Панель координации и учета работы менеджеров] - ".$Title);
 }
 
 function BodyHTML()
 {
    echo "<body  leftmargin=\"0\" topmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" 
        onLoad=\"\">
    <div id=\"opaco\" class=\"hidden\"></div>
    <div id=\"confirm\" class=\"hidden\">
    <div><input id=\"not_close_action_window\" type=\"checkbox\"/>Не закрывать окно действия</div>
    <p>Подтверждаете действие?<br/>
    <input type=\"button\" class=\"close-btn\" id=\"confirm_yes\" name=\"confirm_yes\" value=\"Да\"/>
    <input type=\"button\" class=\"close-btn\" id=\"confirm_no\" name=\"confirm_no\" onclick=\"closeConfirm();\" value=\"Нет\"/>
    </p></div>
    <div id=\"warning_region\" style=\"font-size:10px; color:#CCCCCC;\"></div>";
    
    //echo "<div>eeeee".$_SESSION['psw']."</div>";
    
    session_start();
    date_default_timezone_set('Europe/Moscow');
    
    if (!isset ($_SESSION['login']))    {
        $_SESSION['login'] = "";
    }
    if (!isset ($_SESSION['psw']))  {
        $_SESSION['psw'] = "";
    }
    if (!isset ($_SESSION['enable_admin']))  {
        $_SESSION['enable_admin'] = false;
    }
    if (!isset ($_SESSION['enable_deleting']))  {
        $_SESSION['enable_deleting'] = false;
    }
    if (!array_key_exists('operator_id', $_SESSION))    {
        $_SESSION['operator_id'] = null;
    }
    if (!array_key_exists('manager_id', $_SESSION))    {
        $_SESSION['manager_id'] = null;
    }
    
    if (isset ($_POST['login']))    {
        $_SESSION['login'] = $_POST['login'];
        
    }
    if (isset ($_POST['psw'])) {
        $_SESSION['psw'] = $_POST['psw'];
    }
    
    if (isset ($_GET['action']))    {
        if ($_GET['action']=="logout")  {
            $_SESSION['login'] = "";
            $_SESSION['psw'] = "";
            $_SESSION['operator_id'] = null;
            $_SESSION['manager_id'] = null;
            $_SESSION['enable_admin'] = false;
            $_SESSION['enable_deleting'] = false;
        }
    }
    
    $Connector = new DbConnector($GLOBALS['dbhost'],$GLOBALS['dbname'],$_SESSION['login'],$_SESSION['psw']);
    
    $UserAuth = new UserAuthentification($Connector);
    if ($UserAuth->checkLogin())    {
        if (isset ($_GET['action']))    {
        if ($_GET['action']=="login")  {
            $UserAuth->writeUserInput($_SESSION['login']);
            }
        }
        $this->MainText($Connector);
    }
    else
    {
        if (isset ($_GET['action']))    {
        if ($_GET['action']=="login")  {
            $UserAuth->writeAttempts();
            }
        }
        $UserAuth->writeLoginForm();
    }
    echo "</body>";
 }

 abstract function MainText($Connector);
 
 function writeHeaderAttachment()   {
    $this->AddCSSLinkTag("styles/jquery-ui-1.8.21.custom.css");
    $this->AddCSSLinkTag("styles/default_theme.css");
    $this->AddCSSLinkTag("styles/top_frame_tabs_globalnav.css");
    $this->AddCSSLinkTag("styles/jqueryslidemenu.css");
    $this->AddCSSLinkTag("styles/jqueryslidemenu.css");
    $this->AddCSSLinkTag("styles/blue/style.css");
    $this->AddCSSLinkTag("styles/anytime.css");
    $this->AddCSSLinkTag("styles/buttons_nerwall.css");
    //$this->AddJSLinkTag("jscripts/jquery-1.6.4.min.js");
    $this->AddJSLinkTag("jscripts/jquery-1.7.2.min.js");
    $this->AddJSLinkTag("jscripts/jquery-ui-1.8.21.custom.min.js");
    $this->AddJSLinkTag("jscripts/jqueryslidemenu.js");
    $this->AddJSLinkTag("jscripts/jquery.tablesorter/jquery.tablesorter.js");
    $this->AddJSLinkTag("jscripts/jquery.tablesorter/jquery.tablesorter.pager.js");
    $this->AddJSLinkTag("jscripts/jquery.tabslideout.v1.2.js");
    //$this->AddJSLinkTag("jscripts/jquery-ui.min.js");
    //$this->AddJSLinkTag("jscripts/jquery-ui-i18n.min.js");
    //$this->AddJSLinkTag("jscripts/jquery.ui.datepicker.js");
    $this->AddJSLinkTag("jscripts/anytime.js");
    $this->AddJSLinkTag("jscripts/ajax220812.js");
    $this->AddJSLinkTag("jscripts/work_managment.js");
 }

}
 

?>
