<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

include_once("classes/top_frame_page.class.php");

class TopFrame extends TopFramePage
{
 function MainText()
 {
 echo "<p>Добро пожаловать на домашнюю страничку Васи Пупкина";
 }
}

$Page = new TopFrame("Default");

$Page->Write();

?>