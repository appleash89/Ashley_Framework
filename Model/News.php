<?php

require_once 'Abstract_page.php'; 

/**
	*
	* News class
	*
	* As news article is technically a page, it extends abstract page; so that is can make the methods
	* behave differently to Page class as this class is specifically for news articles
	*
	* @author Ashley Banks
	*
*/

class News extends Abstract_page {
	
	/**
		*
		* The below properties are unique to only the News class. 
		*
		* @var date $news_date - The date the news was posted
		* @var string $urltitle - This is the identifer that goes on the end of the query string.
		* @var string $attachments - This is the listing of attachments if a News article has them. Used to display in template.
		* @var time $news_time - The time the news was posted
		*
		* As this class extends Abstract_page it shares the other page properties of title, content and image.
	*/
	public $news_date,
		   $urltitle,
		   $attachments,
		   $news_time,
		   $show_boxes;
	
	/** 
		* @var int $_news_id - private to just this class.
		* Used to gain anything that uses news_id as a foreign key.
	*/   
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