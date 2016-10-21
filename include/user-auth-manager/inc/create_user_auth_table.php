<?php
// !!!!!!!!!!!! DO NOT TOUCH !!!!!!!!!!
// ask ian first
// mysql> describe users;
// +----------+--------------+------+-----+---------+----------------+
// | Field    | Type         | Null | Key | Default | Extra          |
// +----------+--------------+------+-----+---------+----------------+
// | uid      | bigint(20)   | NO   | PRI | NULL    | auto_increment |
// | fname    | varchar(100) | YES  |     | NULL    |                |
// | lname    | varchar(100) | YES  |     | NULL    |                |
// | email    | varchar(100) | NO   | UNI | NULL    |                |
// | password | varchar(45)  | YES  |     | NULL    |                |
// +----------+--------------+------+-----+---------+----------------+
// 
require_once (dirname(__FILE__) .  "/config.php");

$dbhost=$GLOBALS['dbhost'];
$dbuser=$GLOBALS['dbuser'];
$dbpass=$GLOBALS['dbpass'];
$database=$GLOBALS['dbname'];
$user_tbl = $GLOBALS['user_table'];

$link = mysql_connect($dbhost,$dbuser,$dbpass);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo "Connected successfully\n";

/* ------------------- 
 */
$sql = "CREATE  TABLE IF NOT EXISTS $database.$user_tbl (
  uid BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'uid' ,
  fname VARCHAR(100) NULL ,
  lname VARCHAR(100) NULL ,
  email VARCHAR(100) UNIQUE NOT NULL COMMENT 'real user name used for login credentials' ,
  password VARCHAR(45) NULL ,
  PRIMARY KEY (uid))
ENGINE = InnoDB";

if (mysql_query($sql, $link)) {
    echo "'$database' table '$user_tbl' created successfully\n";
} else {
    echo 'Error creating table : ' . mysql_error() . "\n";
}

echo "DONE: '$database' ALL tables created successfully\n";
mysql_close($link);

?>
