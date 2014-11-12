<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');

class container extends singleton
{

	protected static $_storage = array();
	protected static $_config = array();

	public function __construct(){}

	public function __set($key, $obj)
	{
		if(isset(self::$_storage[$key])){
			show_error($key . ' already exist in container.');
		}
		self::$_storage[$key] = $obj;
	}

	public function __get($key)
	{
		if(!isset(self::$_storage[$key])){
			//if not find anything in $_storage, and try load module in plugin folder
			if(!file_exists(pluginDir . $key . '.php')){
				show_error('module: ' . $key . ' is missing.');
			} else {
				require_once(pluginDir . $key . '.php');
				self::$_storage[$key] = $key::getInstance();
			}
		}
		if(is_callable(self::$_storage[$key])){
			self::$_storage[$key] = call_user_func(self::$_storage[$key]);
		}
		return self::$_storage[$key];
	}

	public function load_config($config)
	{
		foreach($config as $name=>$arr){
			foreach($arr as $key=>$val){
				self::$_config[$name][$key] = $val;
			}
		}
	}

	public function get_config($key)
	{
		if(isset(self::$_config[$key])){
			return self::$_config[$key];
		} else {
			return False;
		}
	}


}//end class_container
?>