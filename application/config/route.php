<?php if ( ! defined('root_path')) exit('No direct script access allowed');
//default controller
$route['Default_Controller'] 	=	'index_controller/indexAction';



$route['code/viewcode'] 		=	'code_controller/viewcodeAction';

$route['login'] 				=	'user_controller/loginAction';
$route['dologin'] 				=	'user_controller/dologinAction';
$route['register'] 				=	'user_controller/registerAction';
$route['doregister'] 			=	'user_controller/doregisterAction';
$route['logout'] 				=	'user_controller/logoutAction';

$route['index/newpost'] 		=	'index_controller/newpostAction';
$route['index/postmsg']			= 	'index_controller/postmsgAction';

$route['helloworld']            = 	'index_controller/helloworldAction';

?>