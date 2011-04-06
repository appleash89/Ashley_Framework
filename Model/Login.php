<?php

require_once("Handler.php");

/***************************************************************
/															   /
/ Class Login: Used for controlling logins and logging out.    /		
/															   /
/ 															   /
/**************************************************************/

class Login extends Handler {
	
	
	public	$sessions = array('userID' => 'pkID','level' => 'level'),
			$alert,
			$login,
			$logged_in = false,
			$name;
	
	private $_username,
			$_password;
			
	/**
		@Construct - Constructor takes @param table. @login needs a table to be set to run.
	*/
			
	public function __Construct($table){
		parent::__Construct($table);
		$this->username = $_POST['username'];
		$this->password = $_POST['password'];
	}
	
	/**
		Login:
			Controlling user log in.
			
			@param redirect - Default index.php redirect. Change on the front-end to whatever page to direct to if <-- failure -->
			@param level - The level to check. As front-end method the level would be 0 as default but this can be changed.
			
			@return alert - Message to display if the login fails
	
	*/
	
	public function login($redirect = 'index.php', $level = 0){
		
		// SQL Injection protect. Escape the form inputs
		$this->sqlProtect($this->username, 'username');
		$this->sqlProtect($this->password, 'password');
		
		// Assigning the SQL Query - using the @sessions
		$this->settings('cols',implode(',',$this->sessions));
		$this->settings('whereCols','email=,password=,level=');
		$this->settings('whereVals',array($this->protectedVal['username'],md5($this->protectedVal['password']),$level));
		$this->limit = 1;
		$this->select();
		$row = $this->getResult();
		$this->numRows();
		if($this->rowCount > 0){
			session_start();
			foreach($this->sessions as $key => $session){
				$_SESSION[$key] = $row[$session];
			}
			$this->logged_in = true;
			header("location:".$redirect);
		}else{
			$this->alert = '<p class="msg-error-login"><span>*</span>Invalid username/password</p>';
		}
		return $this;
	}
	
	/**
		get_username:
			Gets the column from a table where the pkID = the set $_SESSION
			@param column - the column to check in the table
			@param table - the table to check the column in.
			
			@return the @name
	
	*/
	
	public function get_username($colum, $table){
		$this->runQuery('SELECT ' . $column . ' FROM ' . $table . ' WHERE pkID = "' . $_SESSION['userID'] . '"');
		$row = $this->getResult();
		$this->name = $row['' . $column. ''];
		return $this;
		
	}
	
	/**
		logout:
			Logs the user out by destroying the session.
			@param redirect - choose the place the user should land once logged out.
	*/
	
	public function logout($redirect){
		session_start();
		session_destroy();
		header("location:".$redirect);
	}
	
	/**
		is_loggedin:
			Checks to see if a session is set by a user
			@return true is session is set
	*/
	public function is_loggedin () {
		if(!empty($_SESSION['userID'])){
			$this->logged_in = true;
		}
		return $this;
	}
	
	/**
		guardian_angel:
			Checks to see if a session is set. If not redirected
			@param redirect - choose where to redirect the attacker!!!
	
	*/
	public function guardian_angel($redirect){
		if(empty($_SESSION['userID'])){
			header('location: ' . $redirect. '');
		}
	}
	
}	
?>