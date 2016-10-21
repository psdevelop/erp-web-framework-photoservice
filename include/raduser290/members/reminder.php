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


include("../inc/header.php");
require_once('common.php');
session_start();


function reset_password($newPass, $userName)
{
	global $user_password_function;
	
	if($user_password_function == 1)
	{
		$query = "update users set userPassword = password('$newPass') where username='$userName'";
	}
	else
	{
		$query = "update users set userPassword = md5('$newPass') where username='$userName'";
	}
	$result = db_query($query);
	return db_error_log();
}

function gen_password()
{
	$str="ABCDEFGHIJKLMNOPQRSTUVXWYZabcdefghijklmnopqrstuvwxyz01234567890";
	srand((double)microtime()*1000000);

	for($i=0; $i < 6 ; $i++)
	{
		$password .= $str[rand(0,62)];
	}
	return $password;
}

create_header();
echo '<p>&nbsp;</p>';

if(!isset($_REQUEST['submit']))
{
	?>
	<p>&nbsp;</p>
	<form action="reminder.php" method="post">
	<table width="80%" align="center" cellpadding='10'>
		<tr><td colspan=2>
			<? echo $msg_password_reminder; ?>
		</td></tr>
		<tr><td align="center">Username: <input type="text" name="username">
			<input type="submit" value="Fetch Password" name="submit"></td>
		</tr>
	</table>
	<?
}
else
{
	if(is_valid_referer())
	{	
		$username = sanitize_variable($_REQUEST['username']);
		
		$email = get_email($username);
		if($email!="")
		{

			$pwd = gen_password();
			if(reset_password($pwd,$username) != '')
			{
				err_message('Your password could not be reset');
			}
			else
			{

				$fmt = join("",file("reminder.txt"));
				$message = sprintf($fmt,$username,$pwd);
				mail($email,"Your account information updated",$message,"From: $member_service_email\n");
				err_message($msg_pass_changed);
				
				require('login.txt');
			}
		}
		else
		{
			err_message('Your password could not be reset since you do not seem to have entered
			a valid email address in your profile. Please <a href="signup.php">new account</a> and create a new account');
		}
	}
	else
	{
		err_message('Un-authorized access detected your ip has been logged');
		
	}
}
	
create_footer();
	 
	 
?>

