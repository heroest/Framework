<?php
namespace SF_core;
class SF_controller extends container
{
	protected $error='';

	public function __construct()
	{

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
	}//end render

	public function set_error($msg)
	{
		$this->error .= $msg . '<br/>';
	}


}


?>