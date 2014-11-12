<?php namespace SF_core;
if ( ! defined('root_path')) exit('No direct script access allowed');


class singleton
{

	protected static $instance;
	protected static $called_class = array();

	private function __construct() {}
	private function __clone() {}

	public static function getInstance($para='')
	{
		$obj = get_called_class();
		if ( ! isset(self::$called_class[$obj]) ) {
			self::$called_class[$obj] = new $obj($para);
		}
		return self::$called_class[$obj];
	}

}

?>