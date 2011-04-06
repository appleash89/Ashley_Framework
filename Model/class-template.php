<?php
//require a file
require_once("");

class class_name extends extend_class {
	
	/* Assign the properties of the class */
	
	// public property
	public $_public_property;
	
	// protected property
	protected $protected_property;
	
	// private property
	private $_private_proerty;
	
	
	// Initialise the class constructor
	public function __Construct(){
		
		parent :: __Construct();
		
		
	}
	
	/* Assign the class methods below */
	
	public function example_method(){
		return $this->_private_proerty;
	}
	
	
	
	
}


?>