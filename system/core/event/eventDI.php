<?php
namespace lightning\system\core\event;
use lightning\system\core\SystemClass;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class eventDI extends SystemClass
{
	private $storage;

	public function __construct()
	{
		$this->storage = array();
	}

	public function on($event, $function)
	{
		$this->storage[$event][] = $function;
	}

	public function get_storage()
	{
		return $this->storage;
	}

	public function mount($eventDI)
	{
		foreach($eventDI as $event => $functions) {
			foreach($functions as $func) {
				$this->storage[$event][] = $func;
			}
		}
	}
}

?>