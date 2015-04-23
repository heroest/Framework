<?php
namespace lightning\system\MVC;
use lightning\system\core\SystemClass;

class AbstractModel extends SystemClass
{

	protected static $db;

	public function __construct()
	{
		self::$db = $this->db;
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
			show_error("Error in AbstractModel->custom_fetchRow($sql): $msg");
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
			show_error("Error in AbstractModel->custom_fetchAll($sql): $msg");
		}
	}

	protected function custom_update($table_name, $data, $where)
	{
		try {
			$arr = array();
			foreach($data as $key=>$val) {
				$arr[] = "$key=$val";
			}
			$set_stmt = implode(",", $arr);
			$sql = "UPDATE {$table_name} SET {$set_stmt} WHERE {$where}";
			$stmt = self::$db->prepare($sql);
			$stmt->execute();
			return $stmt->rowCount();
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			show_error("Error in AbstractModel->custom_update($sql): $msg");
		}
	}

	protected function custom_insert($table_name, $data, $multiple = False)
	{
		try {
			$keys 	= array();
			$values = array();

			if(! $multiple) {
				$keys 	= array_keys($data);
				$values = array_values($data);
				$value_stmt = "(" . implode(",", $values) . ")";
			} else {
				foreach($data as $element) {
					$keys 		= array_keys($element);
					$values[] 	= "(" . implode(",", array_values($element)) . ")";
				}
				$value_stmt = implode(",", $values);
			}

			$key_stmt 	= "(" . implode(",", $keys) . ")";
			$sql = "INSERT INTO {$table_name} {$key_stmt} VALUES {$value_stmt}";
			$stmt = self::$db->prepare($sql);
			$stmt->execute();
			return $stmt->rowCount();
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			show_error("Error in AbstractModel->custom_insert($sql): $msg");
		}
	}

	protected function custom_query($sql)
	{
		try {
			$stmt = self::$db->prepare($sql);
			return $stmt->execute() ? True : False;
		} catch (\PDOException $e) {
			$msg = $e->getMessage();
			show_error("Error in AbstractModel->custom_query($sql): $msg");
		}
	}
}


?>