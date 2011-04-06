<?php

require_once('Handler.php');

/**
*	Class Validater
*	
*	This class is for validating any type of form
*	There is an array of different validation methods all which are public
*	And can be accessed outside of this class on the front-end
*
*	Very useful and can easily build forms! Extend this to any class.
*
*	@author Ashley Banks
*
*/

class Validater extends Handler {
	/**
		*
		* Public properties that each function uses
		* @var boolean $work - set to true if there is not errors set on validation
		* @var string $error - used to echo out the collected errors for the front-end
		* @var boolean debug - checks to see if fields exists
		* @var string arrayDelimete - using for seperating an array. Default = comma
		* @var string inValidMarkup - displays the invalid mark up for isMissing();
		*
	*/
	public $work = true,
		   $error = '',
		   $debug = false,
		   $arrayDelimeter = ',',
		   $invalidMarkup = '<span class="error">*</span>';
	
	/**
		* Private property only to be accessed by this class. 
		* @var array $missing - Collects any fields validated that have caused an error. isMissing() uses this property to determine
		* what is missing.
	*/  
	private	$_missing = array();
	//{{{ checkStringLength()
	/**
		* @param string $string - The string to compare the length
		* @param int $min - The minimum number to check
		* @param int $max = Default NULL, can set a maximum length to check
		*
		* @return public error message
	*/	  
	public function checkStringLength($string, $min, $max = NULL){
		if(strlen($string) < $min || strlen($string) > $max) {
			$this->error .= '<p class="msg-error"><span>*</span>Field should be between ' . $min . ' and ' . $max . ' characters</p>';
			$this->work = false;
		}
		return $this;
	}
	// }}}
	// {{{ checkFieldExists()
	/**
		*
		* @param array $fields - Can take in an array of the $_POST name eg. 'firstname'. Take in associative array 'Please provide firstname' => 'name'
		* @param boolean $errors - Not entirely sure what this does.
		*	
		* @return - public property error; the missing array
		*	
	*/
	public function checkFieldExists($fields, $errors = true){
		if(!is_array($fields)){
			$fields = explode($this->arrayDelimeter,$fields);
		}
		foreach($fields as $key => $field){
			if($_POST[$field] == ''){
				if(is_string($key) && $errors){
					$this->error .= '<p class="msg-error"><span>*</span>' . $key . '.</p>';
				}elseif(!is_string($key) && $errors){
					$this->error .= '<p class="msg-error"><span>*</span>Please provide your ' . $field . '.</p>';
				}
				array_push($this->_missing,$field);
				$missingField = $field;
				$this->work = false;
			}
		}
		if($this->debug && !$this->work){
			die($missingField.' is missing');	
		}
		
		return $this;
	}
	// }}}
	// {{{ isNumericalRegEx()
	/**
		*
		* Regular Expression method to check is a numerical value
		* @param string $field - the string of the field to check
		* @return public error property
		*
	*/
	public function isNumericalRegEx($field){
		if(!ereg("^[0-9]{3}-[0-9]{3}-[0-9]{4}$", $_POST[$field])) {
			$this->error .= '<p class="msg-error"><span>*</span>' . ucwords($field) . ' must be a numerical field.</p>';
			array_push($this->_missing,$field);
		} 
		return $this;
	}
	// }}}
	// {{{ isNumeric()
	/**
		*
		* Uses PHP built-in function is_numeric to check a numerical value
		* @param string $field  - takes the name of the form field
		* @return error message
		*
	*/
	public function isNumeric($field){
		if(!is_numeric($_POST[$field])) {
			$this->error .= '<p class="msg-error"><span>*</span>' . ucwords($field) . ' must be a numerical field.</p>';
			array_push($this->_missing,$field);
		} 
		return $this;
	}
	// }}}
	// {{{ validURL()
	/**
		*
		* Checks whether a given URL is valid - Regex to check
		* @param string $field - the form field name only
		* @return the error
		*
	*/
	public function validURL($field){
		if(!preg_match("#^http://www\.[a-z0-9-_.]+\.[a-z]{2,4}$#i",$field)) {
			$this->error .= '<p class="msg-error"><span>*</span>URL is not valid format.</p>';
			array_push($this->_missing,$field);
		}
		return $this;
	}
	// }}}
	// {{{ validateEmail()
	/**
		*
		* Validates an email address using PHP built in function
		* @param $string the form field name to check
		* @return the error
		*
	*/
	public function validateEmail($string){
		if(filter_var($_POST[$string], FILTER_VALIDATE_EMAIL) == false){
			$this->error .= '<p class="msg-error"><span>*</span>Email Address is not a valid format.</p>';
			$this->work = false;
			array_push($this->_missing,$string);
		}
		return $this;
	}
	// }}}
	// {{{ existingField()
	/**
		*
		* Checks whether a given field is already taken in the database.
		* @param string $table - given table to check
		* @param string column - given column to check
		* @param string $value - the value to check exists
		* 
		* @return error if value exists
		*
	*/
	public function existingField($table, $column, $value){
		$this->runQuery('SELECT pkID FROM ' . $table . ' WHERE ' . $column . '="' . $value . '"');
		$this->numRows();
		if($this->rowCount != 0){
			$this->error .= '<p class="msg-error"><span>*</span>' . ucwords($value) . ' is currently in use. Please use a different '.$column.' </p>';
			$this->work = false;
		}
		return $this;
	}
	// }}}
	// {{{ existingFieldUser()
	/**
		*
		* Checks whether a field exists - but is not equal to the given ID. This is used for editing user accounts etc.
		* @param string $table - given table to check
		* @param string column - given column to check
		* @param string $value - the value to check exists
		* @param mixed (int / string) $id - the id to check against
		* 
		* @return error
		*
	*/
	public function existingFieldUser($table, $column, $value, $id){
		$this->runQuery('SELECT pkID FROM ' . $table . ' WHERE ' . $column . '="' . $value . '" AND pkID != "' . $id . '"');
		$this->numRows();
		if($this->rowCount != 0){
			$this->error .= '<p class="msg-error"><span>*</span>' . ucwords($value) . ' is currently in use. Please use a different '.$column.' </p>';
			$this->work = false;
		}
		return $this;
	}
	// }}}
	// {{{ current_password()
	/**
		*
		* Checks whether a password provided matches that of the database. Used for forms needing to change a password.
		* @access public
		* @param string $table - given table to check
		* @param string column - given column to check
		* @param string $value - the value to check exists - converts to an MD5 password to variable $password
		* @param mixed (int / string) $id - ID to check against
		*	
		* @return string error
		*
	*/
	public function current_password($table, $column, $value, $id){
		$password = md5($value);
		$this->runQuery('SELECT pkID FROM ' . $table . ' WHERE ' . $column . '="' . $password . '" AND pkID = "' . $id . '"');
		$this->numRows();
		if($this->rowCount == 0){
			$this->error .= '<p class="msg-error"><span>*</span>Current password is not correct: ' . $value . '.</p>';
			$this->work = false;
		}
		return $this;
	}
	// }}}
	// {{{ comparePassword()
	/**
		*
		* Compare two fields - Used for registration forms etc.
		* @param string $field - the first field to validate
		* @param string $field2 - the second field to match
		* @param string $message - the message to display back ie. 'Passwords' would display 'Passwords do not match'
		*
		* @return error
	*/
	public function comparePassword($field, $field2, $message){
		if($field != $field2){
			$this->error .= '<p class="msg-error"><span>*</span>' . $message . ' do not match.</p>';
			$this->work = false;
		}
		return $this;
	}
	// }}}
	// {{{ isMissing()
	/**
		*
		* Checks whether a field name is in the missing array. Can use on the front end to show specific error messages
		* @param string $field - the field to check in the array.
		* @echo - invalidMarkup - This is a standard *. But can be altered in the properties at the top.
		*
	*/
	public function isMissing($field){
		if(in_array($field,$this->_missing)){ 
			echo $this->invalidMarkup; 
		}
	}
	// }}}
	
}


?>