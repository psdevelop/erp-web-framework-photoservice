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


require_once(dirname(__FILE__)."/../inc/config.php");


define("iMANAGER",4);
define("iUSER",1);


class UserProfile
{
	var $id;
	var $userName;
	
	var $title;
	var $firstName;
	var $lastName;
	var $company;
	
	var $email;
	var $addr1;
	var $addr2;
	var $city;
	var $state;
	var $country;
	var $tel;
	var $fax;
	var $mobiTel;
	var $homeTel;
	var $web;
	var $key;
	var $IP;
	var $signUp;
	var $validated;
	var $newsLetter;
	var $zip;
}


function db_query($query)
{
	global $db_type;
	
	
	if($db_type == 'mysql')
	{
		$res = @mysql_query($query);
	}	
	else
	{
		$res = @pg_query($query);
	}
	return $res;
}

function db_num_rows($res)
{
	global $db_type;
	if($db_type == 'mysql')
	{

		return @mysql_num_rows($res);
	}
	else
	{
		return @pg_num_rows($res);
	}
}


function db_fetch_row($res)
{
	global $db_type;
	if($db_type == 'mysql')
	{
		return mysql_fetch_row($res);
	}
	else
	{
		return pg_fetch_row($res);
	}
}


function db_fetch_array($res)
{
	global $db_type;
	if($db_type == 'mysql')
	{
		return mysql_fetch_array($res);
	}
	else
	{
		$row=pg_fetch_assoc($res);
		return $row;
	}
}

function db_error_log($line='')
{
	global $db_type;
	
	
	if($db_type == 'mysql')
	{
	 	if(mysql_errno() != 0)
		{
			$errMessage = mysql_error();
			error_log("$line $errMessage");
			return $errMessage;
		}
	}
	else
	{
		$errMessage = pg_last_error();
		error_log("$line $errMessage");
		return $errMessage;
	}
}

function db_insert_id($sequence)
{
	global $db_type;
	if($db_type == 'mysql')
	{
		return mysql_insert_id();
	}
	else
	{
		$result = pg_query("SELECT currval('$sequence')");
		if($result)
		{
			$row = pg_fetch_row($result);
			return $row[0];
		}
	}
}

/**
 * shows a formatted error message
 */
