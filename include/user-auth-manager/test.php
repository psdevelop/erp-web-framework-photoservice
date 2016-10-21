<?php
require_once (dirname(__FILE__) .  "/classes/UserAuthMgr.php");

$m = new UserAuthMgr();

// Register new user
echo "REGISTER user;<br>";
$user_info = array("credit_count"=>"211",
                   "fname"=>"Raymond",
                   "lname"=>"Jacobs",
                   "screen_name"=>"killa",
                   "dob"=>"11/27/1945");
// "register" a new user
$rv = $m->addUser('ian','cain','ian1@email.com','pass',$user_info);
if($rv < 0 ) {
	echo "duplicate user.\n";
}

// LOGIN user
echo "LOGIN user<br>\n";
$r = $m->userLogin('ian@email.com','pass');  // returns row object of user validated
if( ! $r ) {
	echo "user login failed<br>";
	exit;
}

// get UID of currently logged in user
echo "self=" . $m->self(). "\n";

// get all user information for currently logged in user.
$r = $m->getUserInfo();
echo "$r->uid,$r->fname,$r->lname,$r->email,$r->password,$r->credit_count,$r->screen_name,$r->dob<br>\n";

$rv = $m->protectPage();
if( $rv ) {
	echo "user logged in<br>\n";
}
else {
	echo "user NOT logged in<br>\n";
}

// =============================================
// create a new class extending UserAuthMgr

class MyNewClass extends UserAuthMgr {
	function myFunction() {
		echo "MyNewClass::myFunction() called<br>\n";
	}

	function __construct($db) {
        echo "I'm alive!<br>";
	}
}

// now through the magic of inheritance you have all the functions UserAuthMgr
$mnc = new MyNewClass();

// Register new user
echo "REGISTER user;<br>";
$user_info = array("credit_count"=>"211",
                   "fname"=>"Raymond",
                   "lname"=>"Jacobs",
                   "screen_name"=>"killa",
                   "dob"=>"11/27/1945");
// "register" a new user
$rv = $mnc->addUser('ian','cain','ian1@email.com','pass',$user_info);
if($rv < 0 ) {
	echo "duplicate user.\n";
}

// LOGIN user
echo "LOGIN user<br>\n";
$r = $mnc->userLogin('ian@email.com','pass');  // returns row object of user validated
if( ! $r ) {
	echo "user login failed<br>";
	exit;
}

// get UID of currently logged in user
echo "self=" . $mnc->self(). "\n";

// get all user information for currently logged in user.
$r = $mnc->getUserInfo();
echo "$r->uid,$r->fname,$r->lname,$r->email,$r->password,$r->credit_count,$r->screen_name,$r->dob<br>\n";

$rv = $mnc->protectPage();
if( $rv ) {
	echo "user logged in<br>\n";
}
else {
	echo "user NOT logged in<br>\n";
}

?>