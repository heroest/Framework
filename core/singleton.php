<?php


class singleton
{

	protected static $instance;
	protected static $called_class = array();

	private function __construct() {}
	private function __clone() {}

	public static function getInstance()
	{
		$obj = get_called_class();
		if ( ! isset(self::$called_class[$obj]) ) {
			self::$called_class[$obj] = new $obj();
		}
		return self::$called_class[$obj];
	}

}