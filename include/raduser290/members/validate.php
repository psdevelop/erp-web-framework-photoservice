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

create_header();

/**
 * display a generic error message
 */
function err_message($str)
{
	echo sprintf('<table border=0 width="80%%">
	 				<tr><td>%s</td></tr>
				  </table>',$str);
	/* show the signup form */
	require("signup.txt");				  
}


/**
 * Is the specified validation key ok?
 * returns userId on success. 0 on failure.
 */
function is_valid_key($key,$con)
{
	$query = "select userId from userProfile where userValidationKey = '$key' and userValidated = 0";
	$result = db_query($query,$con);
	
	if($result && db_num_rows($result) ==1)
	{
		$row = db_fetch_row($result);
		return $row[0];

	}
	return 0;
}

/**
 * generally called when the user clicks on the link given in the
 * account verification email.
 */
function set_validated($userId, $con)
{
	$query = "update userProfile set userValidated=1 where userId = $userId";

	$result = db_query($query,$con);
	return (db_error_log() != '');
}

/**
 * if validate_email==1, accounts are created in the disabled state, call
 * this method to enable such accounts.
 */
function enable_account($userId,$con)
{
	$query = "update users set userStatus=1 where userId = $userId";
	$result = db_query($query,$con);
	return (db_error_log() != '');
}

if(!isset($_REQUEST['key']))
{
	header("Location: signup.php");
}
else
{

	$key = $_REQUEST['key'];
	require	("../inc/config.php");
	
	if(($userId = is_valid_key($key,$con)) != 0)
	{
		if(set_validated($userId,$con))
		{
			if(enable_account($userId,$con))
			{
				echo $msg_validated;
			}
			else
			{
				err_message("There was an error in activating your account");
			}
		}
		else
		{
			err_message("There was an error in validating your account");
		}
	}
	else
	{
		err_message("The validation key you have entered was not found in our database");
	}
}


create_footer();
?>

