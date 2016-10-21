<?php

/**
 * @author 
 * @copyright 2011
 */

include_once("classes/html_page.class.php");

abstract class TopFramePage extends HTMLPage
{
 protected $TopFrameAdress = "";
 protected $LeftMenuFrameAdress = "";
 protected $MainFrameAdress = "";

 function __construct($Theme)
 {
    parent::__construct("[Верхний блок панели координации и учета работы менеджеров]");
 }
 
 function BodyHTML()
 {
    echo "
        <ul id=\"globalnav\">
            <li><a href=\"#\">Home</a></li>
            <li><a href=\"#\" class=\"here\">About</a>
                <ul>
                    <li><a href=\"#\">Vision</a></li>
                    <li><a href=\"#\">Team</a></li>
                    <li><a href=\"#\">Culture</a></li>
                    <li><a href=\"#\">Careers</a></li>
                    <li><a href=\"#\" class=\"here\">History</a></li>
                    <li><a href=\"#\">Sponsorship</a></li>
                </ul>
            </li>
            <li><a href=\"#\">News</a></li>
            <li><a href=\"#\">Proof</a></li>
            <li><a href=\"#\">Process</a></li>
            <li><a href=\"#\">Expertise</a></li>
            <li><a href=\"#\">Help</a></li>
        </ul>";
 }

 function Logo()
 {
    echo "<h1>Домашняя страница Васи Пупкина</h1>";
 }

 function Menu()
 {
 echo <<<HTML
<table>
 <tr>
 <td><a href='index.php'>Главная страница</a></td>
 <td><a href='bio.php'>Биография</a></td>
 <td><a href='links.php'>Ссылки</a></td>
 </tr>
</table>
HTML;
 }

 abstract function MainText();
 
}

?>