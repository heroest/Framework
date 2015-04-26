<?php if ( ! defined('framework_name')) exit('No direct script access allowed');

define('root_path', 		dirname(dirname(__FILE__)) . '/');
define('system_path', 		root_path . 'system/');
define('application_path', 	root_path . 'application/');

//SYSTEM PATH
define('core_path', 	system_path . 'core/');
define('database_path', system_path . 'database/');

//APPLICATION PATH
define('controller_path', 	application_path . 'controller/');
define('model_path', 		application_path . 'model/');
define('view_path', 		application_path . 'view/');
define('plugin_path', 		application_path . 'plugin/');
define('extend_path', 		application_path . 'extend/');
define('config_path', 		application_path . 'config/');
define('library_path',		application_path . 'library/');

//Load helper functions
require_once(core_path . 'helper.php');

function lightning_error_handler($errno, $errstr)
{
	show_error("<strong>[{$errno}]:</strong> {$errstr}");
}

function lightning_autoload($class) 
{
	$class_arr = explode('\\', $class);
	if($class_arr[0] == 'lightning') {
		array_shift($class_arr);
		$file_path = root_path . implode('/', $class_arr) . '.php';
		if(! file_exists($file_path)) {
			$file_path = Cpath($file_path);
			show_error("Autoloading: $class, File does not exist: $file_path");
		} else {
			require_once($file_path);
		}
	}
}

spl_autoload_register('lightning_autoload');
set_error_handler('lightning_error_handler');

$di = new lightning\system\core\DI();

//load config
$di->set_config(require_once(config_path . 'config.php'));

$di->set('request', function() {
	return new lightning\system\core\request();
});

$di->set('router', function() {
	return new lightning\system\core\router();
});

$di->set('cache', function() {
	$cache = new lightning\system\core\cache\cache();
	$cache->set_handler( new lightning\system\core\cache\driver\cache_apc() );
	return $cache;
});

$di->set('session', function() {
	$session = new lightning\system\core\session\session();
	$session->start(null);
	return $session;
});

$di->set('db', function() {
	$db_adapter = new lightning\system\database\database_adapter();
	return $db_adapter->connect();
});

$di->set('security', function() {
	return new lightning\system\core\security();
});

$di->set('eventManager', function() {
	return new lightning\system\core\event\eventManager(require_once(config_path . 'event.php'));
});

//load library
$di->set_by_array(require_once(config_path . 'load.php'));

return $di;
?>