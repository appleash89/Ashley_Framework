<?php
require_once("something.php");

/***************************************************************
/															   /
/ Class Pagination: Used for paginating lists.				   /		
/			Should be used to extend a listing class		   /
/ 															   /
/**************************************************************/

class Pagination extends something {
	
	/****
		@page - Set the page to be $_GET['page'] on the front-end. Used to gather the current page.
		@pagination - the property of pagination to be displayed on the front-end lists
		@rows - Default 5, the limit on the amount of rows to display per page.
		
	*/

	public $page,
		   $pagination,
		   $rows = 5;
		   
	public function __Construct($table){
		parent::__Construct($table);
	}
	
	/**
		get_pagination:
			This method paginates a given SELECT query by another class. It limits the values to 5
			@param type - The variable to go to in the url
			
			@return pagingations. Set the property @pagination to the pagination to be outputed.
	
	*/
	
	public function get_pagination($type){
			
			$this->select();
			// Count the amount of rows the listing brings back.
			$this->numRows();
			// Check a page is even set.
			if($this->page == ''){
				$this->page = 1;
			}
			// Calculate the last page of results.
			$last_page = ceil($this->rowCount/$this->rows);
			// Assign how the next page will work.
			$next = $this->page + 1;
			// Assign how the previous page will work
			$previous = $this->page - 1;
			// Initiate a selected property to highlight the button on front-end.
			$selected = '';
			// Set the page number to display how many pages are being display. Can be turned off by commenting out.
			if($_GET['page'] == ''){
				$pageNo = 1;
			}else{
				$pageNo = $_GET['page'];
			}
			// Check to see if there is any results otherwise set @pagination to be empty
			if($this->rowCount == 0){
				$this->pagination = '';
			}else{
				$this->pagination = '<li>Page ' . $pageNo . ' of ' . $last_page . '</li>';
				if($last_page > 1) {
						$this->pagination .= '	<li><a href="' . $type . '/page/1" class="pag-1st">First</a></li>'."\n";
						
					if($this->page != 1){
						
						$this->pagination .= '	<li><a href="' . $type . '/page/'.$previous.'" class="pag-prev">Previous</a></li>'."\n";
						
					}
					// Set the max links to display ie. <<prev 1 2 3 4 5 next>>
                                       // So basically this prevents the paginations from going on forever and looking ugly
					$max_links = 5;
					for($i = 0; $i < $max_links; $i++){
						$newPage = $pageNo + $i;	
						if($newPage <= $last_page){
							// Checks if page is equal to set selected.
							if($newPage == $pageNo){
								$selected = 'selected'; 
							}else{ 
								$selected = '';
							}
							$this->pagination .= '<li><a href="' . $type . '/page/' . $newPage . '" class="' . $selected . '">' . $newPage . '</a></li>' . "\n"; 
						}
					}
						
					if($this->page != $last_page){
						$this->pagination .= '<li><a href="' . $type . '/page/'.$next.'" class="pag-nxt">Next</a></li>'."\n";
					}
					
				}
			}
			// returns the limit to assign to the query.
			$this->limit = ($this->page - 1) * $this->rows .',' .$this->rows;
			return $this;
		}
	
	}
	
	


?>