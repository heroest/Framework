<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
$route = array();

$route['user/login']		 = 'user_controller/loginAction';
$route['user/register']		 = 'user_controller/registerAction';
$route['user/logout/(:any)'] = 'user_controller/logoutAction'; //(:any) for csrf_token

$route['game']		= 'game_controller/indexAction';
$route['_Default']  = 'index_controller/indexAction';


return $route;
?>