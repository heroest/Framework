<?php namespace lightning\application\model;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\MVC\AbstractModel;

class user extends AbstractModel
{
	private $table = 'users';
	private $request;

	public function __construct()
	{
		parent::__construct();
		$instance = \lightning\system\core\SystemClass::getInstance();
		$this->request = $instance->request;
	}

	public function login($username, $password)
	{
		$sql = "SELECT
				id, password, salt
				FROM {$this->table}
				WHERE username = '$username'
				";
		$row = $this->custom_fetchRow($sql);
		return Cmatch(Chash($password, $row['salt']), $row['password'])
			? $row['id']
			: false;
	}

	public function register($email, $username, $password)
	{
		$salt 	  = Chash(Crandom('all', 24));
		$password = Chash($password, $salt);
		$ip       = $this->request->get_ip();
		$sql_data = array(
			'username' => $username,
			'password' => $password,
			'salt'     => $salt,
			'ip'	   => $ip,
			'email'    => $email,
			);
		return $this->custom_insert($this->table, $sql_data) == 1
			? true
			: false;
	}

}



?>