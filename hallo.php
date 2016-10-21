<?php
class Test {

	public $caption="";

	function __construct($caption)
	{
		$this->caption = $caption;
	}
	
	function writeCaption($additional_replic)	{
		echo "<br/><br/><br/><center>
			<div style=\"padding:15px;margin:250px;background-color:#CCCCCC;\"><i><br/>".
			$this->caption."<br/><b>".$additional_replic."</b><br/><br/>
			<img src=\"images\ajax-loader.gif\"><br/></i></div></center>";
	}
	
	
}

$tst = new Test("Извините, сайт временно находится на обслуживании...");
$tst->writeCaption(" Заебали, ждите....");

?>