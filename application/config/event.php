<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
use lightning\system\core\event\eventDI;
$eventDI = new eventDI();


$eventDI->on('before_parse_URL', function(){
	$instance = lightning\system\core\SystemClass::getInstance();
	$security = $instance->security;
	$security->add_security_header();
});

$eventDI->on('before_parse_URL', function(){
	$instance = lightning\system\core\SystemClass::getInstance();
	$security = $instance->security;
	$security->url_check();
});

$eventDI->on('before_controller_execution', function(){
	//add html5 tag
	echo "<!DOCTYPE HTML>\r\n";
});

$eventDI->on('before_controller_execution', function($controller, $action, $param_arr) {
	$instance = lightning\system\core\SystemClass::getInstance();
	$request  = $instance->request;
	$security = $instance->security;
	if($request->get_request_method() == 'post') {
		$security->csrf_validate_post();
	}
});

$eventDI->on('before_controller_execution', function($controller, $action, $param_arr) {
	if($controller == 'user_controller') {
		$instance = lightning\system\core\SystemClass::getInstance();
		if($action == 'loginAction' and $instance->session->has('user_login')) {
			redirect('/');
		} else if($action == 'logout') {
			$salt = array_pop($param_arr);
			$instance->security->csrf_validate($salt);
		}
	}
});

$eventDI->on('Custom_Session_event', function($msg){
	var_dump("session started, $msg");
});



return $eventDI;
?>