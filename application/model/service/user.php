<?php namespace lightning\application\model\service;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use \lightning\system\MVC\AbstractModel;

class user extends AbstractModel
{
	private $DAO;

	public function __construct($db_adapter = null)
	{
		$this->DAO = new \lightning\application\model\DAO\user();
	}

	public function login($username, $password)
	{
		return $this->DAO->login($username, $password);
	}

	public function register($arr)
	{
		return $this->DAO->register($arr);
	}

}


?>