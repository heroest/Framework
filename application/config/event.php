<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
use lightning\system\core\event\eventDI;

$eventDI = new eventDI();

$eventDI->on('before_Parse_URL', function(){
	$instance = lightning\system\core\SystemClass::getInstance();
	$security = $instance->security;
	$security->add_security_header();
});

$eventDI->on('before_Parse_URL', function(){
	$instance = lightning\system\core\SystemClass::getInstance();
	$security = $instance->security;
	$security->url_check();
});

$eventDI->on('before_Controller_Execution', function(){
	//add html5 tag
	echo "<!DOCTYPE HTML>\r\n";
});

$eventDI->on('before_Controller_Execution', function($controller, $action, $param_arr) {
	$instance = lightning\system\core\SystemClass::getInstance();
	$request  = $instance->request;
	$security = $instance->security;
	if($request->getRequestMethod() == 'post') {
		$security->csrf_validate_post();
	} else if($controller == 'index_controller' and $action == 'testAction') {
		$security->csrf_validate(array_pop($param_arr));
	}
});

$eventDI->on('Custom_Session_event', function($msg){
	var_dump("session started, $msg");
});

return $eventDI;
?>