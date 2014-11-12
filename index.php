<?php
	

	error_reporting(E_ALL);
	header('Content-Type: text/html; charset=utf-8');
	define('framework_name', 'strawberry_framework v1.02 D');

	//define path for framework
	define('root_path', dirname(__FILE__));
	define('core_path', root_path . '/core/');
	define('interface_path', core_path . 'interface/');
	define('db_drive_path', core_path . '/database/');
	define('app_path', root_path . '/application/');
	define('configDir', app_path . 'config/');
	define('controllerDir', app_path . 'controller/');
	define('pluginDir', app_path . 'plugin/');
	define('modelDir', app_path . 'model/');
	define('viewDir', app_path . 'view/');
	
	//load route map: $route
	require_once( configDir . 'route.php' );

	//load configuration: $config
	require_once( configDir . 'config.php' );

	//load helper function
	require_once( core_path . 'helper.php' );


	//auto load core class or model class
	function __autoload($className)
	{
		$arr = explode('\\', $className);

		if($arr[0] == 'SF_core'){
			//if core module loading
			$file = end($arr);
			if( strpos($file, 'Interface_') === 0 ){
				require_once(interface_path . $file . '.php');
			} else if( strpos($file, 'db_') === 0 ){
				require_once(db_drive_path . $file . '.php');
			} else {
				require_once(core_path. $file . '.php');
			}

		} else if( file_exists(modelDir . $className . '.php') ){
			require_once(modelDir . $className . '.php');
		} else {
			show_404($className . '.php', ' model class file missing');
		}
	}

	//initiating container
	$container = \SF_core\container::getInstance();
	$container->load_config($config);

	//load module into container
	$container->request = function(){ return \SF_core\request::getInstance(); };
	$container->route = function()use($route){ return \SF_core\route::getInstance($route); };
	$container->session = function(){ return \SF_core\session::getInstance(); };
	$container->security = function(){ return \SF_core\security::getInstance(); };
	$container->db = function(){ $sql = \SF_core\db_mysql::getInstance(); return $sql->connect(); };

	
	//framework core initiating
	$sf = \SF_core\framework::getInstance();
	
	//dispatch
	echo $sf->dispatch();




	
	
	
