<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class framework extends container
{
	public function __construct(){}

	//dispatch
	public function dispatch()
	{
		//run security check
		$this->security->run_check();

		list($controller, $action, $query_array) = $this->route->fetch_url();
		/*
		$controller = $arr['controller'];
		$action = $arr['action'];
		$query_array = $arr['query'];
		*/
		

		//search for controller file and load it
		$file_path = controllerDir . $controller . '.php';
		if( ! file_exists($file_path) ){
			show_404($controller . '.php', 'Controller file is missing');
		} else {
			require_once($file_path);
		}
		//load controller class
		$dispatcher = $controller::getInstance();

		//search for action function in controller
		if( method_exists($dispatcher, $action) ){
			call_user_func_array( array($dispatcher, $action), $query_array );
		} else {
			show_404($action, $action . ' is missing in ' . $controller);
		}

		//clean up
		$output = ob_get_contents(); 
    	ob_end_clean();
    	return $output;
	}//end dispatch function
}//end framework class


?>