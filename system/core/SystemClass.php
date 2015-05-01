<?php
namespace lightning\system\core;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class SystemClass
{
	private static $_cache;
	private static $_DI;
	private static $_storage;
	private static $_config;
	private static $_instance = null;

	private function __construct() {}

	public function __get($key)
	{
		if(! $this->has($key)) {
			show_error("Error in SystemClass->__get(): Can not find a Ojbect with key [$key]");
		} else {
			return $this->return_object($key);
		}
	}

	public function get_config($key)
	{
		if(! $this->has_config($key)) {
			show_error("Error in SystemClass->get_config(): Can not find config with key[$key]");
		} else {
			return $this->return_config($key);
		}
	}

	public function __set($key, $obj)
	{
		show_error("Error in SystemClass->__set(): $key, This action is not Allowed");
	}

	public function set_DI($di)
	{
		self::$_storage = $di->load_storage();
		self::$_config	= $di->load_config();
		self::$_DI 		= $di;
		return self::$_instance;
	}

	public function get_DI()
	{
		return self::$_DI;
	}

	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}



	
	private function has($key)
	{
		return isset(self::$_storage[$key]) && !empty(self::$_storage[$key]);
	}

	private function return_object($key)
	{
		if(is_callable(self::$_storage[$key])) {
			self::$_storage[$key] = call_user_func(self::$_storage[$key]);
		}
		return self::$_storage[$key];
	}

	private function has_config($key)
	{
		return isset(self::$_config[$key]) && !empty(self::$_config[$key]);
	}

	private function return_config($key)
	{
		return self::$_config[$key];
	}
}



?>