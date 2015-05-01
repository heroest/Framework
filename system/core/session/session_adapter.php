<?php
namespace lightning\system\core\session;
use lightning\system\core\SystemClass;
use lightning\system\core\session\session_interface;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class session_adapter extends SystemClass 
{

	private $config;

	public function __construct()
	{
		$this->config = $this->get_config('session');
	}

	public function start(\SessionHandlerInterface $handler = null)
	{
		if(! is_null($handler)) {
			session_set_save_handler($handler, True);
		} else {
			switch($this->config['type']) {
				case 'db':
					session_set_save_handler(new \lightning\system\core\session\driver\session_db(), True);
					break;
				case 'apc':
					session_set_save_handler(new \lightning\system\core\session\driver\session_apc(), True);
					break;
			}
		}

		session_start();
		session_regenerate_id();
		
		if(! isset($_SESSION['expires']) or time() > $_SESSION['expires']) $_SESSION = array();
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

	public function pop($key)
	{
		if($this->has($key)) {
			$ret = $this->get($key);
			$this->delete($key);
			return $ret;
		} else {
			return False;
		}
	}

	public function destory()
	{
		session_destroy();
	}

	private function extend_session()
	{
		$_SESSION['expires'] = (time() + $this->config['duration']);
	}


}

?>