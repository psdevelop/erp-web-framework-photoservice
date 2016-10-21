<?

/**
 * set to the servername or IP address of your mysql server
 */
$db_server = "localhost";

/**
 * postgres or mysql?
 */
$db_type = "mysql";

/**
 * set to the mysql account name.
 */
$db_user = "root";
 
/**
 * set the password for the mysql account given in $db_user
 */
$db_pass = "";


/**
 * If your database isn't called user_manager, change the line below.
 */
 
$db_name = "usermanager";


/**
 * When validate_email is set to one, users are sent an email with a
 * special 'account validation link'. Their account get's activated 
 * only if they click on that link.
 *
 * This step helps to ensure that visitors do not enter fake data 
 * at sign up.
 */
 
$validate_email = 1;

/**
 * Set to 1, if the system should send out a welcome email. If 
 * validate_email is set to 1, the system will send out an email 
 * asking the user to validate his account. In this case a separate
 * welcome email will not be sent.
 *
 * In other words $welcome_email takes effect only when $validate_email
 * is set to 0.
 */
$welcome_email = 1;


/**
 * site_name should be set to the name of your website.
 */
$site_name = "Rad Inks";

/** 
 * the url to the home page of your website.
 */
$site_url  = "http://{$_SERVER['SERVER_NAME']}/";
error_log($site_url);

/**
 * The following email address will be used in the from field for 
 * password reminder and user validation emails.
 */
 
 
 /**
 * don't change the next few lines
 */
if($db_type == "mysql")
{
	$con = mysql_connect($db_server, $db_user, $db_pass);
	mysql_select_db($db_name,$con);
}
else
{
	$pgString = "host=$db_server dbname=$db_name user=$db_user";
	if($db_pass != '')
	{
		$pgString .= " password=$db_pass";
	}
	$con = pg_connect($pgString);
	error_log('connecting to db with ' . $pgString);
	
}

$member_service_email = "members@yoursite.com";

/**
 * This is the default setting for the <title></title> tag of each
 * page in the members area. Can be customized at page level too.
 */
$msg_title  = "Become a member";	


/**
 * This is the prompt on the signup page.
 */
$msg_signup =	'<p>Please select a username for your account along with a password. Please also
	fill in your current email address.</p>';	

/**
 * this is the prompt on the password reminder page.
 */	
$msg_password_reminder ='If you have forgotten your password please enter your username below.
			We will reset your password and email it to the address in our records. If you have forgotten your
			username as well, you will need to create a <a href="signup.php">new account</a>';
	
/**
 * this message is displayed after the password is reset successfully.
 */					
$msg_pass_changed="Your password has been reset and the new password has been emailed to the
				address in our records, please login with the new password and change it immidiately.";			

				
/**
 * The message to be displayed when the user clicks on the account validation
 * link. (which is sent via email, at signup time).
 */				
$msg_validated ='<p>&nbsp;</p>
				<table border="0" width="80%" align="center">
 				 <tr><td><h3>Welcome to Rad Inks.</h3></td></tr>
				 <tr><td>Your account has been activated. You can now make full use of the
				 added benefits available to members of this website. 
				 Please <a href="login.php">click here</a> to login for the first time.</td></tr>
				</table>';								
				
//$session_save = 'db';

/**
 * set this to 1, if you want to use the password() function to encrypt the
 * user's passwords instead of the md5() function when using a mysql db.
 */
$user_password_function=0;	
?>
