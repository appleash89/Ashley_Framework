<?php 

require_once("Abstract_page.php"); 

/** Page for getting the general attributes of a page layout, and storing them as properties to be assigned to the View page. */

class Page extends Abstract_page {
	
	
	public $page_id;
	
	/**
		get_row:
			Inherited from Abstract_page. Runs a query and sets the property in abstract_page @_row the array for other methods to grab.
			@_row can only be accessed by other extended classes. Protected property.
			@param $selector - select the page details by the urltitle
			@return @_row
	
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