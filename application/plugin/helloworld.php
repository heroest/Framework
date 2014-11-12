<?php if ( ! defined('root_path')) exit('No direct script access allowed');
class helloworld extends \SF_core\container
{
	private $config;

	public function __construct()
	{
		$this->config = $this->get_config('helloworld');
	}

	public function say_hello()
	{
		echo 'Hello, I am ' . $this->config['name'];
	}


}






?>