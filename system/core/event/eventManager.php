<?php
namespace lightning\system\core\event;
use lightning\system\core\SystemClass;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class eventManager extends SystemClass
{
	private $listener = array();

	public function __construct($eventDI)
	{
		$this->attachDI($eventDI);
	}

	public function emit($event)
	{
		if(isset($this->listener[$event])) {
			foreach($this->listener[$event] as $function)
			{
				call_user_func_array($function, array($this->application));
			}
		}
		return $this;
	}

	public function attachDI($eventDI)
	{
		$this->listener = $eventDI->get_storage();
	}
}

?>