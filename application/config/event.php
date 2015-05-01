<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
use lightning\system\core\event\eventDI;
$eventDI = new eventDI();

//url check
$eventDI->on('event_parse_url', function($app) {
	$app->security->url_check();
	$app->security->add_security_header();
});

//csrf auto check on post action
$eventDI->on('_controller_execution', function($app) {
	if($app->request->get_request_method() == 'post') {
		$app->security->csrf_validate_post();
	}
});


$eventDI->on('event_controller_execution', function($app) {
	/*
	if(!$app->session->has('user_login') and $app->get('controller') !== 'user_controller') {
		$app->set('controller', 'user_controller');
		$app->set('action', 'loginAction');
	}
	*/
	if($app->get('controller') !== 'user_controller') {
		$app->session->set(array('current_page' => $app->get('url')));
	}
});

$eventDI->on('event_controller_execution', function($app) {
	$controller = $app->get('controller');
	$action 	= $app->get('action');
	$param 		= $app->get('param');

	if($controller == 'user_controller') {
		if($action == 'loginAction' and $app->session->has('user_login')) {
			redirect('/');
		} else if($action == 'logout') {
			$salt = array_pop($param);
			$app->security->csrf_validate($salt);
		}
	} 
});

$eventDI->on('event_get_page', function($app) {
	$page_data = $app->get('page');
	$page_data = "<!DOCTYPE HTML>\r\n" . $page_data;
	$app->set('page', $page_data);
});



return $eventDI;
?>