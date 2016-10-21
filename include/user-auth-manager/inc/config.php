<?php

ini_set("include_path", ".:./inc:../classes");

$dbhost = 'localhost';
$dbname = 'fr';
$dbuser = 'root';
$dbpass = '123456';
define('HOST','localhost');
define('USER','root');
define('PASS','123456');

$user_base_dir = './media';
$user_table = "users";
$admin_user_table = "admin_users";

// optional config for additional user info
// 1) add/create additional user info table to DB by editing the create_user_info_table.php file
// 2) update UserAuthMgrUserTable.php file
// 3) call to getUserInfo() will join user auth table to additional user info table
//     application client will have to know user info table format
// var          =    table name in DB
$user_info_table = 'user_info';

$kit_table = 'kit';

$adminemail = 'admin@admin.com';   // TODO change to real site admin email


?>
