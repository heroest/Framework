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
		if(! empty($this->session_name)) {
			session_name($this->config['session_name']);
		} else {
			$server_name = $this->request->getGlobal('SERVER', 'HTTP_HOST');
			$server_name = 'lightning_' . str_replace(".", "_", $server_name);
			session_name($server_name);
		}
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
		
		if(! isset($_SESSION['expires']) or time() > $_SESSION['expires']) {
			$saved = isset($_SESSION['persistent']) ? $_SESSION['persistent'] : array();
			$_SESSION = array();
			$_SESSION['persistent'] = $saved;
			$_SESSION['temporary']	= array();
		} 
		$this->extend_session();
	}

	/*
	*   Custom Function
	*/
	public function set($arr, $persistent = false)
	{
		if($persistent) {
			$storage  = 'persistent';
			$opposite = 'temporary';
		} else {
			$storage  = 'temporary';
			$opposite = 'persistent';
		}
		foreach($arr as $key=>$obj) {
			$_SESSION[$storage][$key] = $obj;
			if(isset($_SESSION[$opposite][$key])) unset($_SESSION[$opposite][$key]);
		}
		return $this;
	}

	public function has($key)
	{
		return isset($_SESSION['temporary'][$key]) or isset($_SESSION['persistent'][$key]);
	}

	public function get($key = '')
	{
		if(isset($_SESSION['temporary'][$key])) {
			return $_SESSION['temporary'][$key];
		} else if(isset($_SESSION['persistent'][$key])) {
			return $_SESSION['persistent'][$key];
		} else {
			return False;
		}
	}

	public function delete($key)
	{
		unset($_SESSION['temporary'][$key]);
		unset($_SESSION['persistent'][$key]);
		return $this;
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