<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class SF_model{
	protected $db;
	public $error;

	public function __construct()
	{
		//load framework core
		$sf = framework::getInstance();
		
		if( ! $sf->get_core('sql') ){
			$database = $sf->get_config('database');
			$mysql = mysql_db::getInstance();
			$this->db = $mysql->connect(
				$database->host,
				$database->username,
				$database->password,
				$database->db_name,
				$database->port
			);
			$sf->load_core('sql', $this->db);
		} else {
			$this->db = $sf->get_core('sql');
		}
	}

	public function set_error($msg)
	{
		$this->error .= $msg . '<br />';
	}

	protected function filter($string)
	{
		$string = $this->db->real_escape_string($string);
		$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		return $string;
	}

}//end MY_model


?>