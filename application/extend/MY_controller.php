<?php 
namespace SF_extend;

class MY_controller extends \SF_core\SF_controller
{

	public function __construct(){}

	public function render($view_name, $view_bag=array())
	{
		//use helloworld plugin to render page
		$this->helloworld->render($view_name, $view_bag);
		var_dump('Hello World');

	}

}


?>