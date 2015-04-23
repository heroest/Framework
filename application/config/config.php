<?php if ( ! defined('framework_name')) exit('No direct script access allowed');
$config = array();

/* 
* ===============================================================
* website global setting
* ===============================================================
*/
$config['website']['hash_algorithm'] 	= 'md5'; //for Chash();
$config['website']['show_error_stack'] 	= True;


/* 
* ===============================================================
* session setting
* ===============================================================
*/
$config['session']['type']				= 'apc';            //'db', 'apc' or 'default'
$config['session']['duration']			= '7200'; 				//in seconds
$config['session']['db_table_name']		= 'lightning_session';	//when use db
$config['session']['key_name_prefix'] 	= 'lightning_session';  //when use memory-storage
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


/* 
* ===============================================================
* cache setting
* ===============================================================
*/
$config['cache']['key_name_prefix'] = 'lightning_storage';

return $config;
?>