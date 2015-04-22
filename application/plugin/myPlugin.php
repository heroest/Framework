<?php
namespace lightning\application\plugin;
use lightning\system\MVC\AbstractController;

if ( ! defined('framework_name')) exit('No direct script access allowed');
class myPlugin extends AbstractController
{
	public function welcome($name)
	{
		return "Welcome, $name";
	}
}


?>