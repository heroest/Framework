<?php namespace lightning\system\core\cache;
if ( ! defined('framework_name')) exit('No direct script access allowed');


use lightning\system\core\SystemClass;

class cache_adapter extends SystemClass
{
	private $config;
	private $handler = null;

	public function __construct(){}

	public function set_handler(Interface_cache_handler $handler = null)
	{
		$this->handler = $handler;
	}

	public function get($key)
	{
		return $this->handler->get($key);
	}

	public function set($key, $value, $timeout=0)
	{
		return $this->handler->set($key, $value, $timeout);
	}

	public function set_arr($arr, $timeout=0)
	{
		return $this->handler->set_arr($arr, $timeout);
	}

	public function add($key, $value, $timeout=0)
	{
		return $this->handler->add($key, $value, $timeout);
	}

	public function add_arr($arr, $timeout=0)
	{
		return $this->handler->add_arr($arr, $timeout);
	}

	public function has($key)
	{
		return $this->handler->has($key);
	}

	public function has_arr($arr)
	{
		return $this->handler->has_arr($arr);
	}

	public function delete($key)
	{
		return $this->handler->delete($key);
	}

	public function destory()
	{
		return $this->handler->destory();
	}
}


?>