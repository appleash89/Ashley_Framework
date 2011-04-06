<?php
require_once("stormValidate.php");

class Mail extends stormValidate {

	public $success = false,
		   $firstname,
		   $surname,
		   $email,
		   $home,
		   $home1,
		   $mobile,
		   $message,
		   $show_contact = false,
		   $show_boxes;
	
	private $_recipent;
	
	public function get_email_recipent(){
		$this->runQuery("SELECT email FROM emails WHERE pkID = '1'");
		$row = $this->getResult();
		$this->_recipent = $row['email'];
		return $this;
	}

	public function talk_to_us ($type) {
		
		$this->checkFieldExists(array("Please provide your firstname"=>'firstname',
									  "Please provide your surname" => 'surname',
									  "Please provide your email address" => 'email',
									  "You missed out 'How can we help you?'" => 'message'))
			->validateEmail('email');
		if($this->work){
			
			$this->get_email_recipent();
			
			$homephone = $_POST['home']  . ' ' . $_POST['home1'];
			if($type == "proff"){
				$message .= "<p>Healthcare Professional Enqury</p>";
			}
			$message = '<p><strong>Firstname: </strong>' . $_POST['firstname'] . '</p>';
			$message .= '<p><strong>Surname: </strong>' . $_POST['surname'] . '</p>';
			$message .= '<p><strong>Email: </strong>' . $_POST['email'] . '</p>';
			if(!empty($phone)){
				$message .= '<p><strong>Home Phone: </strong>' . $phone . '</p>';	
			}
			if(!empty($_POST['mobile'])){
				$message .= '<p><strong>Mobile Phone: </strong>' . $_POST['mobile'] . '</p>';	
			}
			if(!empty($_POST['message'])){
				$message .= '<p><strong>How can we help you?: </strong></p><p>' . $_POST['message'] . '</p>';	
			}
			if($type == "proff"){
				$subject = "Breastfeeding Professional: Talk To Us";
			}else{
				$subject = "Breastfeeding: Talk To Us";
			}
			$this->sendEmail($this->_recipent, $subject , $message, $_POST['email'], ucwords($_POST['firstname']) . ' ' . ucwords($_POST['surname']));
			$this->success = true;
		}else{
			$this->firstname = $_POST['firstname'];
			$this->surname = $_POST['surname'];
			$this->email = $_POST['email'];
			$this->home = $_POST['home'];
			$this->home1 = $_POST['home1'];
			$this->mobile = $_POST['mobile'];
			$this->message = $_POST['message'];
			
		}
		return $this;
		
	}
	
	protected function sendEmail($recipient, $subject, $message, $from_address, $from_name){
		$message = '<html><body>'.$message.'</body></html>';
		$headers =  'From: '.$from_name. "\r\n" .
					'Reply-To: '.$from_address. "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		mail($recipient, $subject, $message, $headers);
	}


}



?>