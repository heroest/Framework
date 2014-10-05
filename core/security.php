<?php if ( ! defined('root_path')) exit('No direct script access allowed');

class security
{
	private $conf;
	private $session;
	private $request;
	public $csrf_key;
	public $csrf_token_name;
	
	public function __construct()
	{
		$sf = framework::getInstance();
		$this->conf = $sf->get_config('security');
		$this->session = $sf->get_core('session');
		$this->request = $sf->get_core('request');
		$this->csrf_key = md5(uniqid(true) . $this->conf->csrf_salt);
		$this->csrf_token_name = $this->conf->csrf_token_name;
	}


	public function uri_check($uri_arr)
	{
		$pattern = '/[^' . $this->conf->permitted_uri_chars . ']/';
		foreach($uri_arr as $uri){
			if(preg_match($pattern, $uri)){
			//if any character other than permitted_uri_chars
			//show error
				show_error('<strong>WARNING</strong>: uri contains disallowed characters.');
			}
		}
	}

	public function new_csrf_token()
	{
		$this->session->set(array( 
			$this->conf->csrf_token_name => $this->csrf_key,
			'csrf_token_expires' => time() + $this->conf->csrf_token_duration
		 ));
		return array(
			'token_name' => $this->conf->csrf_token_name,
			'key' => $this->csrf_key
			);
	}

	public function csrf_check($csrf_check = '')
	{
		$csrf_key = $this->session->get($this->conf->csrf_token_name);
		$csrf_expires = $this->session->get('csrf_token_expires');
		if(empty($csrf_check)){
			$csrf_check = $this->request->post($this->conf->csrf_token_name);
		}
		if($csrf_expires < time()){
			show_error('<strong>CSRF</strong>&nbsp;CSRF Token expires');
		} else if( ! (md5($csrf_key) === md5($csrf_check)) ){
			show_error('<strong>CSRF Token</strong>&nbsp; missing or incorrect');
		} else {
			return True;
		}
	}

	public function csrf_input()
	{
		$csrf = $this->new_csrf_token();
		echo '<input type="hidden" name="' . $csrf['token_name'] . '" value="' . $csrf['key'] . '" />';
	}




}//end security class



?>