<?php namespace lightning\system\MVC;
if ( ! defined('framework_name')) exit('No direct script access allowed');

use lightning\system\core\SystemClass;

class AbstractModel extends SystemClass
{

	protected static $db;
	protected $_error;
	private $config;

	public function __construct($db_adapter = null)
	{	
		self::$db = is_null($db_adapter) ? $this->db : $db_adapter;
	}

	protected function custom_fetchRow($sql)
	{
		try {
			$stmt = self::$db->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
			return $result;
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			$msg = "Error in AbstractModel->custom_fetchRow($sql): $msg";
			$this->add_error($msg);
		}
	}

	protected function custom_fetchAll($sql)
	{
		try {
			$stmt = self::$db->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			return $result;
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			$msg = "Error in AbstractModel->custom_fetchAll($sql): $msg";
			$this->add_error($msg);
		}
	}

	protected function custom_update($table_name, $data, $where)
	{
		try {
			$sql_data = array();

			$keys   = array_keys($data);
			$sql_data = $this->array_add($sql_data, array_values($data));
			$keys = array_map(function($item){
				return "$item=?";
			}, $keys);
			$placeholder = implode(", ", $keys);

			$where_keys = array_keys($where);
			$sql_data = $this->array_add($sql_data, array_values($where));
			$where_keys = array_map(function($item){
				return "$item=?";
			}, $where_keys);
			$where_placehoder = implode(" AND ", $where_keys);

			$sql = "UPDATE {$table_name} SET {$placeholder} WHERE {$where_placehoder}";

			$stmt = self::$db->prepare($sql);
			$stmt->execute($sql_data);
			return $stmt->rowCount();
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			$msg = "Error in AbstractModel->custom_update($sql): $msg";
			$this->add_error($msg);
		}
	}

	protected function custom_insert($table_name, $data, $multiple = False)
	{
		try {
			$keys 	= array();
			$values = array();

			if(! $multiple) {
				$keys 	  = array_keys($data);
				$values[] = array_values($data);
			} else {
				foreach($data as $item) {
					$keys 	  = array_keys($item);
					$values[] = array_values($item);
				}
			}

			$key_stmt 	 = "(" . implode(", ", $keys) . ")";
			$placeholder_arr = array();
			$sql_data = array();
			foreach($values as $value) {
				$placeholder_arr[] = "(" . implode(", " , array_fill(0, count($keys), "?")) . ")";
					$sql_data += $value;
			}
			$placeholder = implode(", ", $placeholder_arr);
			$sql = "INSERT INTO {$table_name} {$key_stmt} VALUES {$placeholder}";
			$stmt = self::$db->prepare($sql);
			$stmt->execute($sql_data);
			return $stmt->rowCount();
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			$msg = "Error in AbstractModel->custom_insert($sql): $msg";
			$this->add_error($msg);
		}
	}

	protected function custom_query($sql)
	{
		try {
			$stmt = self::$db->prepare($sql);
			return $stmt->execute() ? True : False;
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			$msg = "Error in AbstractModel->custom_query($sql): $msg";
			$this->add_error($msg);
		}
	}

	protected function last_insert_id($name = '')
	{
		return self::$db->lastInsertId($name);
	}

	protected function array_add($arr_a, $arr_b) 
	{
		foreach($arr_b as $item) {
			$arr_a[] = $item;
		}
		return $arr_a;
	}

	public function add_error($msg)
	{
		$msg = "<p class='text-danger'>{$msg}</p>";
		$this->_error .= $msg;
	}

	public function get_error()
	{
		return $this->_error;
	}

}


?>