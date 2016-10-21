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


require_once('../members/common.php');
require_once('admin_header.php');
session_start();
create_header();
echo "<p>&nbsp;</p>";

$submit = $_REQUEST['submit'];

if(!isset($submit) && $submit != '')
{
	/* show the signup form */
	require("signup.txt");
}
else
{
	if(!is_valid_username())
	{
		err_message("Please fill in all the fields");
		require("signup.txt");				  
	}
	else if(!is_valid_addr())
	{
		err_message("the email address you entered is not valid or unacceptable.");
		require("signup.txt");				  
	}
	else
	{
		/*
		* check the referer otherwise this script can be used for mail spoofing.
		* todo: a more vigourous check.
		*/
		if(is_valid_referer())
		{
			/*
			* everything has worked out let's create that account.
			*/

			require	("../inc/config.php");
			$username = sanitize_variable($_REQUEST['username']);	
			$password = sanitize_variable($_REQUEST['password']);	

			if($user_password_function == 1)
			{
				$query = "INSERT INTO USERS(userName,userPassword,userStatus)
					values('$username',password('$password'),1)";
			}
			else
			{

				$query = "INSERT INTO USERS(userName,userPassword,userStatus)
					values('$username',md5('$password'),1)";
			}

			db_query($query,$con);

			$err = db_error_log()
			if($err == '')	
			{	

				$userid = db_insert_id('users_userid_seq');
				$email = sanitize_variable($_REQUEST['email']);

				$query = "INSERT INTO userProfile(userEmail, userId)
						VALUES('$email', $userid)";

				db_query($query);
				$err = db_error_log();

				if($err == '')	
				{
					err_message('Account created.');
					require("signup.txt");				  
				}								
				else
				{	
					echo mysql_error();
					err_message('The username you chose is already in use');
					require("signup.txt");				  
				}	
			}
			else
			{	
				err_message('The username you chose is already in use');
				require("signup.txt");				  
			}	
		}
		else	
		{	
			err_message('<font face="verdana" size="2">Username already in use</font>');
			require("signup.txt");				  
		}
	}
}

create_footer();
	 
?>

