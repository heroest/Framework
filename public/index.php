<?php
define('_start', microtime());
date_default_timezone_set('America/Toronto');

error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
define('framework_name', 'lightning');
$di = require_once(dirname(dirname(__FILE__)) . '/system/bootstrap.php');

/*
//set to load
$di->set('myPlugin', function(){
	return new lightning\application\plugin\myPlugin();
});
*/

/*
//override
$di->override('session', function(){
	$session = new lightning\application\extend\session_event_emitter();
	$session->start(null);
	return $session;
});
*/

$app = new lightning\system\MVC\application($di);
echo $app->handle();
?>