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

	public function render( $view_name, $view_bag = array() )
	{

		//fetch view_bag
		foreach( $view_bag as $key=>$value ){
			$$key = $value;
		}
		if(file_exists( viewDir . $view_name . '.php' )){

			include(viewDir . $view_name . '.php');

		} else {

			show_404(viewDir . $view_name, ' view is missing');

		}

	}


}//end class helloworld






?>