function err_message($str)
{
	echo sprintf('<table border=0 width="350" align="center">
	 				<tr><td>%s</td></tr>
				  </table><br>',$str);
}

/**
 * this function returns the currently logged in user's username
 */
function get_name()
{
	global $con;
	$sid = session_id();
	$query = "SELECT a.userFirstName FROM userProfile a, loggedUsers b
			  WHERE b.sessionId = '$sid' and b.userId = a.userId";
	
	$result = db_query($query);

	if($result)
	{
		$row = db_fetch_row($result);

		return $row[0];
	}
	else
	{
		return "";
	}
			  
}

/**
 * Creates an entry in the logged users table. Call this method 
 * directly if you want to automatically log in a new user who 
 * has just signed up.
 */
 
function set_session($userId,$sessionId, $con)
{
	$query = "INSERT INTO loggedUsers(userId,sessionId, loginTime,lastAccess )
		VALUES($userId,'$sessionId', now(),now())";
			  
	$result = db_query($query,$con);
	
	if(db_error_log() != '')
	{
		/*
		 * it could be that you are already logged in 
		 */
		 $u2 = is_logged($sessionId);
		 
		 return ($u2 == $userId);
	}

	return 1;
}

/**
 * this should not be a function, it should be a cron. It has however
 * been made available so that you have a means of cleaning up unwanted
 * sessions, even if you do not have access to the cron daemon or other
 * scheduling mechanism.
 */
function clean_sessions()
{
	global $db_type;
	if($db_type=='mysql')
	{
		$query = "delete from loggedUsers where
				unix_timestamp(date_add(lastAccess, interval 1 hour)) < unix_timestamp(now())";
	}
	else
	{
		$query = "delete from loggedUsers where
				round(date_part('epoch',lastAccess + interval '1 hour')) < round(date_part('epoch',now()))";
	}

	$result = db_query($query);
}

/**
 * returns 0 if you are not logged in. else returns your userid
 * also updates the 'lastAccess' field in the logged users table.
 */
function is_logged($sid="")
{
	global $con,$db_type;
	
	
	if(!isset($sid) || $sid == '')
	{
		$sid = session_id();
	}
	
	/*
	 * if you set up a cron to clean up unwanted sessions, please comment
	 * the next line.
	 */
	clean_sessions();
	if($db_type=='mysql')
	{
		$query = "SELECT userId from loggedUsers where sessionId = '$sid' and
			  unix_timestamp(date_add(lastAccess, interval 1 hour)) > unix_timestamp(now())";
	}
	else
	{
		$query = "SELECT userId from loggedUsers where sessionId = '$sid' and
			  round(date_part('epoch',lastAccess + interval '1 hour')) >
			  round(date_part('epoch',now()))";
	}

	$result = db_query($query);
	
	if($result)
	{
		$row = db_fetch_row($result);
		if($row)
		{	

			$query = "UPDATE loggedUsers set lastAccess=now() where userId = $row[0]";
			db_query($query);
			db_error_log();
		}
		return $row[0];
	}
	else
	{
		return 0;
	}
}

/**
 * Are you logged in as the administrator?
 * also updates the 'lastAccess' field in the logged users table.
 */
function is_admin($sid="")
{
	global $con, $db_type;
	
	if(!isset($sid) || $sid == '')
	{
		$sid = session_id();
	}
	clean_sessions();

	if($db_type == 'mysql')
	{
		$query = "SELECT a.userId,b.userStatus FROM loggedUsers a, users b
		      WHERE a.sessionId = '$sid' AND b.userStatus >= 2 AND
			  a.userId = b.userId AND 
			  unix_timestamp(date_add(lastAccess, interval 1 hour)) > unix_timestamp(now())";
	}
	else
	{
		$query = "SELECT a.userId,b.userStatus FROM loggedUsers a, users b
		      WHERE a.sessionId = '$sid' AND b.userStatus >= 2 AND
			  a.userId = b.userId AND 
			  round(date_part('epoch',lastAccess + interval '1 hour')) >
			  round(date_part('epoch',now()))";
	}

	
	$result = db_query($query);

	if($result)
	{
		$row = db_fetch_row($result);
		if($row[1]>1)
		{
			$query = "UPDATE loggedUsers set lastAccess=now() where userId = $row[0]";
			db_query($query);
			return $row[1];
		}
	}
	return 0;
}


/**
 * retrieves the uers's status. Currently supported values are
 * 0 - disabled.
 * 1 - enable.
 * 2 - admin.
 */
 
function get_user_status($userId)
{
	$query = "SELECT userStatus from users where userId = $userId";
	$result = db_query($query);
	if($result)
	{
		$row = db_fetch_row($result);
		return $row[0];
	}
	return 0;
}

/**
 * retrieves the email address given the username, used mainly by the
 * password reminder service.
 */
function get_email($username, $userId=0)
{
	if($userId==0)
	{
		$query = "SELECT a.userEmail from userProfile a, users b
				where b.username='$username' and b.userId = a.userId";
	}
	else
	{
		$query = "SELECT userEmail from userProfile	where 
				 userId = $userId";	
	}
		error_log($query);	  
	$result = db_query($query);
	
	
	if(db_error_log == 0)
	{
		if($result)
		{
			$row=db_fetch_row($result);
			return $row[0];
		}
		else
		{

			return 0;
			
		}
	}
	else
	{

		return 0;
	}
}

/**
 * returns an instance of UserProfile for the member whose userId
 * is passed in as a parameter.
 */
function get_profile($userId)
{

	$query = "SELECT * from userProfile where userId = $userId";
	$result = db_query($query);
	
	if($result)
	{

		$row = array_change_key_case(db_fetch_array($result));

		$profile = new UserProfile;
		$profile->id = $row['userid'];
		$profile->firstName = $row['userfirstname'];
		$profile->lastName = $row['userlastname'];
		$profile->email = $row['useremail'];
		$profile->addr1 = $row['useraddr1'];
		$profile->addr2 = $row['useraddr2'];
		$profile->city = $row['usercity'];
		$profile->state = $row['userstate'];
		$profile->country = $row['usercountry'];
		$profile->tel = $row['usertel']	;
		$profile->mobiTel = $row['usermobitel']	;
		$profile->homeTel = $row['userhometel']	;
		$profile->web = $row['userweb']	;
		
		$profile->fax = $row['userfax'];
		$profile->key = $row['uservalidationkey'];
		$profile->IP = $row['userip'];
		$profile->signUp = $row['usersignup'];
		$profile->validated = $row['uservalidated'];
		$profile->newsLetter = $row['usernewsletter'];
		$porfile->zip = $row['userzip'];

		/*
		 * this can be optimized so kill me
		 */
		$query = "SELECT userName FROM users WHERE userId = $userId";
		$result = db_query($query);
		$row = db_fetch_row($result);
		
		$profile->userName=$row[0];


		return $profile;
	}
}


/**
 * displays the box that allows the user to view/change his
 * profile
 */
function show_profile($userId)
{
	
	$profile = get_profile($userId);
	 
	require_once('profile.txt');
}

/**
 * called in when the user submits the change profile form
 */
function change_profile($profile)
{
	$query = sprintf("UPDATE userProfile SET userFirstName='%s',
				userLastName='%s', userAddr1 = '%s', userAddr2 = '%s',
				userEmail = '%s', userTel = '%s', userFax = '%s',
				userWeb = '%s', userMobiTel = '%s', userHomeTel = '%s',
				userZip = '%s',	userCountry = '%s', userState = '%s',
				userCity= '%s'	WHERE userId = %s",
				
				$profile->firstName, $profile->lastName,
				$profile->addr1, $profile->addr2, $profile->email,
				$profile->tel, $profile->fax, $profile->web,
				$profile->mobiTel, $profile->homeTel,
				$profile->zip,$profile->country,
				$profile->state,$profile->city,$profile->id);
				
	$result = db_query($query);
	
	return db_error_log();
}
	
/**
 * subscribe/unsubscribe from newsletters
 */			
function change_newsletter($userId,$setting)
{
	global $con;
	$val = 0;
	if($setting == 'yes')
	{
		$val=1;
	}
	
	$query = "UPDATE userProfile set userNewsLetter=$val where userId=$userId";
	
	db_query($query);
	return db_error_log();
}

/**
 * changes the password for the given user
 */
	
function change_password($userId,$password)
{
	global $con,$user_password_function;
	$password = addslashes($password);
	if($user_password_function == 1)
	{
		$query = "UPDATE users set userPassword= password('$password') WHERE userId=$userId";
	}
	else
	{
		$query = "UPDATE users set userPassword= md5('$password') WHERE userId=$userId";
	}
	$result = db_query($query);

	return db_error_log();
}

/**
 * returns true if the username and password, and password confirm fields
 * are set. And the username field does not contain the '/' or '\' chars.
 */
function is_valid_username()
{
	$pass = sanitize_variable($_REQUEST['password']);
	$pass1 = sanitize_variable($_REQUEST['password1']);
	$user = sanitize_variable($_REQUEST['username']);
	
	return (isset($pass) && $pass != '' &&
			isset($pass1) && $pass1 != '' &&
			isset($user) && $user != '' &&
			strpos($user,'/') ===false &&
			strpos($user,"\\") ===false);
}


/**
 * finds the userId when the userName is known.
 */
function get_user_id($user)
{
	$user = addslashes($user);
	$query = "SELECT userId from users WHERE userName='$user'";
	$result = db_query($query);
	if($result && db_num_rows($result) != 0)
	{
		$row = db_fetch_row($result);
		return $row[0];
	}
	else
	{
		return -1;
	}
}


/**
 * this method changes the user status. The acceptable values are
 * 0 - disable account
 * 1 - enable account
 * 2 - mark as admin
 */
 
function set_user_status($userId, $status)
{
	$query = "UPDATE users set userStatus = $status WHERE userId = $userId";
	return db_query($query);
}
 

function sanitize_variable($var)
{
	return addslashes(trim(strip_tags($var)));
}

/**
 * hotmail, msn, bigfoot and other free addresses are not allowed.
 */
function is_valid_addr()
{
	$disallow = "/hotmail\.com|msn\.com|yahoo\.com|bigoot\.com|lycos\.com/";
	$email = sanitize_variable($_REQUEST['email']);
	if($email == '' || preg_match($disallow,$email))
	{
		return 0;
	}
	else
	{
		return 1;
	}
	
}


/**
 * returns userId on success. 0 on failure.
 * This method should be called when someone enters his username and pwd.
 */
function is_valid($user,$password)
{
	global $user_password_function;

	
	if($user_password_function == 1)
	{
		$query = "SELECT userId FROM users WHERE
			  userName = '$user' and userPassword = password('$password') and userStatus > 0";
	}
	else
	{
		$query = "SELECT userId FROM users WHERE
			  userName = '$user' and userPassword = md5('$password') and userStatus > 0";
	}			  
	$result = db_query($query);
	db_error_log();
	
	if($result && db_num_rows($result) ==1)
	{
		
		$row = db_fetch_row($result);

		return $row[0];
	}
	return 0;
}



/**
 * check the referer to minimize abuse..
 * todo: a more vigourous check.
 */
function is_valid_referer()
{ 
	global $site_url;
	 return (strstr($_SERVER['HTTP_REFERER'],$site_url));
}			


 
function on_session_start($save_path, $session_name) {
	error_log($session_name . " ". session_id());
}

function on_session_end() {
	// Nothing needs to be done in this function
	// since we used persistent connection.
}

function on_session_read($key) {
	global $db_type;

	
	$stmt = "select session_data from sessions ";
	$stmt .= "where session_id ='$key' ";

	if($db_type == 'mysql')
	{
		$stmt .= "and unix_timestamp(session_expiration) >
			  unix_timestamp(date_add(now(),interval 1 hour))";
	}
	else
	{
		$stmt .= "and round(date_part('epoch',session_expiration)) >
			  round(date_part('epoch',lastAccess + interval '1 hour'))";
	}
	
	$result = db_query($stmt);

	if($result)
	{
		$row = array_change_key_case(db_fetch_array($result));
		return($row['session_data']);
	}
	else
	{
		return $result;
	}
}

/**
 * The heart of the session manager.
 *
 * If you are load balancing your web site across several servers you cannot
 * store session information in files. You will either need to store the 
 * information in a database or use cookies. Since many people are reluctant
 * to trust cookies your choices narrow down to exactly one. YOu need to use
 * database.
 *
 * Storing session information in a database makes sense if you are on a 
 * shared hosting enviorenment and have concerns about security.
 *
 * To enabale this feature set the variable $session_in_db to 'db';
 */
function on_session_write($key, $val) {
	global $db_type;
	
	$val = addslashes($val);

	$insert_stmt  = "insert into sessions values('$key', ";
	if($db_type == 'mysql')
	{
		$insert_stmt .= "'$val',unix_timestamp(date_add(now(), interval 1 hour)))";
	}
	else
	{
		$insert_stmt .= "'$val',round(date_part('epoch',lastAccess + interval '1 hour')))";
	}

	$update_stmt  = "update sessions set session_data ='$val', ";
	if($db_type == 'mysql')
	{
		$update_stmt .= "session_expiration = unix_timestamp(date_add(now(), interval 1 hour))";
	}
	else
	{
		$update_stmt .= "session_expiration = round(date_part('epoch',lastAccess + interval '1 hour'))";
	}
	
	$update_stmt .= "where session_id ='$key '";
	
	// First we try to insert, if that doesn't succeed, it means
	// session is already in the table and we try to update
	
	
	db_query($insert_stmt);
	
	$err = db_error_log();
	
	if ($err != '')
	{
		db_query($update_stmt);
	}
}

function on_session_destroy($key) {
	db_query("delete from sessions where session_id = '$key'");
}

function on_session_gc($max_lifetime) 
{
	global $db_query;
	if($db_query == 'mysql')
	{
		db_query("delete from sessions where unix_timestamp(session_expiration)
				 < unix_timestamp(now())");
	}
	else
	{
		db_query("delete from sessions where round(date_part('epoch',session_expiration))
				< round(date_part('epoch',now()))");
	}
}
	
if(isset($session_save) && $session_save == 'db')
{    	    
	error_log('setting save handler');
	// Set the save handlers
	session_set_save_handler("on_session_start",   "on_session_end",
				"on_session_read",    "on_session_write",
				"on_session_destroy", "on_session_gc");
}

session_start();
?>
