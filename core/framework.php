<?php if ( ! defined('root_path')) exit('No direct script access allowed');


class framework extends singleton
{
	//set registry-tree as stdClass
	private static $core;
	private static $config;
	private static $lib;


	private function __clone(){}


	public function __construct()
	{
		self::$config = new stdClass();
		self::$core = array();
		self::$lib = array();
	}

	//load config
	public function load_config($config_array)
	{
		foreach($config_array as $key=>$val_arr){
			$obj = new stdClass();
			foreach($val_arr as $name=>$val){
				$obj->$name = $val;
			}
			self::$config->$key = $obj;
		}
	}

	public function set_core( $key, $obj )
	{
		self::$core[$key] = $obj;
	}

	public function get_core( $key )
	{	
		if(isset(self::$core[$key]) && !empty(self::$core[$key])){
			return self::$core[$key];
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

		//load required module and configuration
		$request = $this->get_core('request');
		$security = $this->get_core('security');
		$web_conf = $this->get_config('website');
		$sec_conf = $this->get_config('security');

		//get request url
		$url = $request->get_url();
	
		$controller = $web_conf->default_controller;
		$action = 'indexAction';
		$query_array = array();

		
		if( ! empty($url) ){
			$url_arr = explode('/', $url);

			//security check for uri
			$security->uri_check($url_arr);
			//analysis url
			$controller = $url_arr[0];
			array_shift($url_arr);
			if( !empty($url_arr[0]) ){
				$action = $url_arr[0] . 'Action';
			}
			array_shift($url_arr);
			if( !empty($url_arr[0]) ){
				$query_array = $url_arr;
			}
		}
		//csrf_check if auto_check is enabled
		if($request->get_request_type()=='post' && $sec_conf->enable_csrf_auto_check){
			$security->csrf_check();
		}


		//search for controller php file and load it
		$file_path = controllerDir . $controller . '_controller.php';
		if( ! file_exists($file_path) ){
			show_404($controller . '_controller.php', 'Controller file is missing');
		} else {
			require_once($file_path);
		}
		
		//load and initiate controller class
		$dispatcher = new $controller();
		$dispatcher->load_core(self::$core);


		//search for action function in controller
		if( method_exists($dispatcher, $action) ){
			call_user_func_array( array($dispatcher, $action), $query_array );
		} else {
			show_404($action, $action . ' is missing in ' . $controller . '_controller');
		}

	}//end dispatch function
}//end framework class


?>