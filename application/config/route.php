<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
$route = array();

$route['user/login']		 = 'user_controller/loginAction';
$route['user/register']		 = 'user_controller/registerAction';
$route['user/logout/(:any)'] = 'user_controller/logoutAction'; //(:any) for csrf_token

$route['game/kalah']		= 'game_controller/kalahAction';
$route['game/kalah/AI']     = 'game_controller/kalahAI_Action';
$route['game/hex-a-bomb']	= 'game_controller/bombAction';
$route['security/getCSRF']  = 'index_controller/getCSRF_Action';
$route['_Default']  		= 'index_controller/indexAction';


return $route;
?>