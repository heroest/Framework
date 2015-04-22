<?php
namespace lightning\system\core\session\driver;
use lightning\system\MVC\AbstractModel;
use lightning\system\core\SystemClass;


if ( ! defined('framework_name')) exit('No direct script access allowed');
class session_db extends AbstractModel implements \SessionHandlerInterface
{
	private $pdo;
	private $instance;
	private $config;
	private $table_name;
	private $session_id;	

	public function __construct()
	{
		$this->instance = SystemClass::getInstance();
	}

	public function open($path, $session_name)
	{
		parent::__construct();
		$config 			= $this->instance->get_config('session');
		$this->pdo 			= $this->instance->db;
		$this->table_name 	= $config['table_name'];
	}

	public function close()
	{
		return True;
	}

	public function read($session_id)
	{
		$sql = "SELECT
				session_id, session_data
				FROM {$this->table_name}
				WHERE session_id = '{$session_id}'
				";
		$data = $this->custom_fetchRow($sql);
		if(! empty($data)) $this->session_id = $data['session_id'];
		return (empty($data))
			? ''
			: $data['session_data'];
	}

	public function write($session_id, $session_data)
	{
		$sql_data = array(
						'session_id'	=> "'$session_id'",
						'session_data' 	=> "'$session_data'",
		);
		if(empty($this->session_id)) {
			return $this->custom_insert($this->table_name, $sql_data) == 0
				? False
				: True;
		} else {
			return $this->custom_update($this->table_name, $sql_data, "session_id='$session_id'") == 0
				? False
				: True;
		}
	}

	public function destroy($session_id)
	{
		$sql_data = array('session_data' => "''");
		return $this->custom_update($this->table_name, $sql_data, "session_id='$session_id'") == 0
				? False
				: True;
	}

	public function gc($maxlifetime)
	{
		return True;
	}
}

?>