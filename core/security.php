<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class security extends container
{
	private $config;
	public $csrf_key;
	public $csrf_token_name;
	
	public function __construct()
	{
		$this->config = $this->get_config('security');
		$this->csrf_key = md5(uniqid(true) . $this->config['csrf_salt']);
		$this->csrf_token_name = $this->config['csrf_token_name'];
	}

	public function run_check()
	{
		//uri check
		$this->uri_check($this->request->get_url());

		//csrf auto check
		if($this->request->get_request_type() == 'post' && $this->config['enable_csrf_auto_check'] === True){
			$this->csrf_check();
		}
	}


	private function uri_check($url)
	{
		$uri_arr = explode('/', $url);
		$pattern = '/[^' . $this->config['permitted_uri_chars'] . ']/';
		foreach($uri_arr as $uri){
			if(preg_match($pattern, $uri)){
			//if any character other than permitted_uri_chars
			//show error
				show_error('<strong>WARNING</strong>: uri contains disallowed characters.');
			}
		}
	}

	private function new_csrf_token()
	{
		$this->session->set(array( 
			$this->config['csrf_token_name'] => $this->csrf_key,
			'csrf_token_expires' => time() + $this->config['csrf_token_duration']
		 ));
		return array(
			'token_name' => $this->config['csrf_token_name'],
			'key' => $this->csrf_key
			);
	}

	public function csrf_check($csrf_check = '')
	{
		$csrf_key = $this->session->get($this->config['csrf_token_name']);
		$csrf_expires = $this->session->get('csrf_token_expires');
		if(empty($csrf_check)){
			$csrf_check = $this->request->post($this->config['csrf_token_name']);
		}
		if($csrf_expires < time()){
			show_error('<strong>CSRF</strong>&nbsp;CSRF Token expires');
		} else if( (md5($csrf_key) !== md5($csrf_check)) or !$csrf_check ){
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

	public function get_csrf_token()
	{
		return array(
			'token_name' => $this->config['csrf_token_name'],
			'key' => $this->csrf_key
			);
	}


}//end security class



?>