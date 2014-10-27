<?php

namespace SF_core;
class mysql_db extends singleton{
	
	protected static $connection;

	public function __construct() {}
	
	public function connect($db_host, $db_username, $db_password, $db_dbname, $db_port = 3306)
	{
		self::$connection =  new \mysqli($db_host, $db_username, $db_password, $db_dbname, $db_port);
		if( ! self::$connection->connect_errno ){
			return self::$connection;
		} else {
			show_error('DB connection failed! ' . self::$connection->connect_error);
			return;
		}
	}

}

?>