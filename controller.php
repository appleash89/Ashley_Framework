<?php
/**
	* 
	* Controller
	*	
	* This controller handles the module requests by the URL
	* IF statements and Switch statements can be used to catch the module in the URL query string
	* and select which model to use and which template to define.
	*
	* @author Ashley Banks
	*
*/

// Assign the module to the controller so it can be used around the Controller.
$module = $_GET['module'];


//**** Require all the files that are to be used here *****\\

//automatically load classes. 
require_once("Model/Auto_load.php");

// Load the Cache class. As it has a self instance the class doesnt need to be initiated. But we will.
require_once("Model/Cache.php");

//*** Initiate the classes that are to be used ***\\\

$_auto_load = new Auto_load;
$_cache = new Cache;

switch($module){
	
	case "welcome":
	
		// Auto load example
		$_auto_load->check_class_loaded($classname);
		
		// Example of loading class with Mustache template
		$class = new Example;
		
		/* Assign a variable to the template before echoing it */
		$cache_me = $class->render($template);
		
		// Example of how to cache a file. Using $cache_me use this as the data to cache
		// This will only work if the cache file does not exist or needs to be updated
		$_cache->set('Home', $cache_me, $tablename);
		
		// Checks to see if a cache file exists otherwise echo the most recent.
		if($_cache->check_cache_file_exists('Home')){
			$_cache->check_cache_file_exists('Home');
		}else{
			echo $cache_me;
		}
		
		break;
	
}


?>