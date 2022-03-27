<?php
class Event
{
	private $_db,
			$_data,
			$_count = 0,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn,
			$_errors = array();
	
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/event');
		$this->_cookieName = Config::get('remember/event');
		
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				if($this->find_user($user)){
					$this->_isLoggedIn = true;
				}else{
				    //logout
				}
			}
		}else{
			$this->find_user($user);
		}
        
	}

	public function insert($fields = array())
	{
		if(!$this->_db->insert('event', $fields))
			throw new Exception("There was a problem a inserting.");
	}
    
	public function update($fields = array(),$id = null)
	{
		if(!$this->_db->update('event',$id,$fields))
			throw new Exception('There was a problem updating');
	}

	public function find($trunk = null,$limit = null)
	{
		if($trunk){
			$hit = false;
			if(is_numeric($trunk)){
				$field = 'ID';
				$data = $this->_db->getAll('event', array($field, '=', $trunk),$limit);
				if($data->count()){
					$this->_data = $data->first();
					$hit = true;
				}
			}
			
			if($hit == false){
					return true;
			}else{
				return true;
			}
		}
		return false;
	}
   
	public function find_user($user = null,$fields = array())
	{
		$table = "event";
		if($user){
			$hit = false;
			if(is_numeric($user)){
				$field = 'ID';
				$data = $this->_db->get($fields,$table, array($field, '=', $user));
				if($data->count()){
					$this->_data = $data->first();
					$hit = true;
				}
			}elseif(filter_var($user, FILTER_VALIDATE_EMAIL) == true){
				$field = 'email';
				$data = $this->_db->get($fields,$table, array($field, '=', $user));
				if($data->count()){
					$this->_data = $data->first();
                    $hit = true;
				}
			}else{
				$field = 'username';
				$data = $this->_db->get($fields,$table, array($field, '=', $user));
				if($data->count()){
					$this->_data = $data->first();
					$hit = true;
				}
			}
			
			if($hit == true){
				return true;
			}
		}
		return false;
	}
	
	public function select($sql = null){
		$data = $this->_db->query("SELECT* FROM `event` {$sql}");
		if($data->count()){
			$this->_count = $data->count();
			$this->_data = $data->results();
		}
	}

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
	
	public function data(){
		return $this->_data;
	}

	public function first(){
		$data = $this->data();
		if(isset($data[0])){
			return $data[0];
		}
		return '';
	}
	
	public function count(){
		return $this->_count;
	}
	
	private function addError($error){
		$this->_errors[] = $error;
	}

	public function errors(){
		return $this->_errors;
	} 
}
?> 