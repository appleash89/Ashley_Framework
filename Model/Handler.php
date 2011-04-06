<?php require_once 'Constants.php';
require_once 'Mustache.php';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//																																				//
// 	Handler:																																	//
//																																				//
// 	This class handles all direct interactions with the MySQL database. Extends Class Mustache for templating purposes						    //
//  The Master Class - Core of any applications																									//
//																																				//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

class Handler extends Mustache {
	
	private	$dbhost = dbhost,
			$dbuser = dbuser,
			$dbpassword = dbpassword,
			$dbname = dbname,
			$connect,
			$table,
			$where = array(),
			$valarray = array(),
			$colarray = array(),
			$set = array();
			
	protected 	$debug = false,
				$query,
				
				$protectedVal = array(),
				$cols = array(),
				$vals = array(),
				$whereCols = array(),
				$whereVals = array();
				
	public	$data,
			$rowCount,
			$limit,
			$order,
			$whereOperator = ' AND ',
			$whereClause,
			$arrayDelimiter = ',',
			$disabled = ' disabled="disabled" ',
			$editdisabled = '',
			$view;
			
	 public function __Construct($table){
		$this->dbConnect();
		$this->dbSelect();
		$this->table = $table;
		if(!empty($_POST['edit'])){
			$this->disabled = '';
			$this->editdisabled = ' disabled="disabled" ';	
		}
	}
	
	public function toggleDebug(){
		$this->debug = true;
		return $this;
	}
	
	private function dbConnect(){
		$this->connect = mysqli_connect($this->dbhost,$this->dbuser,$this->dbpassword) or die('Connection to the database server could not be established: ' . mysqli_error($this->connect));
	}
	
	protected function sqlProtect($input, $key){
		$input = stripslashes($input);
		$input = mysqli_escape_string($this->connect,$input);
		$input = $this->protectedVal[$key] = $input;
		return $this;
	}
	
	private function dbSelect(){
		if(!$this->connect){
			die('Connection to the database server could not be established: '.mysqli_error());
		}else{
			mysqli_select_db($this->connect, $this->dbname) or die('Could not select the database: '.mysqli_error($this->connect));
		}
	}
	
	protected function runQuery($sql){
		if($this->debug){die($sql);}
		$query = mysqli_query($this->connect,$sql) or die($sql ."\n". mysqli_error($this->connect));
		$this->query = $query;
		$this->protectedVal = array();
		return $this;
	}
	
	protected function getResult(){
		if(!$this->query){
			die('You need to set up a query before requesting a data array!');
		}else{
			$this->data = mysqli_fetch_array($this->query);
			return $this->data;
		}
	}
		
	public function numRows(){
		if(!$this->query){
			die('You need to set up a query before requesting a row count!');
		}else{
			$this->rowCount = mysqli_num_rows($this->query);
			return $this->rowCount;
		}
	}
		
	public function settings($param, $value){
		if(!is_array($value) && is_array($this->$param)){
			$value = str_replace(" ","",$value);
			$value = explode($this->arrayDelimiter,$value);
		}
		$this->$param = $value;
		return $this;
	}
			
	public function select(){
		if(isset($this->whereClause)){
			$where = $this->whereClause;
		}else{
			foreach($this->whereVals as $wherekey => $whereval){
				$this->sqlProtect($whereval, $wherekey);
				array_push($this->where,$this->whereCols[$wherekey]."'".$this->protectedVal[$wherekey]."'");
			}
			$where = implode($this->whereOperator,$this->where);
		}
		$cols = implode($this->arrayDelimiter,$this->cols);
		if($this->limit != ''){ $limit = "LIMIT ".$this->limit; }else{$limit = '';}
		if($this->order != ''){ $order = "ORDER BY ".$this->order; }else{$order = '';}
		if($where != ''){ $setwhere = "WHERE ".$where; }
		$sql = "SELECT $cols FROM ".$this->table." $setwhere $order $limit";
		$this->runQuery($sql);
		return $this->query;
	}
	
	public function insert(){
		foreach($this->vals as $key => $val){
			$this->sqlProtect($val, $key);
			array_push($this->valarray,"'".$this->protectedVal[$key]."'");
		}unset($val);
		$colarray = implode($this->arrayDelimiter,$this->cols);
		$valarray = implode($this->arrayDelimiter,$this->valarray);
		$sql = "INSERT INTO ".$this->table." ($colarray) VALUES ($valarray)";
		$this->runQuery($sql);
		$id = mysqli_insert_id($this->connect);
		return $id;
	}
	
	public function delete(){
		if(isset($this->whereClause)){
			$where = $this->whereClause;
		}else{
			foreach($this->whereVals as $wherekey => $whereval){
				$this->sqlProtect($whereval, $wherekey);
				array_push($this->where,$this->whereCols[$wherekey]."'".$this->protectedVal[$wherekey]."'");
			}
			$where = implode($this->whereOperator,$this->where);
		}
		unset($whereval);
		if($this->limit != ''){ $limit = "LIMIT ".$this->limit; }
		$sql = "DELETE FROM ".$this->table." WHERE $where $limit";
		$this->runQuery($sql);
		return $this;
	}

	public function update(){
		if(isset($this->whereClause)){
			$where = $this->whereClause;
		}else{
			foreach($this->whereVals as $wherekey => $whereval){
				$this->sqlProtect($whereval, $wherekey);
				array_push($this->where,$this->whereCols[$wherekey]."'".$this->protectedVal[$wherekey]."'");
			}
			$where = implode($this->whereOperator,$this->where);
		}
		unset($whereval);
		foreach($this->vals as $key => $val){
			$this->sqlProtect($val, $key);
			array_push($this->set,$this->cols[$key]."='".$this->protectedVal[$key]."'");
		}
		unset($val);
		$set = implode($this->arrayDelimiter,$this->set);
		if($this->limit != ''){ $limit = "LIMIT ".$this->limit; }
		$sql = "UPDATE ".$this->table." SET $set WHERE $where $limit";
		$this->runQuery($sql);
		return true;
	}
	
	public function getRow(){
		$this->limit = 1;
		$this->select();
		$this->getResult($this->query);
		return $this->data;
	}
	
	/*
		@param $view = The view defined in the controller
		@return the contents of the view - assigns it as a public property to be accessed
	*/
	
	public function get_view($view){
		$this->view = file_get_contents($view);
		return $this;
	}
		
}


?>