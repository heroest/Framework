<?php if ( ! defined('framework_name')) exit('No direct script access allowed');

$eventDI = new \lightning\system\core\event\eventDI();


//url check
$eventDI->on('event_parse_url', function($app) {
	$app->security->add_security_header();
	$app->security->url_check();
});

//csrf auto check on post action
$eventDI->on('event_controller_execution', function($app) {
	if($app->request->get_request_method() == 'post') {
		$app->security->csrf_validate_post();
		if($app->request->getPost('g-recaptcha-response') !== False) {
			$app->captcha->verify();
		}
	}
});

$eventDI->on('event_controller_execution', function($app) {
	if($app->get('controller') !== 'user_controller' and !$app->request->isAjax()) {
		$app->session->set( array('current_page' => $app->get('url')) );
	}
});

$eventDI->on('event_controller_execution', function($app) {

	$controller = $app->get('controller');
	$action 	= $app->get('action');
	$param 		= $app->get('param');

	if($controller == 'user_controller') {
		if($action == 'loginAction' and $app->session->has('user_login')) {
			redirect(base_url());
		} else if($action == 'logout') {
			$token = array_pop($param);
			$app->security->csrf_validate($token);
		}
	}
});


return $eventDI;
?>