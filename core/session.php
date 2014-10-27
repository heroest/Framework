<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class session extends SF_model implements I_session
{

	private $conf;
	private $session_data = array();
	private $session_id;
	private $request;
	private $index_id;



	public function __construct()
	{
		parent::__construct();
		$sf = framework::getInstance();
		
		//get session config
		$this->conf = $sf->get_config('session');

		//load request module
		$this->request = $sf->get_core('request');
		
		//get session_id
		$this->session_id = $this->request->get_globals('COOKIE', $this->conf->name);
		//if fail to get session_id or fail to get session_data
		if(! $this->session_id or $this->load_session_data() === False){
			$this->create_session();
		}

		//if request is not ajax, then update session
		if($this->request->is_ajax() === False){
			$this->update_session();
		}
	}

	private function load_session_data()
	{
		$sql = "SELECT * FROM session where session_id = '$this->session_id'";
		$res = $this->db->query($sql);

		if($res->num_rows == 1){
			$obj = mysqli_fetch_object($res);
			
			if( $obj->expires < time() ){
				//session expires, then destory all session
				$this->delete();
			} else {
				//load session data
				$this->session_data =  json_decode($obj->session_data, True);
			}
			$this->index_id = $obj->id;
			return True;

		} else {
			//if no session_id exists
			return False;
		}
	}

	private function create_session( $session_data=array() )
	{
		$stmt = $this->db->prepare(
			"INSERT INTO session(session_id, session_data, expires) VALUES (?,?,?)"
			);
		$stmt->bind_param( "sss", $session_id, $json_session_data, $expires );

		$session_data = array('url' => $this->request->get_url());
		$session_id = $this->create_session_id();
		$json_session_data = json_encode($session_data);
		$expires = time() + $this->conf->duration;

		if(! $stmt->execute() ){
			show_error($stmt->error);
		}
		$this->index_id = $this->db->insert_id;
		$this->session_id = $session_id;
		$this->session_data = $session_data;
	}

	private function update_session()
	{
		$stmt = $this->db->prepare(
			"UPDATE session SET session_id=?,expires=? WHERE id=?"
			);
		$stmt->bind_param('sss', $new_session_id, $expires, $this->index_id);
		$new_session_id = $this->create_session_id();
		$expires = time() + $this->conf->duration;
		if(! $stmt->execute() ){
			show_error($stmt->error);
		}
		//set cookie expires in 1 year
		setcookie($this->conf->name, $new_session_id, time()+3600*24*365, '/', $this->request->get_globals('SERVER', 'HTTP_HOST'), False);
		$this->session_id = $new_session_id;
	}

	private function update_session_data()
	{
		$stmt = $this->db->prepare(
			"UPDATE session SET session_data=? WHERE id=?"
			);
		$json_session_data = json_encode($this->session_data);
		$stmt->bind_param("ss", $json_session_data, $this->index_id);
		if(! $stmt->execute() ){
			show_error($stmt->error);
		}
	}

	private function create_session_id()
	{
		return md5(uniqid(true) . $this->conf->salt . rand());
	}



	public function set($sess_array)
	{
		foreach($sess_array as $key=>$val){
			$this->session_data[$key] = $val;
		}
		$this->update_session_data();
		
	}

	public function get($key)
	{
		if( isset($this->session_data[$key]) && ! empty($this->session_data[$key]) ){
			return $this->session_data[$key];
		} else {
			return False;
		}
	}

	public function get_all()
	{
		return $this->session_data;
	}

	public function delete($key = '')
	{
		if(empty($key)){
			$this->session_data = array();
		} else if( isset($this->session_data[$key]) ){
			unset($this->session_data[$key]);
		}
		$this->update_session_data();
	}


}//end class session



?>