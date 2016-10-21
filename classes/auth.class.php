<?php

require_once("classes/tools.class.php");
require_once("classes/configuration.php");

class UserAuthentification extends Tools
{	
        protected $dbconnector;
    
	function __construct($dbconnector)
	{
		$this->dbconnector = $dbconnector;
	}
        
        function checkLogin()   {
            //$ip = $_SERVER['REMOTE_ADDR'];
            //$expiredate = date("Y-m-d H:i:s", strtotime("+1 month"));
            //!isset($_COOKIE["auth_session"])
            //$_SERVER['REMOTE_ADDR']
            $user_login = $this->dbconnector->db_login;
            $user_psw = $this->dbconnector->db_password;
            $this->dbconnector->db_login = $GLOBALS['dbuser'];
            $this->dbconnector->db_password = $GLOBALS['dbpsw'];
            if ($this->dbconnector->createConnection()!=null)   
            {
                //if (($user_login==$GLOBALS['dbuser'])&&
                // ($user_psw==$GLOBALS['dbpsw']))    {
                //    return true;
                //}  
                //else    {
                    $rows = $this->dbconnector->query_both_to_array("SELECT password FROM users WHERE (username='{$user_login}') and (isactive=1)");
                    $missing_user_msg = "!!!!!!!!!!Внимание нет указанного пользователя в таблице алминистрирования, возможна некорректная работа ряда операций!!!!!!!";
                    if ($rows!=null)    {
                        if (sizeof($rows)==1)    {
                            $missing_user_msg = null;
                            //if ($rows['password']==$user_psw)
                            if ($this->hashpass($user_psw)==$rows[0]['password'])    {
                                $cur_users = $this->dbconnector->query_both_to_array("SELECT person_id, person_type_id, 
                                        enable_admin, enable_deleting FROM users_with_relative WHERE (username='{$user_login}');");
                                //$this->writeUserInput($user_login);
                                if($cur_users!=null)
                                    if (sizeof($cur_users)==1)    {
                                        $cur_user = $cur_users[0];
                                        $_SESSION['current_user_id'] = $cur_user['person_id'];
                                        if ($cur_user['person_type_id']==$GLOBALS['operator_type_id'])
                                            $_SESSION['operator_id'] = $cur_user['person_id'];
                                        if ($cur_user['person_type_id']==$GLOBALS['manager_type_id'])
                                            $_SESSION['manager_id'] = $cur_user['person_id'];
                                        if ($cur_user['enable_admin']=='1')
                                            $_SESSION['enable_admin'] = true;
                                        else
                                            $_SESSION['enable_admin'] = false;
                                        if ($cur_user['enable_deleting']=='1')
                                            $_SESSION['enable_deleting'] = true;
                                        else
                                            $_SESSION['enable_deleting'] = false;
                                    }
                       
                                return true;
                            }
                                
                        }
                    }
                    //if ($missing_user_msg!=null)
                    //    echo $missing_user_msg;
                    return false;
                //}
            }
                    
            else
                return false;
        }
        
        function writeUserInput($username) {
            $this->dbconnector->exec_with_prepare_and_params("
                INSERT INTO sessions (uid, username, hash, expiredate, ip) 
                VALUES (0 ,:user_name, :hash, NOW(), :ip);", 
                array(":user_name"=>$username, ":hash"=>"---", ":ip"=>$_SERVER['REMOTE_ADDR']));
        }
        
        function writeAttempts() {
            $this->dbconnector->exec_with_prepare_and_params("
                call `add_attempt` (:ip);", 
                array(":ip"=>$_SERVER['REMOTE_ADDR']));
        }
        
        function writeLoginForm()   {
            echo "<br/><br/><div style=\"text-align:center;\"><center><table class=\"login_form\" border=\"0\" width=\"200\">
                <tr><td><form action=\"index.php?action=login\" method=\"post\"><center>
                Логин:<input id=\"login\" name=\"login\" type=\"TEXT\" value=\"root\" /><br/>
                Пароль:<input id=\"psw\" name=\"psw\" type=\"PASSWORD\" value=\"123456\" /><br/>
                <input type=\"Submit\" value=\"Войти\"></center></form></td></tr></table></center></div>";
        }
	
	/*
	* Log user in via MySQL Database
	* @param string $username
	* @param string $password
	* @return boolean
	
        function login($username, $password)
	{
		if(!isset($_COOKIE["auth_session"]))
		{
			$attcount = $this->getattempt($_SERVER['REMOTE_ADDR']);
			
			if($attcount >= 5)
			{
				$this->errormsg[] = "You have been temporarily locked out !";
				$this->errormsg[] = "Please wait 30 minutes.";
				
				return false;
			}
			else 
			{
				// Input verification :
			
				if(strlen($username) == 0) { $this->errormsg[] = "Username / Password is invalid !"; return false; }
				elseif(strlen($username) > 30) { $this->errormsg[] = "Username / Password is invalid !"; return false; }
				elseif(strlen($username) < 3) { $this->errormsg[] = "Username / Password is invalid !"; return false; }
				elseif(strlen($password) == 0) { $this->errormsg[] = "Username / Password is invalid !"; return false; }
				elseif(strlen($password) > 30) { $this->errormsg[] = "Username / Password is invalid !"; return false; }
				elseif(strlen($password) < 5) { $this->errormsg[] = "Username / Password is invalid !"; return false; }
				else 
				{
					// Input is valid
				
					$password = $this->hashpass($password);
				
					$query = $this->mysqli->prepare("SELECT isactive FROM users WHERE username = ? AND password = ?");
					$query->bind_param("ss", $username, $password);
					$query->bind_result($isactive);
					$query->execute();
					$query->store_result();
					$count = $query->num_rows;
					$query->fetch();
					$query->close();
				
					if($count == 0)
					{
						// Username and / or password are incorrect
					
						$this->errormsg[] = "Username / Password is incorrect !";
						
						$this->addattempt($_SERVER['REMOTE_ADDR']);
						
						$attcount = $attcount + 1;
						$remaincount = 5 - $attcount;
						
						$this->errormsg[] = "$remaincount attempts remaining.";
						
						return false;
					}
					else 
					{
						// Username and password are correct
						
						if($isactive == "0")
						{
							// Account is not activated
							
							$this->errormsg[] = "Account is not activated !";
							
							return false;
						}
						else
						{
							// Account is activated
						
							$this->newsession($username);				
					
							$this->successmsg[] = "You are now logged in !";
							
							return true;
						}
					}
				}
			}
		}
		else 
		{
			// User is already logged in
			
			$this->errormsg[] = "You are already logged in !";
			
			return false;
		}
	}*/
        
        /*
	* Hash user's password with SHA512 and base64_encode
	* @param string $password
	* @return string $password $currpass = $this->hashpass($currpass);
			$newpass = $this->hashpass($newpass);
	*/
	
	function hashpass($password)
	{
		//$password = hash("SHA512", base64_encode(hash("SHA512", $password)));
                $password = base64_encode($password);
		return $password;
	}
	
	/*
	* Register a new user into the database
	* @param string $username
	* @param string $password
	* @param string $verifypassword
	* @param string $email
	* @return boolean
	*/
	
	function register($username, $password, $verifypassword, $email)
	{
		if(!isset($_COOKIE["auth_session"]))
		{
			// Input Verification :
		
			if(strlen($username) == 0) { $this->errormsg[] = "Username field is empty !"; }
			elseif(strlen($username) > 30) { $this->errormsg[] = "Username is too long !"; }
			elseif(strlen($username) < 3) { $this->errormsg[] = "Username is too short !"; }
			if(strlen($password) == 0) { $this->errormsg[] = "Password field is empty !"; }
			elseif(strlen($password) > 30) { $this->errormsg[] = "Password is too long !"; }
			elseif(strlen($password) < 5) { $this->errormsg[] = "Password is too short !"; }
			elseif($password !== $verifypassword) { $this->errormsg[] = "Passwords don't match !"; }
			elseif(strstr($password, $username)) { $this->errormsg[] = "Password cannot contain the username !"; }
			if(strlen($email) == 0) { $this->errormsg[] = "Email field is empty !"; }
			elseif(strlen($email) > 100) { $this->errormsg[] = "Email is too long !"; }
			elseif(strlen($email) < 5) { $this->errormsg[] = "Email is too short !"; }
			elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) { $this->errormsg[] = "Email address is invalid !"; }
		
			if(count($this->errormsg) == 0)
			{
				// Input is valid
			
				$rows = $this->dbconnector->query_both_to_array("SELECT * FROM users WHERE username='{$username}'");
			
				if($rows != null)
				{
                                    if (sizeof($rows)>0)    {
					// Username already exists
				
					$this->errormsg[] = "Username is already taken !";
					
					return false;
                                    }
				}
				
                                $password = $this->hashpass($password);
                                $this->dbconnector->exec_with_prepare_and_params("
                                    INSERT INTO users (username, password, email, isactive) 
                                    VALUES (:user_name, :password, 'psdevelop@yandex.ru', 1);", 
                                    array(":user_name"=>$username, ":password"=>$password));
						
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			// User is logged in
		
			$this->errormsg[] = "You are currently logged in !";
			
			return false;
		}
	}
	
	/*
	* Creates a new session for the provided username and sets cookie
	* @param string $username
	
	
	function newsession($username)
	{
		$hash = md5(microtime());
		
		// Fetch User ID :		
		
		$query = $this->mysqli->prepare("SELECT id FROM users WHERE username=?");
		$query->bind_param("s", $username);
		$query->bind_result($uid);
		$query->execute();
		$query->fetch();
		$query->close();
		
		// Delete all previous sessions :
		
		$query = $this->mysqli->prepare("DELETE FROM sessions WHERE username=?");
		$query->bind_param("s", $username);
		$query->execute();
		$query->close();
		
		
		$expiretime = strtotime($expiredate);
		
		$query = $this->mysqli->prepare("INSERT INTO sessions (uid, username, hash, expiredate, ip) VALUES (?, ?, ?, ?, ?)");
		$query->bind_param("issss", $uid, $username, $hash, $expiredate, $ip);
		$query->execute();
		$query->close();
		
		setcookie("auth_session", $hash, $expiretime);
	}*/
	
	/*
	* Deletes the user's session based on hash
	* @param string $hash
	
	
	function deletesession($hash)
	{
		$query = $this->mysqli->prepare("SELECT username FROM sessions WHERE hash=?");
		$query->bind_param("s", $hash);
		$query->bind_result($username);
		$query->execute();
		$query->store_result();
		$count = $query->num_rows;
		$query->close();
		
		if($count == 0)
		{
			// Hash doesn't exist
		
			$this->errormsg[] = "Invalid Session Hash !";
			
			setcookie("auth_session", $hash, time() - 3600);
		}
		else 
		{
			// Hash exists, Delete all sessions for that username :
			
			$query = $this->mysqli->prepare("DELETE FROM sessions WHERE username=?");
			$query->bind_param("s", $username);
			$query->execute();
			$query->close();
			
			setcookie("auth_session", $hash, time() - 3600);
		}
	}*/
	
	/*
	* Provides an associative array of user info based on session hash
	* @param string $hash
	* @return array $session
	
	
	function sessioninfo($hash)
	{
		$query = $this->mysqli->prepare("SELECT uid, username, expiredate, ip FROM sessions WHERE hash=?");
		$query->bind_param("s", $hash);
		$query->bind_result($session['uid'], $session['username'], $session['expiredate'], $session['ip']);
		$query->execute();
		$query->store_result();
		$count = $query->num_rows;
		$query->fetch();
		$query->close();
		
		if($count == 0)
		{
			// Hash doesn't exist
		
			$this->errormsg[] = "Invalid Session Hash !";
			setcookie("auth_session", $hash, time() - 3600);
			
			return false;
		}
		else 
		{
			// Hash exists
		
			return $session;			
		}
	}
        */
	
	/* 
	* Checks if session is valid (Current IP = Stored IP + Current date < expire date)
	* @param string $hash
	* @return bool
	
	
	function checksession($hash)
	{
		$query = $this->mysqli->prepare("SELECT username, expiredate, ip FROM sessions WHERE hash=?");
		$query->bind_param("s", $hash);
		$query->bind_result($username, $db_expiredate, $db_ip);
		$query->execute();
		$query->store_result();
		$count = $query->num_rows;
		$query->fetch();
		$query->close();
		
		if($count == 0)
		{
			// Hash doesn't exist
			
			setcookie("auth_session", $hash, time() - 3600);
			
			return false;
		}
		else
		{
			if($_SERVER['REMOTE_ADDR'] != $db_ip)
			{
				// Hash exists, but IP has changed
			
				$query = $this->mysqli->prepare("DELETE FROM sessions WHERE username=?");
				$query->bind_param("s", $username);
				$query->execute();
				$query->close();
				
				setcookie("auth_session", $hash, time() - 3600);
				
				return false;
			}
			else 
			{
				$expiredate = strtotime($db_expiredate);
				$currentdate = strtotime(date("Y-m-d H:i:s"));
				
				if($currentdate > $expiredate)
				{
					// Hash exists, IP is the same, but session has expired
				
					$query = $this->mysqli->prepare("DELETE FROM sessions WHERE username=?");
					$query->bind_param("s", $username);
					$query->execute();
					$query->close();
					
					setcookie("auth_session", $hash, time() - 3600);
					
					return false;
				}
				else 
				{
					// Hash exists, IP is the same, date < expiry date
				
					return true;
				}
			}
		}
	}
	
	/*
	* Returns a random string, length can be modified
	* @param int $length
	* @return string $key
	
	
	function randomkey($length = 10)
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$key = "";
		
		for($i = 0; $i < $length; $i++)
		{
			$key .= $chars{rand(0, strlen($chars) - 1)};
		}
		
		return $key;
	}*/
	
}

?>