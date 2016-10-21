<?php

require('config.php');

$database = 'fr';
$db = new mysqli(HOST,USER,PASS,$database);
function db_query($db,$query){
	//echo $query . '<br />';
	$result = $db->query($query);
	if(!$result) {
		printf('MySQL Error %s<br />',mysqli_error($db));
		printf('<b>The query:</b> %s <b>failed.</b><br />',$query);
		exit;
	} else {
		printf('<b>The query:</b> %s <b>was successfully completed.</b><br />',$query);
	}
}

$query = "CREATE TABLE IF NOT EXISTS `user_info` (
  `uid` bigint(20) NOT NULL COMMENT 'uid',
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `streetaddress` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip` varchar(5) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `fax` varchar(12) DEFAULT NULL,
  `activation` tinyint(4) DEFAULT NULL,
  `activation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

db_query($db,$query);

?>