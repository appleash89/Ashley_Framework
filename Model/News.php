<?php

require_once 'Abstract_page.php'; 

/** Class to handle the news article attributes. Extends Abstract class as they have common properties to a page. */

class News extends Abstract_page {
	
	/* Assign the class variables */
	
	public $news_date,
		   $urltitle,
		   $attachments,
		   $news_time,
		   $show_boxes;
		   
	private $_news_id;
	  
	protected $_categories;
	
	public function __Construct($table){
		parent::__Construct($table);
	}
	
	/* Class Methods */
		   
	public function get_row ($selector){
			
		$this->_runQuery('SELECT * FROM news WHERE urltitle = "' . $selector . '"');
		$this->row = $this->getResult();
		return $this;
		
	}
	
	public function get_page_id () {
		$this->news_id = $this->_row['pkID'];
		return $this;
	}
	
	public function get_title () {
		$this->title = $this->_row['title'];
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
	
	public function get_date () {
		$this->news_date = date("d F Y", strtotime($this->_row['date']));
		return $this;
	}
	
	public function get_time(){
		$this->news_time = date("H:m", strtotime($this->_row['time']));
	}
	
	public function get_news_categories () {
		$this->categories = $this->_row['categories'];
		return $this;
	}
	
	public function listAttachments(){
		$this->get_page_id();
		$this->runQuery('SELECT * FROM attachments WHERE postID = "' . $this->_news_id . '"');
		while($row = $this->getResult()){
			
			
			if($row['type'] == 'x'){
				$class .= 'exc';
			}elseif($row['type'] == 'p'){
				$class .= 'pdf';
			}elseif($row['type'] == 'd'){
				$class .= 'word';
			}
			
			$this->attachments .= '<dl class="download">';
			$this->attachments .= '<dt class="pdf"><span></span>' . $row['title'] . '</dt>';
			$this->attachments .= '<dd><a href="Assets/php/download.php?document=' . urlencode($row['name']) . '">click here to download&hellip;</a></dd>';
			$this->attachments .= '</ddl>';
			
		
		}
		return $this;
		
	}


}

?>