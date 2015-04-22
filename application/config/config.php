<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
$config = array();

/* 
* ===============================================================
* website global setting
* ===============================================================
*/
$config['website']['hash_algorithm'] 	= 'md5'; //for custom_hash();
$config['website']['show_error_stack'] 	= True;


/* 
* ===============================================================
* session setting
* ===============================================================
*/
$config['session']['type']		 = 'db'; 			//'db' or 'default'
$config['session']['table_name'] = 'lightning_session'; //if use database to store session
$config['session']['duration'] 	 = '7200'; 				//in seconds

/* 
* ===============================================================
* Database setting
* ===============================================================
*/

$config['database']['type'] 	= 'mysql';
$config['database']['host'] 	= "localhost";
$config['database']['username'] = 'root';
$config['database']['password'] = '';
$config['database']['db_name'] 	= 'lightning';
$config['database']['port'] 	= '3306';


/* 
* ===============================================================
* security setting
* ===============================================================
*/
$config['security']['permitted_uri_chars'] 	= 'a-zA-Z0-9~%.:_\-';
$config['security']['csrf_token'] 			= 'csrf_token';
$config['security']['csrf_salt'] 			= '0kVEWT6fta116x16a57MAiHQpYQ6fZhI';
$config['security']['csrf_duration'] 		= 3600; //in seconds
$config['security']['csrf_token_limit']		= 5; //number of token stored in session


/*	xss_clean Option:
		no_html: 	all html tag will be esacped <default>
		no_script: <stript> tag will be esacped
		no_check: 	no tag will be escaped              */
$config['security']['xss_clean']		= 'no_script';

$config['security']['security_header']	= array(
												'X-Content-Type-Options: nosniff',
												'X-XSS-Protection: 1; mode=block',
												'X-Powered-By: ASP.NET',
												'X-Frame-Options: deny',
												'strict-transport-security: max-age=7200',
											);




return $config;
?>