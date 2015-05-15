<?php namespace lightning\system\MVC; 
if ( ! defined('framework_name')) exit('No direct script access allowed');


use lightning\system\core\SystemClass;

class application extends SystemClass
{
	private $_config;
	private $_url;
	private $_controller;
	private $_action;
	private $_param;
	private $_page;

	public function __construct($di='')
	{
		$sysClass = SystemClass::getInstance();
		$di->set('application', $this);
		if(! empty($di)) {
			$sysClass->set_DI($di);
		}
	}

	public function handle()
	{
		$this->_url = $this->request->getQuery('_url');
		$this->eventManager->emit('event_parse_url');
		
		list($this->_controller, $this->_action, $this->_param) = $this->router->parse($this->_url);
		return $this->dispatch();
	}

	public function dispatch()
	{
		ob_start();
		$this->eventManager->emit('event_controller_execution');

		$controller = $this->_controller;
		$action 	= $this->_action;
		$param 		= $this->_param;

		$controller_class = "lightning\application\controller\\$controller";
		$dispatcher = new $controller_class();

		
		if(method_exists($dispatcher, $action)) {
			$this->eventManager
							->emit("event_$action")
							->emit("event_$controller")
							->emit("event_$controller->$action");
			call_user_func_array(array($dispatcher, $action), $param);
		} else {
			show_error("Error in application->Dispatch(): [$action] does not exists in [$controller]");
		}

		$this->_page = ob_get_contents();
		$this->eventManager->emit('event_get_page');
		ob_end_clean();
		return $this->_page;
	}

	public function get($type)
	{
		$type = "_$type";
		return $this->$type;
	}

	public function set($type, $data)
	{
		$type = "_$type";
		$this->$type = $data;
	}
}


?>