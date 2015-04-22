<?php
namespace lightning\application\extend;
use lightning\system\core\session\session;
use lightning\system\core\SystemClass;

class session_event_emitter extends session
{
	private $em;

	public function __construct()
	{
		$instance = SystemClass::getInstance();
		$this->em = $instance->eventManager;
		parent::__construct();
	}

	public function start()
	{
		$this->em->emit('Custom_Session_event', array('session_event_emitter'));
		parent::start();
	}
}


?>