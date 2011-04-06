<?php
require_once("Handler.php");

/** 
*	Abstract_page class
*
*	Using OOP abstraction method to define the pure minimum methods a standard page can have
*
*	This is to illustrate what must be used by the child classes. Example: News article.
*
*	This class does nothing more than to act as a parent class for a type of page. 
*	Allowing seperate child classes to choose what they wish to do with the abstract methods defined
*
*	@author Ashley Banks
*
*/

abstract class Abstract_page extends Handler  {
	
	/**
		*
		* Public properties
		* @var string title - title of a page
		* @var string image - a page image
		* @var string content - content
		*
	*/
    public $title,
		   $image,
		   $content;
	/**
		* @var array $_row - Associate array of a query held for child classes to grab.
		* @var $_row protected to can only be accessed within classes.
	*/
	protected $_row;
 	
 	public function __Construct($table){
		parent::__Construct($table);
	}
	
	// {{{ get_row()
	/**
		* Child class must have get_row to make a query result property
		* @param string $selector
	*/
	abstract public function get_row ($selector);
	// }}}
	// {{{ get_title()
	/**
		* get_title() is intended to get the title of a page
	*/
	abstract public function get_title ();
	// }}}
	// {{{ get_content()
	/**
		* get_content() is intended to get the content of a page
	*/
	abstract public function get_content ();
	// }}}
	// {{{ get_image()
	/**
		* get_image() is intended to get the image of a page
	*/
	abstract public function get_image ();
	// }}}
	// {{{ get_page_id()
	/**
		* get_page_id() is intended to get the id of a page. 
		* This is can be manipulated for various uses when adding functionality to a child class
	*/
	abstract public function get_page_id ();
	// }}}}
}


?>