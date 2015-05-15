<?php
define('_start', microtime());
date_default_timezone_set('America/Toronto');

error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
define('framework_name', 'lightning');
$di = require_once(dirname(dirname(__FILE__)) . '/system/bootstrap.php');

//cache
$di->set('cache', function() {
	$cache = new lightning\system\core\cache\cache_adapter();
	$cache->set_handler(new lightning\system\core\cache\driver\cache_apc());
	return $cache;
});

//session
$di->set('session', function() {
	$session = new lightning\system\core\session\session_adapter();
	$session->start(null);
	return $session;
});

//database_adapter
$di->set('db', function() {
	$db_adapter = new lightning\system\database\database_adapter();
	return $db_adapter->connect();
});

//custom load
$di->set_by_array(require_once(config_path . 'load.php'));


$app = new lightning\system\MVC\application($di);
echo $app->handle();
?>