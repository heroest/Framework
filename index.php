<?php
	

	error_reporting(E_ALL);
	header('Content-Type: text/html; charset=utf-8');
	define('framework_name', 'strawberry_framework v1.01');

	//define path for framework
	define('root_path', dirname(__FILE__));
	define('core_path', root_path . '/core/');
	define('app_path', root_path . '/application/');
	define('configDir', app_path . 'config/');
	define('controllerDir', app_path . 'controller/');
	define('modelDir', app_path . 'model/');
	define('viewDir', app_path . 'view/');
	
	//load route map
	require_once( configDir . 'route.php' );

	//load configuration
	require_once( configDir . 'config.php' );

	//load helper function
	require_once( core_path . 'helper.php' );


	//auto load core class or model class
	function __autoload($className)
	{
		$arr = explode('\\', $className);
		if($arr[0] == 'SF_core'){
			require_once(core_path. end($arr) . '.php');
		} else if( file_exists(modelDir . $className . '.php') ){
			require_once(modelDir . $className . '.php');
		} else {
			show_404($className . '.php', ' class file missing');
		}
	}
	
	//framework core initiating
	use \SF_core;

	$sf = \SF_core\framework::getInstance();
	$sf->load_config($config);

	//load request module
	$sf->load_core('request', new \SF_core\request());

	//load session module
	$sf->load_core('session', new \SF_core\session());

	//load security module
	$sf->load_core('security', new \SF_core\security());

	//load route module
	$sf->load_core('route', new \SF_core\route($route));


	//dispatch
	$sf->dispatch();




	
	
	
