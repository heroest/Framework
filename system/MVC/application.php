<?php
namespace lightning\system\MVC;
use lightning\system\core\SystemClass;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class application extends SystemClass
{
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
		$this->eventManager->emit('before_parse_URL');
		$url = $this->request->getQuery('_url');
		list($controller, $action, $param_arr) = $this->router->parse($url);
		return $this->dispatch($controller, $action, $param_arr);
	}

	public function dispatch($controller, $action, $param_arr=array())
	{
		ob_start();

		$controller_class = "lightning\application\controller\\$controller";
		$dispatcher = new $controller_class();

		$this->eventManager->emit('before_controller_execution',
								array(
										'controller' => $controller,
										'action'	 => $action,
										'param_arr'	 => $param_arr,
									));
		if(method_exists($dispatcher, $action)) {
			call_user_func_array(array($dispatcher, $action), $param_arr);
		} else {
			show_error("Error in application->Dispatch(): [$action] does not exists in [$controller]");
		}

		$this->eventManager->emit('before_get_output');
		$page = ob_get_contents();
		ob_end_clean();
		return $page;
	}
}


?>