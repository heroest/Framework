<?php namespace SF_core; 
if ( ! defined('root_path')) exit('No direct script access allowed');

class db_mysql extends singleton{
	
	protected static $connection;

	protected function __construct() {}
	
	public function connect()
	{
		$ct = container::getInstance();
		$db = $ct->get_config('database');
		self::$connection =  new \mysqli($db['host'], $db['username'], $db['password'], $db['db_name'], $db['port']);
		if( self::$connection ){
			return self::$connection;
		} else {
			show_error('mysql connection failed! ' . self::$connection->connect_error);
			return;
		}
	}

}

?>