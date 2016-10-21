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

require_once('common.php');


if(!isset($_REQUEST['submit']))
{
	if(!isset($title))
	{
		require_once("../inc/header.php");
		create_header();
	}
	require("login.txt");
}
else
{
	$userName = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	
	require	("../inc/config.php");
	
	if(($userId = is_valid($userName,$password,$con)) != 0)
	{

		@session_start();
		$sessionId = session_id();

		if(set_session($userId,$sessionId,$con))
		{
			/*
			 * now he is logged in. We will now try to find out
			 * if he wants to login as an admin.
			 */
			if(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==1)
			{
				$i = is_admin();
				if($i != 0)
				{
					$_SESSION['role'] = iMANAGER;
				}
				unset($_SESSION['adminLogin']);
			}

			if(isset($ret) && is_valid_referer())
			{
				$url = $_REQUEST['url'];
				header("Location: $url");
			}
			else
			{
				header("Location: my.php");
			}
		}
		else
		{

			require_once("../inc/header.php");
			create_header();

			echo '<p>&nbsp;</p>';
			echo '<table width="90%" align="center"><tr><td>';
			
			err_message("There was an error in loging you in please contact $member_service_email");
			require("login.txt");				  
		}
	}
	else
	{
			require_once("../inc/header.php");
			create_header();

			echo '<p>&nbsp;</p>';
			echo '<table width="90%" align="center"><tr><td>';
			err_message("The username/password combination you entered was not found in our database");
			require("login.txt");				  
	}
}

echo '</td></tr></table>';
require_once('../inc/header.php');
create_footer();
?>

