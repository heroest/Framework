<?php namespace lightning\application\model\DAO;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use \lightning\system\MVC\AbstractModel;
use \lightning\system\core\SystemClass;

class user extends AbstractModel
{
	private $table = 'users';

	public function __construct($db_adapter = null)
	{
		parent::__construct($db_adapter);
	}

	public function login($username, $password)
	{
		$sql  = "SELECT password, salt, id
				 FROM {$this->table}
				 WHERE username = '{$username}'";
		$data = $this->custom_fetchRow($sql);
		if(empty($data)) return false;
		$match = Chash($password, $data['salt']);
		if( Cmatch($match, $data['password']) ) {
			return array('username' => $username, 'user_id' => $data['id']);
		} else {
			return false;
		}
	}

	public function register($arr)
	{
		$username = $arr['username'];
		$email    = $arr['email'];
		$salt     = Chash(Crandom('all'));
		$password = Chash($arr['password'], $salt);
		$ip		  = $this->request->get_ip();

		$sql = "SELECT
				id
				FROM {$this->table}
				WHERE username = {$username} OR email = {$email}
				";
		$row = $this->custom_fetchRow($sql);
		if(! empty($row)) {
			$this->add_error('Username or Email must be unique');
			return false;
		}
		$result = $this->custom_insert($this->table, array(
				'username' => $username,
				'password' => $password,
				'salt'	   => $salt,
				'email'	   => $email,
				'ip'	   => $ip,
			)
		);
		return ($result !== 1) ? false : array('username' => $username, 'user_id' => $this->last_insert_id());
	}




}

?>