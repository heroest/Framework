<?php if ( ! defined('root_path')) exit('No direct script access allowed');

	/* 
	* ===============================================================
	* Database setting
	* ===============================================================
	*/

	$config['database']['type'] = 'mysql';
	$config['database']['host'] = "localhost";
	$config['database']['username'] = 'root';
	$config['database']['password'] = '';
	$config['database']['db_name'] = 'framework';
	$config['database']['port'] = '3306';


	/* 
	* ===============================================================
	* session setting
	* ===============================================================
	*/
	$config['session']['salt'] = 'X6bjKzI0lINE17bQ6gKG8gyjM92yxF30';
	$config['session']['duration'] = '7200'; //in seconds
	$config['session']['name'] = 'sf_session';


	/* 
	* ===============================================================
	* security setting
	* ===============================================================
	*/
	$config['security']['permitted_uri_chars'] = 'a-zA-Z0-9~%.:_\-';
	$config['security']['csrf_token_name'] = 'sf_csrf_token';
	$config['security']['csrf_salt'] = '0kVEWT6fta116x16a57MAiHQpYQ6fZhI';
	$config['security']['csrf_token_duration'] = 3600; //in seconds
	$config['security']['enable_csrf_auto_check'] = True;  





	/* 
	* ===============================================================
	* web setting
	* ===============================================================
	*/
	$config['website']['default_controller'] = 'index';


?>