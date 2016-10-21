<?php
// *
// Author: Ian Cain, 2010, ian.colorado@gmail.com
//  Copyright (c) 2008 The NetBSD Foundation, Inc.
//  All rights reserved.
// 
//  This code is derived from software contributed to The NetBSD Foundation
//  by 
// 
//  Redistribution and use in source and binary forms, with or without
//  modification, are permitted provided that the following conditions
//  are met:
//  1. Redistributions of source code must retain the above copyright
//     notice, this list of conditions and the following disclaimer.
//  2. Redistributions in binary form must reproduce the above copyright
//     notice, this list of conditions and the following disclaimer in the
//     documentation and/or other materials provided with the distribution.
// 
//  THIS SOFTWARE IS PROVIDED BY THE NETBSD FOUNDATION, INC. AND CONTRIBUTORS
//  ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
//  TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
//  PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE FOUNDATION OR CONTRIBUTORS
//  BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
//  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
//  SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
//  INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
//  CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
//  ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
//  POSSIBILITY OF SUCH DAMAGE.
// *

// ----------------------------------------------------------------------------
// UserAuthManager provides API required to manage, 
// validate and administer user accounts
//

require_once (dirname(__FILE__) .  "/../inc/config.php");
require_once (dirname(__FILE__) .  "/UserAuthMgrConfig.php");
require_once (dirname(__FILE__) .  "/UserInfo.php");  // custom user info
require_once (dirname(__FILE__) .  "/DBConnector.php");

class UserAuthMgr extends UserInfo
{
    private $m_class_name = "UserAuthMgr";
    protected $db;
    protected $uid;
	private $user_login_state = false;
	private $user_tbl;
	private $user_info_tbl;
    
	// --------------------------------------
    // PUBLIC
	function self() {
		if( ! $this->getLoginState() ) { return false; }
		return $this->uid;
	}
    
	// main methods to protect pages
	function protectPage() {
		if( isset($_SESSION['login_state']) ) {
			if($_SESSION['login_state'] == "VALID") {return true;}
		}
		return false;
	}
	
	// check to see if user exists in DB
	// return: true/false
 	function userCheck($email) {
 		//echo " UserAuthMgr::userCheck() called.<br>\n";
		$query = "select email from $this->user_tbl where email='$email'";
		$r = $this->db->sql($query);
		//echo "$query<br>\n";
		
		if( $r->num_rows ) { return true; }
		return false;
	}

	// returns DB results obj.  obj->uid = UID of user
 	function userLogin($email,$pass) {
 		echo " UserAuthMgr::userLogin() called.<br>\n";
		$query = "select * from $this->user_tbl where email='$email' AND password='$pass'";
		$r = $this->db->sql($query);
		echo "$query<br>\n";
		
		if( $r->num_rows ) {
			$obj = $r->fetch_object();    // return result obj so, $r-><field_name> is valid
			$this->setLoginState(true,$obj->uid);
			return $obj; 
		}
		return false;
	}
	
	function userlogout() {
		$this->setLoginState(false,-99);
		return true;
	}
	
	protected function getLoginState() { return $this->user_login_state; }
	
	// add user to authentication table and pass on specific/custom user information
	//   to UserInfo class.
	// note: $user_info is an array of custom user info passed blindly onto the UserInfo class
	//
	// return: DB object.  $obj->uid = UID
	//
	function addUser($fname,$lname,$email,$pass,$user_info) {
		// add user to auth table
		$query = "insert into $this->user_tbl 
		          values (Null,'$fname','$lname','$email','$pass');";
		//echo "q='$query'<br>\n";
		$r = $this->db->sql($query);
		if( $r === -1 ) {
			return $r;  // regular mysql error
		}

		// get UID of newly added user
		$query = "select uid from $this->user_tbl where email='$email'";
		//echo "q='$query'<br>\n";
		$r = $this->db->sql($query);
		$rec = $r->fetch_object();

		// call UserInfo class to add custom user information connected by UID
		$rv = parent::addUserInfo($rec->uid, $user_info);  // return false if failed
		if( $rv < 0 ) {
			// user info failed.  unroll auth table record
			$query = "delete from $this->user_tbl where uid=$rec->uid;";
			$r = $this->db->sql($query);
		}
		
		return $rv;
	}

	// get user specific data in seperate table
	function getUserInfo() {
		if( ! $this->user_login_state ) { return false; }
		
		$uid = $this->uid;
		$query = "select * from $this->user_tbl LEFT JOIN ($this->user_info_tbl) 
		           ON ($this->user_tbl.uid = $this->user_info_tbl.uid) 
		           WHERE $this->user_tbl.uid=$uid";
		//echo "$query<br>\n";
		$r = $this->db->sql($query);
		
		if( $r->num_rows ) {
			return $r->fetch_object();  // return data oject
		}
		return false;
	}
	
	// thin wrapper to UserInfo class method to auto select UID
	// Note: read:modify:write method to update
	// read:  call getUserInfo()
	// modify: modify data as needed and pack array with new user data
	// write: call this method
	function updateUserInfo($uid, $user_info) {
		if( ! $this->user_login_state ) { return false; }

		$rv = parent::updateUserInfo($this->uid, $user_info);
		return $rv;		
	}
	
	// --------------------------------------
	// PRIVATE
	private function setLoginState($login_state,$uid) {
		if( $login_state ) {
			$_SESSION['login_state'] = "VALID";
			$_SESSION['uid'] = $uid;
			$this->uid = $uid;
			$this->user_login_state = true;
		}
		else {
			$_SESSION['login_state'] = "INVALID";
			$_SESSION['uid'] = -99;
			$this->uid = -99;
			$this->user_login_state = false;
		}
	}

	function __construct() {
		session_start();
		//echo ":: '$this->m_class_name' constructor<br>\n";

    	// grab all tables from global config.php file
    	$this->user_tbl=$GLOBALS['user_table'];
    	$this->user_info_tbl=$GLOBALS['user_info_table'];  // optional additional user info table

    	// set login states for this object using $_SESSION state    	
    	$this->user_login_state = false;
    	$this->uid = -99;

    	if( $this->protectPage() ) { 
    		$this->user_login_state = true; 
    		$this->uid = $_SESSION['uid'];
    	}
    	
        $this->db = DBConnector::connect();  //connect to DB singleton
		parent::__construct($this->db);  // call UserInfo class constructor
	}
}
?>