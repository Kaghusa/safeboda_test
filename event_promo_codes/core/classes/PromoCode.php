<?php
class PromoCode
{
	private $_db,
			$_data,
			$_count = 0,
			$_errors = array();
	
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		
		if($user){
			//$this->find($user);
		}
	}

//USER CREATE AN ACCOUNT
	public function insert($fields = array()){
		if(!$this->_db->insert('event_promo_code', $fields)){
			throw new Exception("There was a problem transaction_entry.");
		}
	}

// USER DATA UPDATE
	public function update($fields = array(),$id = null){
		if(!$this->_db->update('event_promo_code',$id,$fields)){
			throw new Exception('There was a problem updating');
		}
	}
	public function updateMultiple($fields = array(),$conditions = array()){
		if(!$this->_db->updateMultiple('event_promo_code',$conditions, $fields)){
			throw new Exception('There was a problem updating');
		}
	}

	// Select	
	public function select($sql = null){
		$data = $this->_db->query("SELECT* FROM `event_promo_code` {$sql}");
		if($data->count()){
			$this->_count = $data->count();
			$this->_data = $data->results();
		}
	}
	// Select	
	public function selectQuery($sql,$params=array()){
		$data = $this->_db->query($sql,$params);
		if($data->count()){
			$this->_count = $data->count();
			$this->_data = $data->results();
		}
	}
	
	public function exists(){
		return (!empty($this->_data))? true : false;
	}
	

// DATA COLLECT
	public function data(){
		return $this->_data;
	}
// first
	public function first(){
		$data = $this->data();
		if(isset($data[0])){
			return $data[0];
		}
		return '';
	}
	
// DATA COUNT
	public function count(){
		return $this->_count;
	}
	
// ADD ERRORS NOTIF
	private function addError($error){
		$this->_errors[] = $error;
	}
// ERROR COLLECT
	public function errors(){
		return $this->_errors;
	} 
}
?> 