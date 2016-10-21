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

// include main configuration file which include the correct database
require_once (dirname(__FILE__) .  "/../inc/config.php");

// singleton
// call example
//  $db = DBConnector::connect();  //connect to DB singleton
//
//
class DBConnector
{
	private static $instance;
	private $m_class_name = "DBConnector";
	var $m_dbconn;
	var $m_connected;
    var $dbhost;
    var $dbname;
    var $dbuser;
    var $dbpass;
    var $dup;

    function sql($query) {
        if($this->m_connected == false) {return false;}
        //echo "sql:'$query'<br>\n";
        if ($r = $this->m_dbconn->query($query)) { return $r; }
        if(1062 == mysqli_errno($this->m_dbconn)) {	return -1; }
        
        echo "mysqli err: $this->dup" . mysqli_error($this->m_dbconn) . "<br>\n";
        return false;  // regular mysql error
    }

    function get_next_row($result) {
    	if($this->m_connected == false) {return false;}
    	 
    	//echo "fetch_object:<br>\n";
    	if( $obj = $result->fetch_object()) { return $obj; }
    	return false;
    }

    function fetch_object($result) {
    	if($this->m_connected == false) {return false;}
    	
        //echo "fetch_object:<br>\n";
        if( $obj = $result->fetch_object()) { return $obj; }
        return false;
    }
    
    // singleton method "connect:
    // called like,
    // $db = DBConnector::connect();
    // return single instance of DB connection
    
    public static function connect() {
        //echo ":: DBConnector::connect() singleton called.<br>\n";
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
    
    private function __construct() {
        //echo ":: '$this->m_class_name' constructor<br>\n";
    	// DB credentials
    	$this->dbhost=$GLOBALS['dbhost'];
    	$this->dbname=$GLOBALS['dbname'];
    	$this->dbuser=$GLOBALS['dbuser'];
    	$this->dbpass=$GLOBALS['dbpass'];
    	
    	// connect
    	$this->m_connected = false;
        $this->m_dbconn = new mysqli($this->dbhost,$this->dbuser,$this->dbpass,$this->dbname);
        if (mysqli_connect_errno()) {
        	printf("Connect failed: %s\n", mysqli_connect_error());
        	return;
        }
        $this->m_connected = true;   // fucking A
    }
    
    function __destruct() {
        //echo ":: '$this->m_class_name' destructor<br>\n";
        $this->m_dbconn->close();
        $this->m_connected = false;  // I'm out bitches
    }
    
    // Prevent users from cloning the instance
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
}

?>