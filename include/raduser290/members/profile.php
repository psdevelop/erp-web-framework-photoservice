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

require_once("common.php");
$password = sanitize_variable($_REQUEST['password']);
$password1 = sanitize_variable($_REQUEST['password1']);

if(($userId = is_logged())!= 0)
{
	require('../inc/header.php');
	create_header();
	echo '<p>&nbsp;</p>';

	if(isset($_REQUEST['submit']))
	{
		if($_REQUEST['submit']=='Change password')
		{
			if($password1 != $password)
			{
				err_message('The passwords do not match');
			}
			else if($password == '')
			{
				err_message('The password cannot be empty');
			}
			else
			{
				if(change_password($userId,$password) != '')
				{

					err_message('Sorry the password could not be changed');
				}
				else
				{
					err_message('Your password has been changed');
				}
			}
		}
		else if($_REQUEST['submit'] =='update')
		{
			$news = sanitize_variable($_REQUEST['news']);
			if(change_newsletter($userId,$news) != '')
			{
				err_message('Sorry could not update your newsletter subscription');
			}
		}
		else
		{
			$profile = new UserProfile();
			$profile->id = $userId;
			$profile->firstName = sanitize_variable($_REQUEST['firstName']);
			$profile->lastName =  sanitize_variable($_REQUEST['lastName']);
			$profile->email =  sanitize_variable($_REQUEST['email']);
			$profile->addr1 =  sanitize_variable($_REQUEST['addr1']);
			$profile->addr2 =  sanitize_variable($_REQUEST['addr2']);
			$profile->city =  sanitize_variable($_REQUEST['city']);
			$profile->state =  sanitize_variable($_REQUEST['state']);
			$profile->country =  sanitize_variable($_REQUEST['country']);
			$profile->tel =  sanitize_variable($_REQUEST['tel']);
			$profile->mobiTel =  sanitize_variable($_REQUEST['mobitel']);
			$profile->homeTel =  sanitize_variable($_REQUEST['hometel']);
			$profile->web =  sanitize_variable($_REQUEST['web']);
			$profile->fax =  sanitize_variable($_REQUEST['fax']);
			
			if(change_profile($profile) != '')
			{
				err_message('Sorry could not update your profile');
			}
		}
	}
	show_profile($userId);
	
	create_footer();
}
else
{
	//header("Location: index.php\n");
	echo 'ee';
}
	
?>
