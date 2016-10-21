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

//  mysql> describe user_info;
// +---------------+--------------+------+-----+---------+-------+
// | Field         | Type         | Null | Key | Default | Extra |
// +---------------+--------------+------+-----+---------+-------+
// | uid           | bigint(20)   | NO   | PRI | NULL    |       | 
// | fname         | varchar(100) | YES  |     | NULL    |       | 
// | lname         | varchar(100) | YES  |     | NULL    |       | 
// | email         | varchar(100) | YES  |     | NULL    |       | 
// | streetaddress | varchar(100) | YES  |     | NULL    |       | 
// | city          | varchar(100) | YES  |     | NULL    |       | 
// | state         | varchar(2)   | YES  |     | NULL    |       | 
// | zip           | varchar(5)   | YES  |     | NULL    |       | 
// | country       | varchar(100) | YES  |     | NULL    |       | 
// | phone         | varchar(12)  | YES  |     | NULL    |       | 
// | fax           | varchar(12)  | YES  |     | NULL    |       | 
// | activation    | TINYINT      | YES  |     | NULL    |       | 
// +---------------+--------------+------+-----+---------+-------+

// include main configuration file which include the correct database
require_once (dirname(__FILE__) .  "/../inc/config.php");

abstract class UserInfo
{
	private $db;
	private $user_info_tbl;
		
	function addUserInfo($uid,$user_info) {
#		echo "UserInfo::addUserInfo(uid=$uid) called<br>\n";
		
#		foreach ($user_info as $key => $val) {
#			echo "$key=$val<br>\n";
#		}
		
		// insert record into db
		$fname = $user_info['fname'];
		$lname = $user_info['lname'];
		$email = $user_info['email'];
		$streetaddress = $user_info['streetaddress'];
		$city = $user_info['city'];
		$state = $user_info['state'];
		$zip = $user_info['zip'];
		$country = $user_info['country'];
		$phone = $user_info['phone'];
		$fax = $user_info['fax'];
		$activation = $user_info['activation'];
		$activation_date = NULL;
		
		$q = "insert into $this->user_info_tbl values ($uid,'$fname','$lname','$email','$streetaddress','$city','$state','$zip','$country','$phone','$fax', 1,Null);";
#		echo "$q<br>\n";
		$rv = $this->db->sql($q);
		return $rv;
	}
	
	function activateUserAcct($uid){
		$sql = "SELECT activation FROM user_info WHERE uid=$uid;";
		$r = $this->db->sql($sql);
		if($r->num_rows){
			$row = $r->fetch_object();
			if($row->activation == 1){
				echo "<h1>Thank you</h1><p>Your account was previously activated. No further activation is required.</p><p>Please <a href=\"login.php\">log in</a> to start enjoying your benefits, right now.</p>";
				include('inc/pagebottom.php');
				exit;
			} else {
				$q = "UPDATE user_info SET activation=1, activation_date=NOW() WHERE uid=$uid;";
			//	echo $q;
			//	echo 'uid: ' . $uid . 'query: ' . $q;
				$rv = $this->db->sql($q);
				return $rv;
			}
		}
	}

	function getUserInfo() {
		echo "UserInfo::getUserInfo() called<br>\n";
	}

	function __construct($db) {
    	$this->user_info_tbl=$GLOBALS['user_info_table'];  // optional additional user info table
		
		$this->db = $db;  // DB connection
	}
}

?>