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


require_once("../inc/header.php");
require_once('common.php');

session_start();


$password = sanitize_variable($_REQUEST['password']);
$password1 = sanitize_variable($_REQUEST['password1']);

create_header($msg_title);

echo "<p>&nbsp;</p>";


if(!isset($_REQUEST['submit']))
{

	/* show the signup form */
	echo $msg_signup;
	
	require("signup.txt");
}
else
{
	if($password != $password1)	
	{	
		err_message('Passwords do not match');
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
			if(is_valid_referer())
			{
				
				/*
				 * everything has worked out let's create that account.
				 */
						 
				require	("../inc/config.php");
				$username = sanitize_variable($_REQUEST['username']);	

				
				$userStatus = ($validate_email == 0) ? 1 : 0;
				
				if($user_password_function == 1)
				{

					$query = "INSERT INTO users(userName,userPassword,userStatus)
						values('$username',password('$password'),1)";
					
				}
				else
				{
					$query = "INSERT INTO users(userName,userPassword,userStatus)
						values('$username',md5('$password'),1)";
				}
				

				db_query($query);
				
				if(db_error_log() == '')
				{
					error_log('set profile');
					$uniqueId = md5(uniqid($username));
					$email = sanitize_variable($_REQUEST['email']);	
					$firstname = sanitize_variable($_REQUEST['firstname']);	
					$lastname = sanitize_variable($_REQUEST['lastname']);	
					$userid = db_insert_id('users_userid_seq');
					$ip = sanitize_variable($_SERVER['REMOTE_ADDR']);
					/*
					* create the profile 
					*/
					$query = "INSERT INTO
							userProfile(userId,userFirstName,userLastName,
								userEmail,userValidationKey,userIP,userSignUp)
							VALUES($userid,'$firstname','$lastname','$email',
								'$uniqueId','$ip',now())";
					
					db_query($query,$con);
					$err = db_error_log();
					
					if($err  == '')
					{
						require("thanks.txt");
						$f = join("",file("welcome-email.txt"));
							
						/* generate formatted message */
						if($validate_email)
						{
							$link = "$site_url" ."members/validate.php?key=$uniqueId";
							$message = sprintf($f,"$firstname $lastName",$link,$site_url);
							mail($email,"$site_name membership confirmation",$message,"From: $member_service_email\n");
						}
						else
						{
							if($welcome_email == 1)
							{
								$message = sprintf($f,"$firstname $lastName",$site_url);
								mail($email,"$site_name membership confirmation",$message,"From: $member_service_email\n");
							}
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
					err_message('The username you chose is already in use');
					require("signup.txt");				  
				}	
			}
			else	
			{	
				err_message('<font face="verdana" size="2">Possible attempt to abuse this script detected.</font>');
				require("signup.txt");				  
			}
		}
	}
}


create_footer();
	 
	 
?>

