<?php

	error_reporting(E_ALL);
	header('Content-Type: text/html; charset=utf-8');
	define('framework_name', 'strawberry_framework v1.0');

	//define path for framework
	define('root_path', dirname(__FILE__));
	define('core_path', root_path . '/core/');
	define('app_path', root_path . '/application/');
	define('configDir', app_path . 'config/');
	define('controllerDir', app_path . 'controller/');
	define('modelDir', app_path . 'model/');
	define('viewDir', app_path . 'view/');


	//load config array
	require_once( configDir . 'config.php' );

	//load helper function
	require_once( core_path . 'helper.php' );

	//auto load core class or model class
	function __autoload($className)
	{
		if(  file_exists(core_path . $className . '.php')  ){
			//check if model and core class use same name
			if(  file_exists(modelDir . $className . '.php')  ){
				show_error('Your can not use system reserved file name: <strong>' . $className . '.php</strong>');
			}
			require_once( core_path . $className . '.php' );

		} else if( file_exists(modelDir . $className . '.php') ){

			require_once(modelDir . $className . '.php');

		} else {

			show_404($className . '.php', 'model class missing');

		}
	}
	

	//framework core initiating
	$sf = framework::getInstance();
	$sf->load_config($config);

	//load request module
	$sf->set_core('request', new request());

	//load session module
	$sf->set_core('session', new session());

	//load security module
	$sf->set_core('security', new security());


	//after all prepartion work is done
	$sf->dispatch();

	

	
	
	
