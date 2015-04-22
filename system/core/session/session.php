<?php
namespace lightning\system\core\session;
use lightning\system\core\SystemClass;
use lightning\system\core\session\session_interface;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class session extends SystemClass 
{

	private $config;

	public function __construct()
	{
		$this->config = $this->get_config('session');
		$type = $this->config['type'];
		
		if('default' !== $type or !empty($type)) {
			$session = "lightning\system\core\session\driver\session_$type";
			$handler = new $session();
			session_set_save_handler($handler, True);
		}		
	}

	public function start()
	{
		session_start();
		if(! isset($_SESSION['expires']) or time() > $_SESSION['expires']) {
			$_SESSION = array();
		}
		$this->extend_session();
	}

	/*
	*   Custom Function
	*/
	public function set($arr)
	{
		foreach($arr as $key=>$obj) {
			$_SESSION[$key] = $obj;
		}
	}

	public function has($key)
	{
		return isset($_SESSION[$key]);
	}

	public function get($key = '')
	{
		if(isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return False;
		}
	}

	public function delete($key)
	{
		unset($_SESSION[$key]);
	}

	public function destory()
	{
		session_destroy();
	}

	private function extend_session()
	{
		$duration = $this->config['duration'];
		$_SESSION['expires'] = (time() + $duration);
	}


}

?>