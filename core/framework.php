<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class framework extends singleton
{
	//set registry-tree as stdClass
	private static $core;
	private static $config;
	private static $addon;


	private function __clone(){}


	public function __construct()
	{
		self::$config = new \stdClass();
		self::$core = array();
		self::$addon = array();
	}

	//load config
	public function load_config($config_array)
	{
		foreach($config_array as $key=>$val_arr){
			$obj = new \stdClass();
			foreach($val_arr as $name=>$val){
				$obj->$name = $val;
			}
			self::$config->$key = $obj;
		}
	}

	public function load_core( $key, $obj )
	{
		self::$core[$key] = $obj;
	}

	public function get_core( $key )
	{	
		if(isset(self::$core[$key]) && !empty(self::$core[$key])){
			return is_callable(self::$core[$key]) ? call_user_func(self::$core[$key]) : self::$core[$key];
		} else {
			return False;
		}
	}

	public function get_config( $key )
	{
		if(isset(self::$config->$key) && !empty(self::$config->$key)){
			return self::$config->$key;
		} else {
			return False;
		}
	}



	public function dispatch()
	{

		$request = $this->get_core('request');
		$security = $this->get_core('security');
		$sec_conf = $this->get_config('security');
		$route = $this->get_core('route');

		$arr = $route->fetch_url();
		$controller = $arr['controller'];
		$action = $arr['action'];
		$query_array = $arr['query'];
		
		//csrf_check if auto_check is enabled
		if($request->get_request_type()=='post' && $sec_conf->enable_csrf_auto_check){
			$security->csrf_check();
		}

		//search for controller file and load it
		$file_path = controllerDir . $controller . '.php';
		if( ! file_exists($file_path) ){
			show_404($controller . '.php', 'Controller file is missing');
		} else {
			require_once($file_path);
		}
		//load controller class
		$dispatcher = new $controller();
		//pass loaded module to controller
		$dispatcher->set_core(self::$core);

		//search for action function in controller
		if( method_exists($dispatcher, $action) ){
			call_user_func_array( array($dispatcher, $action), $query_array );
		} else {
			show_404($action, $action . ' is missing in ' . $controller);
		}

	}//end dispatch function
}//end framework class


?>