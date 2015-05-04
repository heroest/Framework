<?php
namespace lightning\system\database;
use lightning\system\core\SystemClass;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class database_adapter extends SystemClass
{
	private $config;

	public function __construct()
	{
		$this->config = $this->get_config('database');
	}

	public function connect($config_arr = array())
	{
		if(! empty($config_arr)) {
			$this->config = $config_arr;
		}

		$type 		= $this->config['type'];
		$host 		= $this->config['host'];
		$port 		= $this->config['port'];
		$db_name 	= $this->config['db_name'];
		$username 	= $this->config['username'];
		$password 	= $this->config['password'];
		
		$dsn = "$type:host=$host;port=$port;dbname=$db_name;charset=utf8";
		$option = array(
			\PDO::ATTR_ERRMODE 			=> \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_EMULATE_PREPARES => False,
			\PDO::ATTR_PERSISTENT 		=> true
			);
		try {
			$pdo = new \PDO($dsn, $username, $password, $option);
			return $pdo;
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			show_error("Error in database_adapter->connect($type://$host:$port): $msg");
		}
	}
}


?>