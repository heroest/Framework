<?php if ( ! defined('root_path')) exit('No direct script access allowed');

class request
{
	private $data = array();
	public function __construct()
	{
		//_SESSION, _POST, _GET,  _REQUEST, _SERVER
		$sys = array('GET', 'POST', 'REQUEST', 'SERVER');
		foreach($sys as $name){
			$val_name = '_' . $name;
			if(! empty($GLOBALS[$val_name])  )
			{
				$this->data[$name] = array();
				foreach( $GLOBALS[$val_name] as $key=>$value ){
					//check if it's POST
					if($val_name = 'POST'){
						$this->filter($value);
					}
					$this->data[$name][$key] = $value;
				}
				unset($GLOBALS[$val_name]);
			}
		}
	}

	public function post($key)
	{
		if( isset($this->data['POST'][$key]) )
		{
			return $this->data['POST'][$key];
		}
		else
		{
			return False;
		}
	}

	public function get($key)
	{
		if( isset($this->data['GET'][$key]) )
		{
			return $this->data['GET'][$key];
		}
		else
		{
			return False;
		}
	}

	public function get_url()
	{
		if(  isset($this->data['GET']['_url']) && !empty($this->data['GET']['_url'])  ){
			return $this->data['GET']['_url'];
		} else {
			return '';
		}
	}

	public function get_request_type()
	{
		return strtolower($this->data['SERVER']['REQUEST_METHOD']);
	}

	public function get_globals($globals_name, $key)
	{
		if(  isset($this->data[$globals_name][$key]) && !empty($this->data[$globals_name][$key]) ){
			return $this->data[$globals_name][$key];
		} else {
			return false;
		}
	}

	public function is_ajax()
	{
		$type = 'HTTP_X_REQUESTED_WITH';
		if(isset($this->data['SERVER'][$type]) 
			&& !empty($this->data['SERVER'][$type]) 
			&& strtolow($this->data['SERVER'][$type]) == 'xmlhttprequest' ){
			return True;
		} else {
			return False;
		}
	}

	public function filter(&$str)
	{
		$str = mysql_real_escape_string($str);
	}


}


?>