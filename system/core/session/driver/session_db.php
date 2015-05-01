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
	private $index_id;

	public function __construct()
	{
		$this->instance = SystemClass::getInstance();
	}

	public function open($path, $session_name)
	{
		parent::__construct();
		$config 			= $this->instance->get_config('session');
		$this->pdo 			= $this->instance->db;
		$this->table_name   = $config['db_table_name'];
	}

	public function close()
	{
		return True;
	}

	public function read($session_id)
	{
		$sql = "SELECT
				id, session_id, session_data
				FROM {$this->table_name}
				WHERE session_id = '{$session_id}'
				";
		$data = $this->custom_fetchRow($sql);
		if(isset($data['session_id'])) {
			$this->index_id = $data['id'];
			return $data['session_data'];
		} else {
			return '';
		}
	}

	public function write($session_id, $session_data)
	{
		$sql_data = array(
						'session_id'	=> "$session_id",
						'session_data' 	=> "$session_data",
		);
		
		if(! empty($this->index_id)) {
			return ($this->custom_update($this->table_name, $sql_data, array('id' => $this->index_id)) == 1)
			 	? True
			 	: False;
		} else {
			return $this->custom_insert($this->table_name, $sql_data) == 1
				? True
				: False;
		}
		

	}

	public function destroy($session_id)
	{
		$sql = "DELETE FROM {$this->table_name} WHERE session_id='$session_id'";
		return $this->custom_query($sql);
	}

	public function gc($maxlifetime)
	{
		return True;
	}
}

?>