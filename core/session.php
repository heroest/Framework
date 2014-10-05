<?php if ( ! defined('root_path')) exit('No direct script access allowed');

class session extends SF_model
{

	private $conf;
	private $session_data = array();
	private $session_id;
	private $request;

	public function __construct()
	{
		parent::__construct();
		$sf = framework::getInstance();
		$this->conf = $sf->get_config('session');
		
		//get session_id
		if( isset($_COOKIE[$this->conf->name]) ){
			$this->session_id = $_COOKIE[$this->conf->name];
		} else {
		//if no session_id exists
			$this->create_session();
		}

		
		if( $this->load_session_data() === False ){
			//load session data from database
			//if no matched, create new session
			$this->create_session();
		}
		//if request is not ajax, then update session
		$this->request = $sf->get_core('request');
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
				//session expires, then destory old record
				$sql = "DELETE FROM session where session_id = '$this->session_id'";
				if(! $this->db->query($sql)){
					show_error($this->db->error);
				}
				return False;
			} else {
				//not expires
				$this->session_data =  json_decode($obj->session_data, True);
				return True;
			}

		} else {
			//if no session record
			return False;
		}
	}

	private function create_session( $session_data=array() )
	{
		$sf = framework::getInstance();
		$req = $sf->get_core('request');
		$stmt = $this->db->prepare(
			"INSERT INTO session(session_id, session_data, expires) VALUES (?,?,?)"
			);
		$stmt->bind_param( "sss", $session_id, $json_session_data, $expires );

		$session_data = array('url' => $req->get_url());
		$session_id = $this->create_session_id();
		$json_session_data = json_encode($session_data);
		$expires = time() + $this->conf->duration;

		if(! $stmt->execute() ){
			show_error($stmt->error);
		}
		$this->session_id = $session_id;
		$this->session_data = $session_data;
	}

	public function update_session()
	{
		
		$stmt = $this->db->prepare(
			"UPDATE session SET session_id=?,expires=? WHERE session_id=?"
			);
		$stmt->bind_param('sss', $new_session_id, $expires, $this->session_id);
		$new_session_id = $this->create_session_id();
		$expires = time() + $this->conf->duration;
		if(! $stmt->execute() ){
			show_error($stmt->error);
		}
		//set cookie expires in 1 year
		setcookie($this->conf->name, $new_session_id, time()+3600*24*365, '/', $this->request->get_globals('SERVER', 'HTTP_HOST'), False);
		$this->session_id = $new_session_id;
	}

	public function set( $sess_array )
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

	public function get_all($key)
	{
		return $this->session_data;
	}

	public function del($key = '')
	{
		if(empty($key)){
			$this->session_data = array();
		} else if( isset($this->session_data[$key]) ){
			unset($this->session_data[$key]);
		}
		$this->update_session_data();
	}






	private function update_session_data()
	{
		$stmt = $this->db->prepare(
			"UPDATE session SET session_data=? WHERE session_id=?"
			);
		$json_session_data = json_encode($this->session_data);
		$stmt->bind_param("ss", $json_session_data, $this->session_id);
		if(! $stmt->execute() ){
			show_error($stmt->error);
		}
	}


	private function create_session_id()
	{
		return md5(uniqid(true) . $this->conf->salt . sha1(time()));
	}

}



?>