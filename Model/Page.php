<?php 

require_once("Abstract_page.php"); 

/**
	*
	* Page class
	*
	* This page is defined by the class it extends, which is an abstract page.
	* 
	* The characteristics of the page class are id, title, content and image.
	* Each of the methods are public and can pick and choose which to use in the controller.
	*
	* @author Ashley Banks
	*
*/

class Page extends Abstract_page {
	
	
	public $page_id;
	
	/**
		get_row:
			Inherited from Abstract_page. Runs a query and sets the property in abstract_page @_row the array for other methods to grab.
			@_row can only be accessed by other extended classes. Protected property.
			@param string $selector - select the page details by the urltitle
			@return associative array of results
	
	*/
	public function get_row ($selector) {
		$this->runQuery('SELECT pkID, imgname, title,section, content FROM pages WHERE urltitle = "' . $selector . '"');
		$this->_row = $this->getResult();
		return $this;
	}
	
	
	/**
		Getter methods for getting the page properties.
	*/
	public function get_page_id () {
		$this->page_id = $this->_row['pkID'];
		return $this;
	}
	
	public function get_title () {
		$this->title = $this->_row['title'];
		return $this;
		
	}
	
	public function get_section () {
		$this->section = $this->_row['section'];
		return $this;
		
	}
	     
	public function get_content () {
		$this->content = $this->_row['content'];
		return $this;
	}
	
	public function get_image () {
		$this->image = '<img src="/Assets/uploads/' . $this->_row['imgname'] . '">';
		return $this;
	}
}

?>