<?php

require_once("Mail.php");

/**
* Class Forgotten_password
* 
* This class handles the forgotten password request
* Simply takes an email and sends the user a new randomly generated password
*
* Functions:
*		   - _createString()
*		   - forgottenPassword()
*
* @author Ashley Banks. @co-authour Iain Chambers
*/

class Forgotten_password extends Mail {
	
	/**
		* @var string $alert = Display the failure message for the front-end/
		* @var boolean $success = Set as true if the request has successfuly been made. Used ideally for hiding and displaying a total form for success messages
	*/
	public $alert,
		   $success;
	/**
		* @var string $_randomString = Randomly generated string used for password
	*/
	private $_randomString;
	
	// {{{ _createString()
	/**
		* @param int $len = DEFAULT 6, change to set the character length of the returned string
		* @return @var $_randomString
	*/
	protected function _createString( $len = 6 ){
		$the_alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for( $i = 0; $i < $len; $i++ ){
			$this->_randomString .= $the_alphabet[ rand(0, strlen( $the_alphabet ) - 1) ];
		}
		return $this;
	}
	// }}}
	// {{{ forgottenPassword()
	/**
		* @param string $email - the email to send to the new password to.
		* @return @var $success - returns true if it is sent otherwise false.
	*/
	public function forgottenPassword($email){
		
		$this->sqlProtect($_POST['email'], 'email');
		$status = true;
		if(!stristr($_POST['email'], "@")){ 
			$this->alert .= '<p class="msg-error">* Address entered is not a valid format.</p>';
			$status = false;
		}
		
		$this->runQuery('SELECT pkID, email FROM users WHERE email = "' . $this->protectedVal['email'] . '" LIMIT 1');
		$this->numRows();
		$row = $this->getResult();
		if($this->rowCount == 0){
			$this->alert .= '<p class="msg-error">* Email does not exist on our database.</p>';
			$status = false;
		}
		if($status){
			$password = $this->_createString();
			$password2 = md5($this->_randomString);
			$email = $row['email'];
			$this->runQuery("UPDATE users SET password = '$password2' WHERE email = '$email'");
			$to = $email;
			$subject = "";
			// Section for the email message. Change the subject and message accordingly
			$message = "A password request has been sent for this email address for Breastfeeding user account. Your new password is: $password . \n\n ";
			$this->sendEmail($to, "Subject", $message, 'Breastfeeding', "admin@breastfeeding.nhs.uk");
			$this->success = true;
			
		}
		else{
			$this->success = false;
		}
		return $this;
	}
	// }}}
	
	
	
	
}