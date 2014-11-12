<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class SF_model{
	protected $db;
	public $error;

	public function __construct()
	{
		$container = container::getInstance();
		$this->db = $container->db;
	}

	public function set_error($msg)
	{
		$this->error .= $msg . '<br />';
	}

	protected function filter($string)
	{
		$string = $this->db->real_escape_string($string);
		return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}

}//end MY_model


?>