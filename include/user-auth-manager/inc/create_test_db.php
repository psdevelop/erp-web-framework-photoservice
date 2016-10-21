<?php

require_once (dirname(__FILE__) .  "/config.php");

$dbhost=$GLOBALS['dbhost'];
$dbuser=$GLOBALS['dbuser'];
$dbpass=$GLOBALS['dbpass'];
$database=$GLOBALS['dbname'];

$link = mysql_connect($dbhost,$dbuser,$dbpass);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo "Connected successfully\n";

/* DROP existing database
 */
$sql =  "DROP DATABASE IF EXISTS $database";
if (mysql_query($sql, $link)) {
    echo "Database '$database' dropped successfully\n";
} else {
    echo 'Error dropping database: ' . mysql_error() . "\n";
}   

/* CREATE database
 */
$sql = "CREATE DATABASE $database";
if (mysql_query($sql, $link)) {
    echo "Database '$database' created successfully\n";
} else {
    echo 'Error creating database: ' . mysql_error() . "\n";
}

mysql_close($link);

?>
