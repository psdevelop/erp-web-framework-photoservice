<?
# Rad User Manager Version 2.90
# Copyright (C) Rad Inks (Pvt) Ltd. 2003-2005
# http://www.radinks.net/

# Licence:
# The contents of this file are subject to the Mozilla Public
# License Version 1.1 (the "License"); you may not use this file
# except in compliance with the License. You may obtain a copy of
# the License at http://www.mozilla.org/MPL/
# 
# Software distributed under the License is distributed on an "AS
# IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
# implied. See the License for the specific language governing
# rights and limitations under the License.
# 
# The Initial Developer of the Original Code is Rad Inks (Pvt) Ltd.
# Portions created by Rad Inks are Copyright (C) 2003-2005
# Rad Inks (Pvt) Ltd. All Rights Reserved.
# 


require_once("admin_header.php");
require_once("functions.php");
require_once("../members/common.php");
session_start();

$admin = new UserAdmin();


if(!is_admin())
{
	echo "<html><body>";
	require("sorry.php");
	echo "</html></body>";
	exit();
}

global $userStatus;
$strt = $_REQUEST['strt'];

$admin->strt = (isset($strt))  ? $strt : 0;

if(isset($_REQUEST['del_x']) && is_array($_REQUEST['userId']))
{
	
	
		$ids = join(",",$_REQUEST['userId']);
		
		$query = "DELETE FROM users where userId in ($ids)";
		db_query($query);
		db_error_log();
		
		$query = "DELETE FROM userProfile where userId in ($ids)";
		db_query($query);
		
		/*
		 * he wants to delete something, implement it
		 */

		
}

?>
<html><head><title>admin</title></head><body bgcolor="#FEFFB7">
<script src="../inc/script.js" type="text/javascript" language="javascript"></script>

<form action="useradmin.php">
<table border=0 width="130">
<tr><td bgcolor="#999999"><input type="checkbox" name="all" onClick="select_all();"></td>
    <td bgcolor="#999999" align="center">
		<span style="font-family: verdana; color: #EFEFEF; font-size: 11 px; font-weight: bold">User Name</span></td>
  </tr>
<?


	$admin->show_user_list();
	echo '</table>';
	$admin->create_form_buttons("newuser.php","useradmin.php");
?>
</table>
</form>

<div align="center"><br>
  <a href="../members/logout.php" target="_top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Logout</strong></font></a>
</div>

</body>
</html>
