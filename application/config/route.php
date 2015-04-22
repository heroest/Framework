<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
$route = array();

$route['path']							   = 'index_controller/pathAction';
$route['game']							   = 'game_controller/indexAction';
$route['test/(:num)/(:num)/(:num)/(:any)'] = 'index_controller/testAction';
$route['_Default']                         = 'index_controller/indexAction';


return $route;
?>