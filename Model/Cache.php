<?php
require_once("Handler.php");
/**
*
* Class Cache
*
* Used for creating 'cached' pages into a new directory called cache for speed.
*
* Useful for caching pages that are not likely to be changed that often; NOT Search results.
*
*/

class Cache extends Handler {

	private $_cachedir = 'Cache/';
	
	private $_expire = 60;
	
	private static $_instance = NULL;
	
	
	/**
		*
		* Gets an instance of itself so class doesnt have to be inititated
		* @param string $cachedie - the directory cache files are held
		*
	*/
	public static function getInstance($cachedir = ''){
	
		if (self::$instance === NULL){
		
			self::$instance = new self($cachedir);
		
		}
		
		return self::$instance;
	
	}
	
	public function __construct($cachedir = ''){
	
		if ($cachedir !== ''){
		
			if (!is_dir($cachedir) or !is_writable($cachedir)){
		
				throw new Exception('Cache directory must be a valid writeable directory.');     
		
		}
	
		$this->_cachedir = $cachedir;
	
		}
	
	}
	
	/**
		*
		* Check that a cache file exists, if it does then prompt get() to get the cache file.
		* @param string $id - the identifier of the cache file
		*
	*/
	public function check_cache_file_exists($id){
		
		if(file_exists($this->_cachedir . $id)){
			$this->get($id);
		}
	}
	
	
	
	/**
		*
		* set() - Takes the timestamp from a given table and checks the date. 
		*		  If the table has been updated - create a new cache file
		*
		* @param string $id - The identifier of the cache file
		* @param string $data - The data to enter into the cache file. 
		* @param string $tablename - Table name to check the timestamp from
		*
	*/
	public function set($id, $data, $tablename){
		
		$row = $this->getResult($this->runQuery('SELECT date_time FROM ' . $tablename . ''));
		$date_time = $row['date_time'];
		
		$file = $this->_cachedir . $id;
		if(date("Y-m-d") > $date_time){
			if (file_exists($file)){
			
				unlink($file);
			
			}
		
			if (!file_put_contents($file, serialize($data))){
			
				throw new Exception('Error writing data to cache file.');
			
			}
		
		}
	
	}
	
	
	/**
		*
		* get() - Gets the cache file to display on the front-end
		*
		* @param string $id - The identifier to get the cache file to display.
		*
		* @return generates a storable representation of a value
		*
	*/
	public function get($id){
	
		$file = glob($this->_cachedir . $id);
		
		$file = array_shift($file);
		
		if (!$data = file_get_contents($file)){
		
			throw new Exception('Error reading data from cache file.');
		
		}
		
		return unserialize($data);
	
	}
	
	/**
		*
		* valid() checks to see if a cache file is valid
		*
		*
	*/
	public function valid($id){
	
		$file = glob($this->_cachedir . $id);
		
		$file = array_shift($file);
		
		return (bool)(time() - filemtime($file) <= $this->_expire);
	
	}

}

?>