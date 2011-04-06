<?php
#############################################################

/**
	* Class Auto_load: 
	*
	*	This class autoloads classes. This is used
	*	for the controller to automatically loads a class
	*	if it has not been included.
	*	@author Ashley Banks
	*	@license Ashley Banks
*/

class Auto_load {
	// {{{ __autoload()
	/**
		* 
		*	Checks if a class exists within the Model directory. If true it requires the relevent class.
		*	@param string - className: Name of the class to include
		*	@return true if file_exists false if does not exist
		*	@access public
		*   @author - Ashley Banks
	*/

	public function __autoload($className) { 
		  if (file_exists('Model/' . $className . '.php')) { 
			  require_once ('Model/' . $className . '.php'); 
			  return true; 
		  } 
		  return false; 
	} 
	// }}}
	//{{{ check_class_loaded()
	/*
		* 
		*	Checks to see if the class exists. Used for the front_end.
		*	If class does not exist on the page it runs __autoloads to require the class
		*	@author Ashley Banks
		*	@access public
		*	@param string className - a class name to check.
	
	*/
	public function check_class_loaded($className) { 
		  if(!class_exists($className)){
			  $this->__autoload($className);
		  }
	}
	// }}}
	
}


?>