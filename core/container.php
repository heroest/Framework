<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');

class container extends singleton
{

	protected static $storage = array();
	protected static $config = array();

	public function __construct(){}

	public function __set($key, $obj)
	{
		if(isset(self::$storage[$key])){
			show_error($key . ' already exist in container.');
		}
		self::$storage[$key] = $obj;
	}

	public function __get($key)
	{
		if(!isset(self::$storage[$key])){
			show_error('module: ' . $key . ' is missing.');
		}
		if(is_callable(self::$storage[$key])){
			self::$storage[$key] = call_user_func(self::$storage[$key]);
		}
		return self::$storage[$key];
	}

	public function load_config($config)
	{
		foreach($config as $name=>$arr){
			foreach($arr as $key=>$val){
				self::$config[$name][$key] = $val;
			}
		}
	}

	public function get_config($key)
	{
		if(isset(self::$config[$key])){
			return self::$config[$key];
		} else {
			show_error('Faild to load config: ' . $key);
		}
	}


}//end class_container
?>